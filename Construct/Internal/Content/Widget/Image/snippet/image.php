<div class="ip">
    <div class="ipAdminWidgetToolbar hidden" id="ipWidgetImageMenu">
        <div class="bttn-toolbar" role="toolbar">
            <div class="bttn-group">
                <button class="bttn bttn-controls ipsEdit" role="button"><i class="fa fa-edit"></i></button>
                <button class="bttn bttn-controls ipsLink" role="button"><i class="fa fa-link"></i></button>
                <button class="bttn bttn-controls ipsSettings" role="button"><i class="fa fa-gears"></i></button>
                <button class="bttn bttn-controls ipsActualSize" role="button"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
    </div>
    <div id="ipWidgetImageEditPopup" class="modal"><?php /*Fade breaks image management*/?>
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
    <div id="ipWidgetImageLinkPopup" class="modal fade">
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
    <div id="ipWidgetImageSettingsPopup" class="modal fade">
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
