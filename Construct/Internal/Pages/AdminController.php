<?php
/**
 * @package construct
 *
 */

namespace Construct\Internal\Pages;


class AdminController extends \Construct\Controller
{


    public function index()
    {
        ipAddJsVariable('ipTranslationAreYouSure', __('Are you sure?', 'Construct-admin', false));

        ipAddJs('Construct/Internal/Core/assets/js/angular.js');
        ipAddJs('Construct/Internal/Pages/assets/js/pages.js');
        ipAddJs('Construct/Internal/Pages/assets/js/pagesLayout.js');
        ipAddJs('Construct/Internal/Pages/assets/js/menuList.js');
        ipAddJs('Construct/Internal/Pages/assets/jstree/jstree.min.js');

        ipAddJs('Construct/Internal/Pages/assets/js/jquery.pageTree.js');
        ipAddJs('Construct/Internal/Pages/assets/js/jquery.pageProperties.js');

        ipAddJs('Construct/Internal/Grid/assets/grid.js');
        ipAddJs('Construct/Internal/Grid/assets/gridInit.js');
        ipAddJs('Construct/Internal/Grid/assets/subgridField.js');


        ipAddJsVariable('languageList', Helper::languageList());
        ipAddJsVariable('ipPagesLanguagesPermission', ipAdminPermission('Languages'));

        $menus = Model::getMenuList();
        foreach($menus as $key => &$menu) {
            $default = 'top';
            if ($key == 0) {
                $default = 'bottom';
            }
            $menu['defaultPosition'] = Model::getDefaultMenuPagePosition($menu['alias'], false, $default);
            $default = 'below';
            $menu['defaultPositionWhenSelected'] = Model::getDefaultMenuPagePosition($menu['alias'], true, $default);
        }
        $menus = ipFilter('ipPagesMenuList', $menus);
        ipAddJsVariable('menuList', $menus);

        $variables = array(
            'addPageForm' => Helper::addPageForm(),
            'addMenuForm' => Helper::addMenuForm(),
            'languagesUrl' => ipConfig()->baseUrl() . '?aa=Languages.index'
        );
        $layout = ipView('view/layout.php', $variables);

        ipResponse()->setLayoutVariable('removeAdminContentWrapper', true);
        ipAddJsVariable('listStylePageSize', ipGetOption('Pages.pageListSize', 30));

        return $layout->render();
    }

    public function pagesGridGateway()
    {
        $parentId = ipRequest()->getRequest('parentId');
        if (!$parentId) {
            throw new \Construct\Exception('Missing required parameter');
        }

        $worker = new \Construct\Internal\Grid\Worker(Helper::pagesGridConfig($parentId));
        $result = $worker->handleMethod(ipRequest());
        return new \Construct\Response\JsonRpc($result);
    }

    public function getPages()
    {
        $data = ipRequest()->getRequest();
        if (empty($data['languageId'])) {
            throw new \Construct\Exception("Missing required parameters");
        }
        $language = ipContent()->getLanguage($data['languageId']);
        if (!$language) {
            throw new \Construct\Exception("Language doesn't exist. " . $data['languageId']);
        }
        $languageCode = $language->getCode();

        if (empty($data['menuName'])) {
            throw new \Construct\Exception("Missing required parameters");
        }
        $menuName = $data['menuName'];

        $parentId = constructQuery()->selectValue(
            'page',
            'id',
            array('languageCode' => $languageCode, 'alias' => $menuName, 'isDeleted' => 0)
        );

        $tree = JsTreeHelper::getPageTree($languageCode, $parentId);
        $tree = ipFilter('ipPageTree', $tree, array('languageCode' => $languageCode, 'parentId' => $parentId));

        $responseData = array(
            'tree' => $tree
        );

        return new \Construct\Response\Json($responseData);

    }

    public function pagePropertiesForm()
    {
        $pageId = ipRequest()->getQuery('pageId');
        if (!$pageId) {
            throw new \Construct\Exception("Missing required parameters");
        }

        $variables = array(
            'form' => Helper::pagePropertiesForm($pageId)
        );
        $layout = ipView('view/pageProperties.php', $variables)->render();

        $data = array(
            'html' => $layout
        );
        return new \Construct\Response\Json($data);
    }

