<div class="ip">
    <div id="ipWidgetFilePopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Files', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="ipWidget_ipFile_container"></div>
                    <div class="hidden">
                        <div class="ipsFileTemplate form-group">
                            <div class="input-group">
                                <div class="input-group-bttn">
                                    <button class="bttn bttn-default ipsFileMove" type="button" title="<?php _e('Drag', 'Construct-admin'); ?>"><i class="fa fa-arrows"></i></button>
                                </div>
                                <input type="text" class="form-control ipsFileTitle" name="title" value="" />
                                <div class="input-group-bttn">
                                    <a href="#" class="bttn bttn-default ipsFileLink" target="_blank" title="<?php _e('Preview', 'Construct-admin'); ?>"><i class="fa fa-external-link"></i></a>
                                    <button class="bttn bttn-danger ipsFileRemove" type="button" title="<?php _e('Delete', 'Construct-admin'); ?>"><i class="fa fa-trash-o"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="ipsUpload ipAdminButton bttn bttn-new"><?php _e('Add new', 'Construct-admin'); ?></a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default ipsCancel" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
