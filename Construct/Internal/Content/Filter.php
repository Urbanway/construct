<?php


namespace Construct\Internal\Content;


class Filter
{
    public static function ipWidgets($widgets)
    {

        $widgets['Heading'] = new \Construct\Internal\Content\Widget\Heading\Controller('Heading', 'Content', 1);
        $widgets['Text'] = new \Construct\Internal\Content\Widget\Text\Controller('Text', 'Content', 1);
        $widgets['Divider'] = new \Construct\Internal\Content\Widget\Divider\Controller('Divider', 'Content', 1);
        $widgets['Image'] = new \Construct\Internal\Content\Widget\Image\Controller('Image', 'Content', 1);
        $widgets['Gallery'] = new \Construct\Internal\Content\Widget\Gallery\Controller('Gallery', 'Content', 1);
        $widgets['File'] = new \Construct\Internal\Content\Widget\File\Controller('File', 'Content', 1);
        $widgets['Html'] = new \Construct\Internal\Content\Widget\Html\Controller('Html', 'Content', 1);
        $widgets['Columns'] = new \Construct\Internal\Content\Widget\Columns\Controller('Columns', 'Content', 1);
        $widgets['Form'] = new \Construct\Internal\Content\Widget\Form\Controller('Form', 'Content', 1);
        $widgets['Video'] = new \Construct\Internal\Content\Widget\Video\Controller('Video', 'Content', 1);
        $widgets['Map'] = new \Construct\Internal\Content\Widget\Map\Controller('Map', 'Content', 1);


        $widgetDirs = static::getPluginWidgetDirs();
        foreach ($widgetDirs as $widgetDirRecord) {
            $widgetKey = $widgetDirRecord['widgetKey'];
            $widgetClass = '\\Plugin\\' . $widgetDirRecord['module'] . '\\' . Model::WIDGET_DIR . '\\' . $widgetKey . '\\Controller';
            if (class_exists($widgetClass)) {
                $widget = new $widgetClass($widgetKey, $widgetDirRecord['module'], 0);
            } else {
                $widget = new \Construct\WidgetController($widgetKey, $widgetDirRecord['module'], 0);
            }
            $widgets[$widgetDirRecord['widgetKey']] = $widget;
        }
        return $widgets;
    }

