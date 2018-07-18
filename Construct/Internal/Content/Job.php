<?php


namespace Construct\Internal\Content;


class Job
{
    /**
     * @param $info
     * @return array|null
     * @throws \Construct\Exception
     */
    public static function ipRouteAction_80($info)
    {
        if ($info['routeLanguage'] == null) {
            return null;
        }

        if ($info['relativeUri'] == '') {
            $pageId = ipContent()->getDefaultPageId();
            $page = \Construct\Internal\Pages\Service::getPage($pageId);
        } else {
            $languageCode = ipContent()->getCurrentLanguage()->getCode();
            $page = \Construct\Internal\Pages\Service::getPageByUrl($languageCode, $info['relativeUri']);
        }

        if (!$page || $page['isSecured'] && !ipAdminId()) {
            return null;
        }

        $result['page'] = new \Construct\Page($page);
        $result['plugin'] = 'Content';
        $result['controller'] = 'PublicController';
        $result['action'] = 'index';
        $result['urlParts'] = isset($urlParts[1]) ? explode('/', $urlParts[1]) : [];

        return $result;
    }

    /**
     * @param $info
     * @return \Construct\Response\Redirect
     */
    public static function ipExecuteController($info)
    {
        $page = ipContent()->getCurrentPage();
        if ($page && $page->getRedirectUrl() && !ipAdminId()) {
            return new \Construct\Response\Redirect($page->getRedirectUrl());
        }
        return null;
    }

    /**
     * @return mixed
     */
    public static function ipDefaultPageId_70($info)
    {
        $languageCode = $info['languageCode'];
        $defaultPageId = ipGetOption('Config.defaultPageId_' . $languageCode, null);

        if ($defaultPageId) {
            return $defaultPageId;
        }


        $menus = \Construct\Internal\Pages\Service::getMenus($languageCode);


        foreach ($menus as $menu) {
            $pages = \Construct\Internal\Pages\Service::getChildren($menu['id'], 0, 1);
            if (!empty($pages[0]['id'])) {
                return $pages[0]['id'];
            }
        }

        return null;
    }
}
