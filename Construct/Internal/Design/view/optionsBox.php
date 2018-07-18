<div class="ip">
    <div class="ipModuleDesignConfig">
        <div class="modal-dialog ipsDialog">
            <div class="modal-content">
                <div class="modal-header ipsDragHandler">
                    <h4 class="modal-title"><i class="fa fa-cogs"></i> <?php _e('Theme options', 'Construct-admin'); ?></h4>
                </div>
                <div class="_body ipsBody">
                    <?php echo $form->render(); ?>
                </div>
                <div class="modal-footer ipsActions">
                    <a href="#" class="btn btn-success btn-sm ipsSave"><?php _e('Save', 'Construct-admin'); ?></a>
                    <a href="#" class="btn btn-default btn-sm ipsReloadButton hidden "><?php _e('Reload preview', 'Construct-admin'); ?></a>
                    <a href="#" class="btn btn-default btn-sm ipsDefault"><?php _e('Preview defaults', 'Construct-admin'); ?></a>
                    <a href="#" class="btn btn-default btn-sm ipsCancel"><?php _e('Cancel', 'Construct-admin'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
