<div class="ip ipsAdminNavbarContainer">
    <div class="ipAdminNavbar ipsAdminNavbar menubar menubar-default menubar-fixed-top menubar-inverse" role="navigationigation">
        <button type="button" class="_toggle ipsAdminMenu menubar-toggle">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="_menu  invisible ipsAdminMenuBlock">
             
            <div class="_menuContainer ipsAdminMenuBlockContainer">
                <navigation>
                    <?php
                        $data = array(
                            'items' => $menuItems,
                            'depth' => 1,
                            'attributesStr' => 'class="navigation navigation-stacked"',
                            'active' => 'active',
                            'selected' => 'selected',
                            'disabled' => 'disabled',
                            'crumb' => 'crumb',
                            'parent' => 'parent',
                            'children' => 'children'
                        );
                        $view = ipView('menu.php', $data);
                        echo $view->render();
                    ?>
                    <ul class="navigation navigation-stacked">
                        <li>
                            <a href="<?php echo ipActionUrl(array('sa' => 'Admin.logout')); ?>">
                                <i class="fa fa-fw fa-power-off"></i>
                                <?php _e('Logout', 'Construct-admin'); ?>
                            </a>
                        </li>
                    </ul>
                </navigation>
            </div>
        </div>

        <?php if ($curModTitle) { ?>
            <ul class="_active navigation menubar-navigation">
                <li class="ipsItemCurrent">
                    <a href="<?php echo esc($curModUrl); ?>">
                        <?php if ($curModIcon) { ?>
                            <i class="fa fa-fw <?php echo esc($curModIcon); ?>"></i>
                        <?php } ?>
                        <?php echo esc($curModTitle); ?>
                    </a>
                </li>
            </ul>
        <?php } ?>

        <ul class="_right navigation menubar-navigation menubar-right">
            <?php foreach ($menubarButtons as $button) { ?>
            <li>
                <a
                    href="<?php echo empty($button['url']) ? '#' : escAttr($button['url']); ?>"
                    class="<?php echo empty($button['class']) ? '' : escAttr($button['class']); ?>"
                    title="<?php echo empty($button['hint']) ? '' : escAttr($button['hint']); ?>"
                >
                    <i class="fa <?php echo empty($button['faIcon']) ? '' : escAttr($button['faIcon']); ?>"></i>
                    <?php echo empty($button['text']) ? '' : $button['text']; ?>
                </a>
            </li>
            <?php } ?>
        </ul>

        <div class="menubar-center">
            <div class="menubar-center-container">
                <?php foreach ($menubarCenterElements as $el) { echo $el; } ?>
            </div>
        </div>
    </div>
</div>