    /**
     * Form widget
     * @param $fieldTypes
     * @param null $info
     * @return \Construct\Internal\Content\FieldType[]
     * @internal param array $value
     */
    public static function ipWidgetFormFieldTypes($fieldTypes, $info = null)
    {

        $typeText = __('Text', 'Construct-admin', false);
        $typeEmail = __('Email', 'Construct-admin', false);
        $typeTextarea = __('Textarea', 'Construct-admin', false);
        $typeSelect = __('Select', 'Construct-admin', false);
        $typeCheckbox = __('Checkbox', 'Construct-admin', false);
        $typeRadio = __('Radio', 'Construct-admin', false);
        $typeCaptcha = __('Captcha', 'Construct-admin', false);
        $typeFile = __('File', 'Construct-admin', false);
        $typeRichText = __('Rich text', 'Construct-admin', false);
        $typeCheckboxes = __('Checkboxes', 'Construct-admin', false);
        $typeDate = __('Date', 'Construct-admin', false);
        $typeTime = __('Time', 'Construct-admin', false);
        $typeFieldset= __('Fieldset', 'Construct-admin', false);

        $fieldTypes['Text'] = new FieldType('Text', '\Construct\Form\Field\Text', $typeText);
        $fieldTypes['Email'] = new FieldType('Email', '\Construct\Form\Field\Email', $typeEmail);
        $fieldTypes['Textarea'] = new FieldType('Textarea', '\Construct\Form\Field\Textarea', $typeTextarea);
        $fieldTypes['Select'] = new FieldType('Select', '\Construct\Form\Field\Select', $typeSelect, 'ipWidgetForm_InitListOptions', 'ipWidgetForm_SaveListOptions', ipView(
            'view/formFieldOptions/list.php'
        )->render());
        $fieldTypes['Checkbox'] = new FieldType('Checkbox', '\Construct\Form\Field\Checkbox', $typeCheckbox, 'ipWidgetForm_InitWysiwygOptions', 'ipWidgetForm_SaveWysiwygOptions', ipView(
            'view/formFieldOptions/wysiwyg.php',
            array('form' => self::wysiwygForm())
        )->render());
        $fieldTypes['Radio'] = new FieldType('Radio', '\Construct\Form\Field\Radio', $typeRadio, 'ipWidgetForm_InitListOptions', 'ipWidgetForm_SaveListOptions', ipView(
            'view/formFieldOptions/list.php'
        )->render());
        $fieldTypes['Captcha'] = new FieldType('Captcha', '\Construct\Form\Field\Captcha', $typeCaptcha);
        $fieldTypes['File'] = new FieldType('File', '\Construct\Form\Field\File', $typeFile);
        $fieldTypes['RichText'] = new FieldType('RichText', '\Construct\Form\Field\RichText', $typeRichText);
        $fieldTypes['Checkboxes'] = new FieldType('Checkboxes', '\Construct\Form\Field\Checkboxes', $typeCheckboxes, 'ipWidgetForm_InitListOptions', 'ipWidgetForm_SaveListOptions', ipView(
        'view/formFieldOptions/list.php'
    )->render());
        $fieldTypes['Date'] = new FieldType('Date', '\Construct\Form\Field\Date', $typeDate);
        $fieldTypes['Time'] = new FieldType('Time', '\Construct\Form\Field\Time', $typeTime);
        $fieldTypes['Fieldset'] = new FieldType('Fieldset', '\Construct\Form\Fieldset', $typeFieldset);

        return $fieldTypes;
    }

