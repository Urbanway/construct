<?php
/**
 * @package construct
 *
 */

namespace Construct\Internal\Pages;


class Helper
{

    public static function pagesGridConfig($parentId)
    {
        $config = array(
            'table' => 'page',
            'title' => false,
            'allowCreate' => false,
            'allowSearch' => false,
            'allowDelete' => false,
            'allowUpdate' => false,
            'sortField' => 'pageOrder',
            'pageSize' => ipGetOption('Pages.pageListSize', 30),
            'layout' => 'Construct/Internal/Pages/view/grid/layout.php',
            'pagerSize' => 5,
            'pageVariableName' => 'gpage',
            'filter' => 'isDeleted = 0 and parentId = ' . (int)$parentId, //rename to sqlWhere
            'fields' => array(
                array(
                    'label' => __('Title', 'Construct-admin', false),
                    'field' => 'title'
                )
            )
        );

        $config = ipFilter('ipPageListGridConfig', $config, array('parentId' => $parentId));

        return $config;
    }


    public static function languageList()
    {
        $answer = [];
        $languages = ipContent()->getLanguages();
        foreach ($languages as $language) {
            $answer[] = array(
                'id' => $language->getId(),
                'title' => $language->getTitle(),
                'abbreviation' => $language->getAbbreviation(),
                'code' => $language->getCode(),
            );
        }
        return $answer;
    }

    public static function menuForm($menuId)
    {
        $menu = Model::getPage((int)$menuId);

        if (!$menu) {
            throw new \Construct\Exception('Menu not found.', array('id' => $menuId));
        }

        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'Pages.updateMenu'
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'id',
                'value' => $menu['id']
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'title',
                'label' => __('Title', 'Construct-admin', false),
                'value' => $menu['title']
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'alias',
                'label' => __('Menu name (used in PHP code)', 'Construct-admin', false),
                'value' => $menu['alias']
            ));
        $form->addField($field);

        $layouts = \Construct\Internal\Design\Service::getLayouts();

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'layout',
                'label' => __('Layout', 'Construct-admin', false),
                'value' => $menu['layout'],
                'values' => $layouts,
            ));
        $form->addField($field);

        $values = array(
            array('tree', __('Tree (for menu)', 'Construct-admin', false)),
            array('list', __('List (for blogs)', 'Construct-admin', false)),
        );
        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'type',
                'label' => __('Type', 'Construct-admin', false),
                'value' => $menu['type'],
                'values' => $values,
            ));
        $form->addField($field);

        $form = ipFilter('ipMenuForm', $form, array('menuId' => $menuId));

        return $form;
    }

    public static function pagePropertiesForm($pageId)
    {
        $page = new \Construct\Page($pageId);

        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);


        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'aa',
                'value' => 'Pages.updatePage'
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'pageId',
                'value' => $pageId
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'title',
                'label' => __('Title', 'Construct-admin', false),
                'value' => $page->getTitle()
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'urlPath',
                'label' => __('URL path', 'Construct-admin', false),
                'value' => $page->getUrlPath(),
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'isVisible',
                'label' => __('Visible', 'Construct-admin', false),
                'value' => $page->isVisible()
            ));
        $form->addField($field);


        $layouts = \Construct\Internal\Design\Service::getLayouts();

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'layout',
                'label' => __('Layout', 'Construct-admin', false),
                'values' => $layouts,
                'value' => $page->getLayout()
            ));
        $form->addField($field);

        $fieldset = new \Construct\Form\Fieldset(__('Seo', 'Construct-admin', false));
        $form->addFieldset($fieldset);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'metaTitle',
                'label' => __('Meta title', 'Construct-admin', false),
                'value' => $page->getMetaTitle()
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'keywords',
                'label' => __('Keywords', 'Construct-admin', false),
                'value' => $page->getKeywords()
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Textarea(
            array(
                'name' => 'description',
                'label' => __('Description', 'Construct-admin', false),
                'value' => $page->getDescription()
            ));
        $form->addField($field);


        $fieldset = new \Construct\Form\Fieldset(__('Other', 'Construct-admin', false));
        $form->addFieldset($fieldset);

        $field = new \Construct\Form\Field\Url(
            array(
                'name' => 'redirectUrl',
                'label' => __('Redirect', 'Construct-admin', false),
                'value' => $page->getRedirectUrl()
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'isDisabled',
                'label' => __('Disabled', 'Construct-admin', false),
                'value' => $page->isDisabled(),
                'note' => __('Won\'t be clickable in menu if selected.', 'Construct-admin', false),
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'isSecured',
                'label' => __('Secured', 'Construct-admin', false),
                'value' => $page->isSecured(),
                'note' => __('Won\'t be accessible to view even knowing the URL.', 'Construct-admin', false),
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'isBlank',
                'label' => __('Open in new window', 'Construct-admin', false),
                'value' => $page->isBlank()
            ));
        $form->addField($field);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'alias',
                'label' => __('Alias (used in code)', 'Construct-admin', false),
                'value' => $page->getAlias()
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'createdAt',
                'label' => __('Created on', 'Construct-admin', false),
                'value' => date('Y-m-d', strtotime($page->getCreatedAt()))
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'updatedAt',
                'label' => __('Updated on', 'Construct-admin', false),
                'value' => date('Y-m-d', strtotime($page->getUpdatedAt()))
            ));
        $form->addField($field);

        $form = ipFilter('ipPagePropertiesForm', $form, array('pageId' => $pageId));

        return $form;
    }

    public static function addPageForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $form->setAjaxSubmit(false);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'title',
                'label' => __('Title', 'Construct-admin', false)
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'isVisible',
                'label' => __('Visible', 'Construct-admin', false),
                'value' => !ipGetOption('Pages.hideNewPages', 0)
            ));
        $form->addField($field);

        $values = array(
            array('top', __('Top', 'Construct-admin', false)),
            array('above', __('Above selected', 'Construct-admin', false)),
            array('child', __('Child of selected', 'Construct-admin', false)),
            array('below', __('Below selected', 'Construct-admin', false)),
            array('bottom', __('Bottom', 'Construct-admin', false)),
        );
        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'position',
                'label' => __('Position', 'Construct-admin', false),
                'values' => $values,
                'value' => 'below'
            ));
        $form->addField($field);

        return $form;
    }

    public static function addMenuForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'title',
                'label' => __('Title', 'Construct-admin', false)
            ));
        $form->addField($field);

        $values = array(
            array('tree', __('Tree (for menu)', 'Construct-admin', false)),
            array('list', __('List (for blogs)', 'Construct-admin', false)),
        );
        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'type',
                'label' => __('Type', 'Construct-admin', false),
                'values' => $values,
            ));
        $form->addField($field);

        return $form;
    }


}
