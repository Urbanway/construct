<div class="ipsUpdateModal modal fade"  ng-cloak>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php _e('Administrator profile', 'Construct-admin'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $updateForm; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                <button type="button" class="ipsSave bttn bttn-primary"><?php _e('Save', 'Construct-admin'); ?></button>
            </div>
        </div>
    </div>
</div>
