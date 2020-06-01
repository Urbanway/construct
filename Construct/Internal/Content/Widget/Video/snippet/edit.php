<div class="ip">
    <div id="ipWidgetVideoPopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo __('Video widget settings', 'Construct-admin') ?></h4>
                </div>

                <div class="modal-body">
                    <?php echo $form->render() ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php echo __('Cancel', 'Construct-admin') ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php echo __('Confirm', 'Construct-admin') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
