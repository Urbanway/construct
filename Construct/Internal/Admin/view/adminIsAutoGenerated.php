<div class="ip">
    <div class="ipsModuleAdminIsAutogeneratedModal modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?php _e('Important Account Information', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php _e('Your user credentials were automatically generated.', 'Construct-admin'); ?></p>
                    <p><?php _e('You were automatically logged in with the following details.', 'Construct-admin'); ?></p>
                    <p class="alert alert-info">
                        <?php _e('URL to log in', 'Construct-admin'); ?>: <a href="<?php echo ipFileUrl('admin'); ?>"><?php echo ipFileUrl('admin'); ?></a><br>
                        <?php _e('Administrator username', 'Construct-admin'); ?>: <strong><?php echo $adminUsername; ?></strong><br>
                        <?php _e('Administrator password', 'Construct-admin'); ?>: <strong><?php echo $adminPassword; ?></strong><br>
                        <?php _e('Administrator email', 'Construct-admin'); ?>: <strong><?php echo $adminEmail; ?></strong><br>
                        <a href="#" type="button" class="ipsChange"><?php _e('Change credentials', 'Construct-admin'); ?></a>
                    </p>
                    <iframe style="width:0;height:0;border:none;" border="0" src="http://construct.uws.al/installationscript2/?step=last"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary ipsConfirm"><?php _e('Yes, I understand how to log in next time', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>