    public function updatePage()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();

        if (empty($data['pageId'])) {
            throw new \Construct\Exception("Missing required parameters");
        }
        $pageId = (int)$data['pageId'];
        $page = ipContent()->getPage($pageId);
        if (!$page) {
            throw new \Construct\Exception("Page doesn't exist");
        }

        $answer = [];
        if (strtotime($data['createdAt']) === false) {
            $answer['errors']['createdAt'] = __('Incorrect date format. Example:', 'Construct-admin', false) . date(" Y-m-d");
        }

        if (strtotime($data['updatedAt']) === false) {
            $answer['errors']['updatedAt'] = __('Incorrect date format. Example:', 'Construct-admin', false) . date(" Y-m-d");
        }

        if ($data['alias'] != $page->getAlias()) {
            if (Model::getPageByAlias($page->getLanguageCode(), $data['alias'])) {
                $answer['errors']['alias'] = __('This alias is already occupied', 'Construct-admin');
            }
        }


        $data['isVisible'] = !empty($data['isVisible']);
        $data['isDisabled'] = !empty($data['isDisabled']);
        $data['isSecured'] = !empty($data['isSecured']);
        $data['isBlank'] = !empty($data['isBlank']);
        if ($page->getUrlPath() == $data['urlPath']) {
            unset($data['urlPath']);
        }
        if (empty($answer['errors'])) {
            Model::updatePageProperties($pageId, $data);

            ipEvent('ipFormUpdatePageSubmitted', array($data));

            $answer['status'] = 'success';
            $answer['newPageUrl'] = ipPage($pageId)->getUrlPath();
        } else {
            $answer['status'] = 'error';
        }

