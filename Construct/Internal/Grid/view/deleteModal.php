<div class="ipsDeleteModal modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <?php echo esc($deleteWarning) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin') ?></button>
                <button type="button" class="ipsConfirm bttn bttn-primary"><?php _e('Delete', 'Construct-admin') ?></button>
            </div>
        </div>
    </div>
</div>
