<div class="ipsMoveModal modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php _e('Move record to another position', 'Construct-admin'); ?></h4>
            </div>
            <div class="modal-body ipsBody">
                <?php echo __('Please enter position number where selected record has to be moved to.', 'Construct-admin') ?>
                <?php
                echo $moveForm->render();
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin') ?></button>
                <button type="button" class="ipsConfirm bttn bttn-primary"><?php _e('Move', 'Construct-admin') ?></button>
            </div>
        </div>
    </div>
</div>
