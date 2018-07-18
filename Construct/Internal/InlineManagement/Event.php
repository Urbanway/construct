<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\InlineManagement;


class Event
{
    public static function ipBeforeController()
    {

        if (ipIsManagementState()) {
            if (ipConfig()->isDebugMode()) {
                ipAddJs('Construct/Internal/InlineManagement/assets/src/inlineManagement.js');
                ipAddJs('Construct/Internal/InlineManagement/assets/src/inlineManagementControls.js');
                ipAddJs('Construct/Internal/InlineManagement/assets/src/inlineManagementImage.js');
                ipAddJs('Construct/Internal/InlineManagement/assets/src/inlineManagementLogo.js');
                ipAddJs('Construct/Internal/InlineManagement/assets/src/inlineManagementText.js');
                ipAddJs('Construct/Internal/InlineManagement/assets/src/jquery.fontselector.js');
            } else {
                ipAddJs('Construct/Internal/InlineManagement/assets/inlineManagement.min.js');
            }

            ipAddJsVariable('ipModuleInlineManagementControls', ipView('view/management/controls.php')->render());

            ipAddJs('Construct/Internal/Content/assets/jquery.ip.uploadImage.js');

            ipAddJs('Construct/Internal/Core/assets/js/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js');
            ipAddCss('Construct/Internal/Core/assets/js/bootstrap-colorpicker/css/bootstrap-colorpicker.css');
        }


    }

    public static function ipUrlChanged($info)
    {
        $httpExpression = '/^((http|https):\/\/)/i';
        if (!preg_match($httpExpression, $info['oldUrl'])) {
            return;
        }
        if (!preg_match($httpExpression, $info['newUrl'])) {
            return;
        }
        Model::updateUrl($info['oldUrl'], $info['newUrl']);
    }
}


