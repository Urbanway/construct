<div class="ip">
    <div class="ipModuleInlineManagementLogoModal ipsModuleInlineManagementLogoModal modal"><?php /* Fade breaks image management */ ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php _e('Edit logo', 'Construct-admin'); ?></h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#ipsTypeSelectText" data-toggle="tab" data-logotype="text"><?php _e('Text','Construct-admin'); ?></a></li>
                        <li><a href="#ipsTypeSelectImage" data-toggle="tab" data-logotype="image"><?php _e('Image logo', 'Construct-admin'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="ipsTypeSelectText">
                            <div class="input-group input-group-lg">
                                <input class="form-control ipsLogoText" type="text" value="" />
                                <span class="_colorPicker input-group-addon ipsColorPicker">
                                    <i></i>
                                    <input class="_colorPickerValue ipsLogoColor" type="text" value="" />
                                </span>
                                <div class="ipsFontSelect input-group-bttn">
                                    <button data-toggle="dropdown" class="bttn bttn-default select-toggle
menubar">
                                        <span class="ipsFontName"><?php _e('Default', 'Construct-admin'); ?></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="#" class="ipsDefaultFont"><?php _e('Default', 'Construct-admin'); ?>,</a></li>
                                        <?php if (isset($availableFonts) && is_array($availableFonts)) foreach($availableFonts as $font) { ?>
                                            <li><a href="#"><?php echo esc($font); ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="ipsTypeSelectImage">
                            <div class="ipsImage"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bttn bttn-default" data-dismiss="modal"><?php _e('Cancel', 'Construct-admin'); ?></button>
                    <button type="button" class="bttn bttn-primary ipsConfirm"><?php _e('Confirm', 'Construct-admin'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>




