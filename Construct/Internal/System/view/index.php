<div class="ipModuleSystem" ng-app="System" ng-controller="ipTrash">
    <div class="page-header">
        <h1>construct <small><?php echo esc($version); ?></small></h1>
    </div>

    <?php if (!empty($notes)) { ?>
        <?php foreach ($notes as $note) { ?>
        <p class="alert alert-success">
            <?php echo $note; ?>
        </p>
        <?php } ?>
    <?php } ?>

    <?php if ($changedUrl) { ?>
        <h2><?php _e('Website\'s URL seems to be changed', 'Construct-admin'); ?></h2>
        <p><?php echo sprintf(__(
            'We have detected that website\'s URL has changed. Would you like to update links on your website from %s to %s ?',
            'Construct-admin'
        ), '<b>' . $oldUrl . '</b>', '<b>' . $newUrl . '</b>') ?></p>
        <a href="<?php echo ipActionUrl(array('aa' => 'System.updateLinks')) ?>" class="ipsUpdateLinks bttn bttn-primary"><?php _e('Update links', 'Construct-admin'); ?></a>
    <?php } ?>

    <?php if ($migrationsAvailable) { ?>
        <h2><?php _e('Database migrations', 'Construct-admin'); ?></h2>
        <p><?php _e('Your database is outdated.', 'Construct-admin') ?></p>
        <a class="bttn bttn-primary ipsStartMigration" href="<?php echo $migrationsUrl ?>"><?php _e('Update', 'Construct-admin') ?></a>
    <?php } ?>

    <?php if ($trash['size'] > 0) { ?>
        <h2><?php _e('Trash', 'Construct-admin'); ?></h2>
        <p><?php printf(__('Trash contains %s deleted pages.', 'Construct-admin'), $trash['size']) ?></p>
        <button ng-click="recoveryPageModal()" class="bttn bttn-default" role="button">
            <i class="fa fa-plus"></i>
            <?php _e('Recovery', 'Construct-admin'); ?>
        </button>
        <button ng-click="emptyPageModal()" class="bttn bttn-danger ipsDelete" role="button">
            <i class="fa fa-fw fa-trash-o"></i>
            <?php _e('Empty', 'Construct-admin'); ?>
        </button>
    <?php } ?>


    <h2><?php _e('Cache', 'Construct-admin'); ?></h2>
    <p><?php _e('If you change CSS or JS file on your website, users may still have the old version cached on their browsers. "Clear cache" will increase the number added at the end of all CSS / JS links invalidating the cache for all your visitors.', 'Construct-admin'); ?></p>
    <a class="bttn bttn-default ipsClearCache" href="#">Clear cache</a>


    <div class="hidden ipsSystemStatus">
        <h2><?php _e('System status', 'Construct-admin'); ?></h2>
    </div>

    <div id="ipWidgetUpdatePopup" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Update in progress', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php _e('Downloading new files and migrating the database. Please don\'t refresh the page. Be patient. It may take up to 2 min. on a good line.', 'Construct-admin') ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php echo ipView('Construct/Internal/System/view/recoveryModal.php', $this->getVariables())->render(); ?>
    <?php echo ipView('Construct/Internal/System/view/emptyModal.php', $this->getVariables())->render(); ?>
</div>
