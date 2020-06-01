<div class="ip">
    <div class="ipAdminWidgetControls ipsWidgetControls">
        <div class="_controls ipsControls clearfix">
            <?php if (!empty($optionsMenu)) { ?>
                <button class="bttn bttn-controls bttn-xs _settings" data-toggle="dropdown" title="<?php _e('Settings', 'Construct-admin'); ?>"><i class="fa fa-cog"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach($optionsMenu as $menuItem) { ?>
                        <li>
                            <a
                                <?php
                                if (is_array($menuItem['attributes'])) {
                                    foreach ($menuItem['attributes'] as $key => $value) {
                                        echo escAttr($key) . '="' . escAttr($value) . '" ';
                                    }
                                }
                                ?>
                                href="#"><?php echo esc($menuItem['title']) ?></a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <button class="bttn bttn-controls bttn-xs _drag ipsWidgetDrag" title="<?php _e('Drag', 'Construct-admin'); ?>">&nbsp;</button>
            <button class="bttn bttn-controls bttn-xs _delete ipsWidgetDelete" title="<?php _e('Delete', 'Construct-admin'); ?>"><i class="fa fa-trash-o"></i></button>
        </div>
    </div>
</div>
