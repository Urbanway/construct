<div class="ip">
    <div class="ipAdminWidgetToolbar hidden" id="ipWidgetHeadingControls">
        <div class="bttn-toolbar" role="toolbar">
            <div class="bttn-group">
                <?php for($i=1; $i <= $maxLevel; $i++) { ?>
                    <?php $text = 'H' . $i; ?>
                <button type="button" data-level="<?php echo $i ?>" class="bttn bttn-controls ipsH"><?php _e($text, 'Construct-admin'); ?></button>
                <?php } ?>
                <button type="button" class="bttn bttn-controls ipsOptions"><?php _e('Options', 'Construct-admin'); ?></button>
            </div>
        </div>
    </div>
</div>
