<div class="ip">
    <div class="ipAdminWidgetToolbar ipsWidgetGalleryMenu hidden" id="ipWidgetGalleryMenu">
        <div class="bttn-toolbar" role="toolbar">
            <div class="bttn-group">
                <button class="bttn bttn-controls ipsEdit" role="button"><i class="fa fa-edit"></i></button>
                <button class="bttn bttn-controls ipsLink" role="button"><i class="fa fa-link"></i></button>
                <button class="bttn bttn-controls ipsSettings" role="button"><i class="fa fa-gears"></i></button>
                <button class="bttn bttn-controls ipsDelete" role="button"><i class="fa fa-trash-o"></i></button>
            </div>
        </div>
    </div>
    <div id="ipWidgetGalleryEditPopup" class="modal"><?php /*Fade breaks image management*/?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Edit image', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="ipsEditScreen"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="ipWidgetGalleryManagePopup" class="modal"><?php /*Fade breaks image management*/?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Manage gallery', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="ipsContainer _container clearfix">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-primary ipsDone" data-dismiss="modal"><?php _e('Done', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
        <div class="ipsItemTemplate ipsItem _item">
            <img class="_image ipsImage" src="" alt="" />
        </div>
    </div>
    <div id="ipWidgetGalleryLinkPopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Link', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo $linkForm; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="ipWidgetGallerySettingsPopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Settings', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo $settingsForm; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
