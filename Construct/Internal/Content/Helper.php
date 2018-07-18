<?php
/**
 * @package construct
 *
 *
 */

namespace Construct\Internal\Content;


/**
 *
 * Event dispatcher class
 *
 */
class Helper
{

    /**
     * @param $data
     * @return \Construct\Language
     */
    public static function createLanguage($data)
    {
        $language = new \Construct\Language($data['id'], $data['code'], $data['url'], $data['title'], $data['abbreviation'], $data['isVisible'], $data['textDirection']);
        return $language;
    }

    public static function initManagement()
    {
        $widgets = Service::getAvailableWidgets();
        $snippets = [];
        foreach ($widgets as $widget) {
            $snippetHtml = $widget->adminHtmlSnippet();
            if ($snippetHtml != '') {
                $snippets[] = $snippetHtml;
            }
        }
        ipAddJsVariable('ipWidgetSnippets', $snippets);


        ipAddJsVariable('ipContentInit', Model::initManagementData());



        ipAddJs('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.js');
        ipAddCss('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.css');

        if (ipConfig()->isDebugMode()) {
            ipAddJs('Construct/Internal/Content/assets/management/ipContentManagementInit.js');
            ipAddJs('Construct/Internal/Content/assets/management/content.js');
            ipAddJs('Construct/Internal/Content/assets/management/jquery.ip.contentManagement.js');
            ipAddJs('Construct/Internal/Content/assets/management/jquery.ip.widgetbutton.js');
            ipAddJs('Construct/Internal/Content/assets/management/jquery.ip.layoutModal.js');
            ipAddJs('Construct/Internal/Content/assets/management/jquery.ip.block.js');
            ipAddJs('Construct/Internal/Content/assets/management/jquery.ip.widget.js');
            ipAddJs('Construct/Internal/Content/assets/management/exampleContent.js');
            ipAddJs('Construct/Internal/Content/assets/management/drag.js');

            ipAddJs('Construct/Internal/Content/Widget/Columns/assets/Columns.js');
            ipAddJs('Construct/Internal/Content/Widget/File/assets/File.js');
            ipAddJs('Construct/Internal/Content/Widget/File/assets/jquery.ipWidgetFile.js');
            ipAddJs('Construct/Internal/Content/Widget/File/assets/jquery.ipWidgetFileContainer.js');
            ipAddJs('Construct/Internal/Content/Widget/Form/assets/Form.js');
            ipAddJs('Construct/Internal/Content/Widget/Form/assets/FormContainer.js');
            ipAddJs('Construct/Internal/Content/Widget/Form/assets/FormField.js');
            ipAddJs('Construct/Internal/Content/Widget/Form/assets/FormOptions.js');
            ipAddJs('Construct/Internal/Content/Widget/Html/assets/Html.js');
            ipAddJs('Construct/Internal/Content/Widget/Video/assets/Video.js');
            ipAddJs('Construct/Internal/Content/Widget/Image/assets/Image.js');
            ipAddJs('Construct/Internal/Content/Widget/Gallery/assets/Gallery.js');
            ipAddJs('Construct/Internal/Content/Widget/Text/assets/Text.js');
            ipAddJs('Construct/Internal/Content/Widget/Heading/assets/Heading.js');
            ipAddJs('Construct/Internal/Content/Widget/Heading/assets/HeadingModal.js');
            ipAddJs('Construct/Internal/Content/Widget/Map/assets/Map.js');

        } else {
            ipAddJs('Construct/Internal/Content/assets/management.min.js');
        }



        ipAddJs('Construct/Internal/Core/assets/js/jquery-tools/jquery.tools.ui.scrollable.js');


        ipAddJs('Construct/Internal/Content/assets/jquery.ip.uploadImage.js');

        ipAddJsVariable('isMobile', \Construct\Internal\Browser::isMobile());


        ipAddJsVariable(
            'ipWidgetLayoutModalTemplate',
            ipView('view/widgetLayoutModal.php')->render()
        );

    }
}
