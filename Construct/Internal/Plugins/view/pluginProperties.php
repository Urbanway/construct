<div class="page-header">
    <?php if (!empty($icon)) { ?>
        <img class="_icon" src="<?php echo escAttr($icon) ?>" alt="Plugin icon" />
    <?php } ?>

    <h1><?php echo esc($plugin['title']) ?></h1>
</div>
<div class="_actions clearfix">
    <?php if ($plugin['active']) { ?>
        <button class="ipsDeactivate bttn bttn-default" type="button" role="button"><?php _e('Deactivate', 'Construct-admin'); ?></button>
    <?php } else { ?>
        <button class="ipsDelete bttn bttn-danger pull-right" type="button" role="button"><?php _e('Delete', 'Construct-admin'); ?><i class="fa fa-fw fa-trash-o"></i></button>
        <button class="ipsActivate bttn bttn-new" type="button" role="button"><?php _e('Activate', 'Construct-admin'); ?></button>
    <?php } ?>
</div>
<p><?php echo esc($plugin['description']); ?></p>
<ul class="_details">
    <li><strong><?php _e('Author', 'Construct-admin'); ?>:</strong> <?php echo esc($plugin['author']); ?></li>
    <li><strong><?php _e('Name', 'Construct-admin'); ?>:</strong> <?php echo esc($plugin['name']); ?></li>
    <li><strong><?php _e('Version', 'Construct-admin'); ?>:</strong> <?php echo esc($plugin['version']); ?></li>
</ul>
<?php if (!empty($form)) { ?>
    <?php echo $form->render(); ?>
<?php } ?>
