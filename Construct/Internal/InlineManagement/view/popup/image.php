<div class="ip ipsModuleInlineManagementImageModal">
    <div class="ipModuleInlineManagementImageModal">
        <div class="ipsImage"></div>
        <div class="_controls form-inline ipsControls clearfix">
            <div class="form-group pull-left">
                <label><?php _e('Apply to', 'Construct-admin'); ?></label>
                <select class="form-control ipsType">
                    <?php foreach ($types as $key => $type){ ?>
                        <option value="<?php echo esc($type['value']); ?>" <?php echo !empty($type['selected']) ? 'selected="selected"' : ''; ?>><?php echo esc($type['title']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php if (isset($showRemoveLink) && $showRemoveLink) { ?>
                <div class="form-group pull-right">
                    <button class="btn btn-danger ipsRemove"><?php _e('Remove this image', 'Construct-admin'); ?><i class="fa fa-fw fa-trash-o"></i></button>
                    <div class="hidden ipsRemoveConfirm"><?php _e('There is no option to undo this action. Parent page image or the default one will be applied to this page. Do you want to proceed?', 'Construct-admin'); ?></div>
                </div>
            <?php } ?>
            <div class="form-group pull-left">
                <button class="btn btn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                <button class="btn btn-default ipsCancel"><?php _e('Cancel', 'Construct-admin'); ?></button>
            </div>
        </div>
    </div>
</div>