        return new \Construct\Response\Json($answer);
    }

    public function updateMenuForm()
    {
        $menuId = (int)ipRequest()->getQuery('id');
        if (empty($menuId)) {
            throw new \Construct\Exception("Missing required parameters");
        }

        $form = Helper::menuForm($menuId);
        $html = $form->render();

        $data = array(
            'html' => $html
        );
        return new \Construct\Response\Json($data);
    }


    public function addPage()
    {
        ipRequest()->mustBePost();

        $parentId = ipRequest()->getPost('parentId');
        if (empty($parentId)) {
            throw new \Construct\Exception("Missing required parameters");
        }

        $title = ipRequest()->getPost('title');
        if ($title === '') {
            $title = __('Untitled', 'Construct-admin', false);
        }

        $isVisible = ipRequest()->getPost('isVisible', 0);

        $pageId = Service::addPage($parentId, $title, array('isVisible' => $isVisible));
        $position = ipRequest()->getPost('position');
        if ($position !== null) {
            Service::movePage($pageId, $parentId, $position);
        }

        $eventData = ipRequest()->getPost();
        ipEvent('ipFormCreatePageSubmitted', $eventData);



        $answer = array(
            'status' => 'success',
            'pageId' => $pageId
        );

        return new \Construct\Response\Json($answer);

    }

    public function setDefaultPagePosition()
    {
        ipRequest()->mustBePost();
        $alias = ipRequest()->getPost('alias');
        $isPageSelected = ipRequest()->getPost('isPageSelected');
        $position = ipRequest()->getPost('position');

        Model::setDefaultMenuPagePosition($alias, $isPageSelected, $position);

        return new \Construct\Response\Json(1);
    }

    public function deletePage()
    {
        ipRequest()->mustBePost();

        $pageId = (int)ipRequest()->getPost('pageId');
        if (!$pageId) {
            throw new \Construct\Exception("Page id is not set");
        }

        Service::deletePage($pageId);

        $answer = [];
        $answer['status'] = 'success';

        return new \Construct\Response\Json($answer);
    }

    public function movePage()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();


        if (!isset($data['pageId'])) {
            throw new \Construct\Exception("Page id is not set");
        }
        $pageId = (int)$data['pageId'];


        if (!empty($data['destinationParentId'])) {
            $destinationParentId = $data['destinationParentId'];
        } else {
            if (!isset($data['languageId'])) {
                throw new \Construct\Exception("Missing required parameters");
            }
            throw new \Construct\Exception\NotImplemented();
        }


        if (!isset($data['destinationPosition'])) {
            throw new \Construct\Exception("Destination position is not set");
        }
        $destinationPosition = $data['destinationPosition'];


        try {
            Model::movePage($pageId, $destinationParentId, $destinationPosition);
        } catch (\Construct\Exception $e) {
            $answer = array(
                'status' => 'error',
                'error' => $e->getMessage()
            );
            return new \Construct\Response\Json($answer);
        }


        $answer = array(
            'status' => 'success'
        );

        return new \Construct\Response\Json($answer);


    }


    public function copyPage()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();


        if (!isset($data['pageId'])) {
            throw new \Construct\Exception("Page id is not set");
        }
        $pageId = (int)$data['pageId'];


        if (!isset($data['destinationParentId'])) {
            throw new \Construct\Exception("Missing required parameter");
        }
        $destinationParentId = $data['destinationParentId'];

        if (!isset($data['destinationPosition'])) {
            throw new \Construct\Exception("Destination position is not set");
        }
        $destinationPosition = $data['destinationPosition'];


        try {
            Service::copyPage($pageId, $destinationParentId, $destinationPosition);
        } catch (\Construct\Exception $e) {
            $answer = array(
                'status' => 'error',
                'error' => $e->getMessage()
            );
            return new \Construct\Response\Json($answer);
        }


        $answer = array(
            'status' => 'success'
        );

        return new \Construct\Response\Json($answer);


    }

    public function getPageUrl()
    {
        $data = ipRequest()->getQuery();


        if (!isset($data['pageId'])) {
            throw new \Construct\Exception("Page id is not set");
        }
        $pageId = (int)$data['pageId'];

        $page = new \Construct\Page($pageId);

        $answer = array(
            'pageUrl' => $page->getLink()
        );

        return new \Construct\Response\Json($answer);
    }

    public function updateMenu()
    {
        $request = ipRequest();

        $menuId = $request->getPost('id');
        $title = $request->getPost('title');
        $alias = $request->getPost('alias');
        $layout = $request->getPost('layout');
        $type = $request->getPost('type');

        if (empty($menuId) || empty($title) || empty($alias) || empty($layout) || empty($type)) {
            throw new \Construct\Exception('Missing required parameters');
        }

        // validate page alias
        $page = Model::getPage($menuId);

        $errors = [];

        if ($page['alias'] != $alias) {
            if (Model::getPageByAlias($page['languageCode'], $alias)) {
                $errors['alias'] = __('This alias is already occupied', 'Construct-admin');
            }
        }

        if ($errors) {
            return new \Construct\Response\Json(array(
                'status' => 'error',
                'errors' => $errors,
            ));
        }

        Service::updateMenu($menuId, $alias, $title, $layout, $type);

        $eventData = $request->getPost();
        ipEvent('ipFormUpdateMenuSubmitted', $eventData);



        $answer = array(
            'status' => 'ok'
        );

        return new \Construct\Response\Json($answer);
    }


    public function createMenu()
    {
        $request = ipRequest();
        $request->mustBePost();
        $languageCode = $request->getPost('languageCode');
        $title = $request->getPost('title');
        $type = $request->getPost('type');

        if (empty($title)) {
            $title = __('Untitled', 'Construct-admin', false);
        }

        $pageId = Service::createMenu($languageCode, null, $title, $type);

        $menu = ipContent()->getPage($pageId);

        $eventData = $request->getPost();
        $eventData['id'] = $pageId;
        ipEvent('ipFormCreateMenuSubmitted', $eventData);

        $answer = array(
            'status' => 'success',
            'menuName' => $menu->getAlias()
        );

        return new \Construct\Response\Json($answer);
    }

    public function changeMenuOrder()
    {
        ipRequest()->mustBePost();

        $menuId = ipRequest()->getPost('menuId');
        $newIndex = ipRequest()->getPost('newIndex');

        if (empty($menuId) || !isset($newIndex)) {
            throw new \Construct\Exception("Missing required parameters");
        }

        Model::changeMenuOrder($menuId, $newIndex);

        return new \Construct\Response\Json(array(
            'error' => 0
        ));
    }

}
