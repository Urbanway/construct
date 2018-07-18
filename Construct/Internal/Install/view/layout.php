<!DOCTYPE html>
<html>
<head>
    <link href="<?php echo ipFileUrl('Construct/Internal/Install/assets/theme.css'); ?>" rel="stylesheet" type="text/css" />
    <meta name="robots" content="NOINDEX,NOFOLLOW">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('construct installation wizard', 'Install') ?></title>
</head>
<body>

<div class="ip">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <img src="<?php echo ipFileUrl('Construct/Internal/Install/assets/impresspages_logo.png'); ?>" alt="construct">
                    <?php
                        $languages = \Construct\Internal\Install\Helper::getInstallationLanguages();
                        $currentLanguage = isset($_SESSION['installationLanguage']) ? $_SESSION['installationLanguage'] : \Construct\Internal\Install\Helper::$defaultLanguageCode;
                    ?>
                    <div class="pull-right dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo mb_strtoupper($currentLanguage); ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($languages as $key => $language) { ?>
                                    <li<?php if ($key == $currentLanguage) { echo ' class="active"'; } ?>>
                                        <a href="index.php?step=<?php echo !empty($_GET['step']) ? ((int)$_GET['step']) : \Construct\Internal\Install\Helper::$firstStep; ?>&amp;lang=<?php echo htmlspecialchars($key); ?>">
                                            <?php echo htmlspecialchars($language); ?>
                                        </a>
                                    </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <h3><?php _e('construct installation wizard', 'Install'); ?>
                        <small><?php echo esc(sprintf(__('Version %s', 'Install', false), \Construct\Application::getVersion())); ?></small></h3>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?php echo \Construct\Internal\Install\Helper::generateMenu(!empty($_GET['step']) ? $_GET['step'] : \Construct\Internal\Install\Helper::$firstStep); ?>
                    </div>
                    <div class="col-md-9 ipsContent">
                        <?php echo ipBlock('main')->render(); ?>
                    </div>
                </div>
                <div class="page-footer">
                    <?php printf(__('This software is brought to you by <a target="_blank" href="%s">construct team</a>', 'Install', false), 'http://construct.uws.al/about-us/'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<iframe style="width:0;height:0;border:none;" border="0" src="http://construct.uws.al/installationscript2/?step=<?php echo !empty($_GET['step']) && $_GET['step'] >=\Construct\Internal\Install\Helper::$firstStep ? (int)$_GET['step'] : \Construct\Internal\Install\Helper::$firstStep; ?>"></iframe>

<script type="text/javascript" src="<?php echo ipFileUrl('Construct/Internal/Install/assets/js/jquery.js') ?>"></script>
<script type="text/javascript" src="<?php echo ipFileUrl('Construct/Internal/Install/assets/js/bootstrap.js') ?>"></script>
<script type="text/javascript" src="<?php echo ipFileUrl('Construct/Internal/Install/assets/js/ModuleInstall.js') ?>"></script>
<script type="text/javascript" src="<?php echo ipFileUrl('Construct/Internal/Install/assets/js/install.js') ?>"></script>
<script type="text/javascript">
    var baseUrl = '<?php echo ipConfig()->baseUrl() ?>';
</script>
</body>
