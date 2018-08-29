<div class="ipsUpdateMenuModal modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php _e('Update menu', 'Construct-admin'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="ipsDeleteConfirmation hidden">
                    <div class="alert alert-danger"><?php _e('All pages inside this menu will be deleted. Are you sure you want to delete?', 'Construct-admin'); ?></div>
                    <button class="bttn bttn-danger ipsDeleteProceed" role="button"><?php _e('Delete', 'Construct-admin'); ?><i class="fa fa-fw fa-trash-o"></i></button>
                    <button class="bttn bttn-default ipsDeleteCancel" role="button"><?php _e('Cancel', 'Construct-admin'); ?></button>
                </div>
                <div class="ipsBody"></div>
            </div>
            <div class="modal-footer ipsModalActions">
                <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                <button class="bttn bttn-danger ipsDelete" type="button" role="button"><?php _e('Delete', 'Construct-admin'); ?><i class="fa fa-fw fa-trash-o"></i></button>
                <button type="button" class="ipsSave bttn bttn-primary"><?php _e('Save', 'Construct-admin'); ?></button>
            </div>
        </div>
    </div>
</div>