    private static function wysiwygForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);
        $field = new \Construct\Form\Field\RichText(array(
            'name' => 'text'
        ));
        $form->addField($field);
        return $form;
    }

    private static function getPluginWidgetDirs()
    {
        $answer = [];
        $plugins = \Construct\Internal\Plugins\Service::getActivePluginNames();
        foreach ($plugins as $plugin) {
            $answer = array_merge($answer, static::findPluginWidgets($plugin));
        }
        return $answer;
    }

    private static function findPluginWidgets($moduleName)
    {
        $widgetDir = ipFile('Plugin/' . $moduleName . '/' . Model::WIDGET_DIR . '/');

        if (!is_dir($widgetDir)) {
            return [];
        }
        $widgetFolders = scandir($widgetDir);
        if ($widgetFolders === false) {
            return [];
        }

        $answer = [];
        //foreach all widget folders
        foreach ($widgetFolders as $widgetFolder) {
            //each directory is a widget
            if (!is_dir($widgetDir . $widgetFolder) || $widgetFolder == '.' || $widgetFolder == '..') {
                continue;
            }
            if (isset ($answer[(string)$widgetFolder])) {
                ipLog()->warning(
                    'Content.duplicateWidget: {widget}',
                    array('plugin' => 'Content', 'widget' => $widgetFolder)
                );
            }
            $answer[] = array(
                'module' => $moduleName,
                'dir' => $widgetDir . $widgetFolder . '/',
                'widgetKey' => $widgetFolder
            );
        }
        return $answer;
    }


    public static function ipAdminNavbarButtons($buttons, $info)
    {
        $breadcrumb = ipContent()->getBreadcrumb();
        if (!empty($breadcrumb[0])) {
            $rootPage = $breadcrumb[0];
            $menu = ipContent()->getPage($rootPage->getParentId());
            $alias = $menu->getAlias();
        } else {
            $alias = '';
        }


        if (ipContent()->getCurrentPage()) {
            if (!ipAdminPermission('Content')) {
                //Do nothing
            } elseif (ipIsManagementState()) {
                $buttons[] = array(
                    'text' => __('Preview', 'Construct-admin', false),
                    'hint' => __('Hides admin tools', 'Construct-admin', false),
                    'class' => 'ipsContentPreview',
                    'faIcon' => 'fa-eye',
                    'url' => '#'
                );
            } else {
                $buttons[] = array(
                    'text' => __('Edit', 'Construct-admin', false),
                    'hint' => __('Show widgets', 'Construct-admin', false),
                    'class' => 'ipsContentEdit',
                    'faIcon' => 'fa-edit',
                    'url' => '#'
                );
            }

            if (ipAdminPermission('Pages')) {
                $buttons[] = array(
                    'text' => __('Settings', 'Construct-admin', false),
                    'hint' => __('Page settings', 'Construct-admin', false),
                    'class' => 'ipsAdminPageSettings',
                    'faIcon' => 'fa-gear',
                    'url' => ipActionUrl(array('aa' => 'Pages.index')) . '#hash&language=' . ipContent(
                        )->getCurrentLanguage()->getCode() . '&menu=' . $alias . '&page=' . ipContent()->getCurrentPage(
                        )->getId()
                );
            }

        }

        return $buttons;
    }


    public static function ipAdminNavbarCenterElements($elements, $info)
    {
        if (ipContent()->getCurrentPage() && ipAdminPermission('Content')) {
            $revision = \Construct\ServiceLocator::content()->getCurrentRevision();
            $revisions = \Construct\Internal\Revision::getPageRevisions(ipContent()->getCurrentPage()->getId());

            $managementUrls = [];
            $currentPageLink = ipContent()->getCurrentPage()->getLink();
            foreach ($revisions as $value) {
                $managementUrls[] = $currentPageLink . '?_revision=' . $value['revisionId'];
            }

            $data = array(
                'revisions' => $revisions,
                'currentRevision' => $revision,
                'managementUrls' => $managementUrls,
                'isPublished' => !\Construct\Internal\Content\Model::isRevisionModified($revision['revisionId']) && ipContent(
                    )->getCurrentPage()->isVisible(),
                'isVisible' => ipContent()->getCurrentPage()->isvisible()
            );

            $elements[] = ipView('view/publishButton.php', $data);
        }
        return $elements;
    }

    public static function ipHead($head, $info)
    {
        $relativePath = ipRequest()->getRelativePath();



        $canonicalUrl = null;
        //detect if we need to add canonical meta tag because we are on the homepage
        if (ipContent()->getCurrentPage() && ipContent()->getCurrentPage()->getId() == ipContent()->getDefaultPageId() && ipRequest()->getRelativePath() != '') {
            //if current page is the default page of current language and relative path is not empty
            $languages = ipContent()->getLanguages();
            $firstLanguage = $languages[0];

            if (ipContent()->getCurrentLanguage()->getId() == $firstLanguage->getId()) {
                //if current language is the first language, set canonical to the base URL.
                $canonicalUrl = ipConfig()->baseUrl();
            } elseif(ipRequest()->getRoutePath() != '') {
                //if current URL is not equal to the language URL, set canonical as language URL
                $canonicalUrl = ipContent()->getcurrentLanguage()->getLink();
            }



        }

        //detect if we need to add canonical tag because of missing trailing slash
        if (!$canonicalUrl) {
            //if canonicalUrl is not set yet
            if (ipGetOption('Config.trailingSlash', 1) && ipContent()->getCurrentPage()) {
                if (substr($relativePath, -1) != '/') {
                    $canonicalUrl = ipConfig()->baseUrl() . $relativePath;
                    if (substr($canonicalUrl, -1) != '/') {
                        $canonicalUrl .= '/';
                    }
                }
            } else {
                if (substr($relativePath, -1) == '/') {
                    $canonicalUrl = ipConfig()->baseUrl() . substr($relativePath, 0, -1);
                }
            }

        }

        if ($canonicalUrl) {
            $append = '    <link rel="canonical" href="' . escAttr($canonicalUrl) . '" />' . "\n";
            $head .= $append;
        }


        return $head;

    }
}
