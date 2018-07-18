<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Repository;


class Event
{
    public static function ipBeforeController()
    {

        if (ipIsManagementState() || ipRoute()->isAdmin() || ipRequest()->getQuery('ipDesignPreview')) {
            ipAddJs('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.js');
            ipAddJs('Construct/Internal/Repository/assets/ipRepository.js');
            ipAddJs('Construct/Internal/Repository/assets/ipRepositoryUploader.js');
            ipAddJs('Construct/Internal/Repository/assets/ipRepositoryAll.js');
            ipAddJs('Construct/Internal/Repository/assets/ipRepositoryBuy.js');
            ipAddJs('Construct/Internal/System/assets/market.js');
            ipAddJs('Construct/Internal/Core/assets/js/easyXDM/easyXDM.min.js');

            $marketUrl = ipConfig()->get('imageMarketUrl', ipConfig()->protocol() . '://market.impresspages.org/images-v1/');

            $popupData = array(
                'marketUrl' => $marketUrl,
                'allowUpload' => ipAdminPermission('Repository upload'),
                'allowRepository' => ipAdminPermission('Repository')
            );

            ipAddJsVariable('ipRepositoryHtml', ipView('view/popup.php', $popupData)->render());
            ipAddJsVariable(
                'ipRepositoryTranslate_confirm_delete',
                __('Are you sure you want to delete selected files?', 'Construct-admin')
            );
            ipAddJsVariable(
                'ipRepositoryTranslate_delete_warning',
                __('Some of the selected files are still used somewhere on your website. Do you still want to remove them? ', 'Construct-admin')
            );
        }


    }

}


