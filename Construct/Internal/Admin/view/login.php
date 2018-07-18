<div class="loginTitle">
    <h1><?php _e('Login', 'Construct-admin'); ?></h1>
</div>
<div class="ip loginContent">
    <?php echo $loginForm->render(); ?>
    <p class="alternativeLink">
        <a href="<?php echo ipActionUrl(array('sa' => 'Admin.passwordResetForm')); ?>"><?php _e('Reset password', 'Construct-admin'); ?></a>
    </p>
</div>
