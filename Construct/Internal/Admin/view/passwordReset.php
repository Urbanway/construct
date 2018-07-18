<div class="loginTitle">
    <h1><?php _e('Password reset', 'Construct-admin'); ?></h1>
</div>
<div class="ip loginContent">
    <?php echo $passwordResetForm->render(); ?>
    <p class="alternativeLink">
        <a href="<?php echo ipFileUrl('admin'); ?>"><?php _e('Login', 'Construct-admin'); ?></a>
    </p>
</div>
