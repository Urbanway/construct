<div class="ipAdminPages ipsAdminPages" ng-app="Pages" ng-controller="ipPages">
    <div class="_outer ipsAdminPagesContainer">
        <div class="_container _languages ipsLanguages" ng-cloak>
            <div class="_actions">
    <!--            <a href="#" class="bttn bttn-new"><i class="fa fa-plus"></i></a>-->
            </div>
            <ul>
                <li ng-repeat="language in languageList" ng-class="{active: language == activeLanguage}">
                    <a href="" ng-click="setLanguageHash(language)">{{language.abbreviation}}</a>
                </li>
                <li ng-show="{{allowActions&&ipPagesLanguagesPermission}}">
                    <a href="<?php echo $languagesUrl; ?>"><i class="fa fa-cog"></i></a>
                </li>
            </ul>
        </div>
        <div class="_container _menus ipsMenus" ng-cloak>
            <div class="_actions" ng-show="{{allowActions}}">
                <button ng-click="addMenuModal()" class="bttn bttn-new" role="button">
                    <i class="fa fa-plus"></i>
                    <?php _e('Add', 'Construct-admin'); ?>
                </button>
            </div>
            <ul class="ipsMenuList">
                <li ng-repeat="menu in menuList" menulist-post-repeat-directive data-menuname="{{menu.alias}}" data-menuid="{{menu.id}}" ng-class="{active: menu == activeMenu}">
                    <a href="" ng-show="activeLanguage.code == menu.languageCode" ng-click="setMenuHash(menu)">{{menuTitle(menu)}}</a>
                    <button ng-show="{{allowActions}}" class="bttn bttn-default _control" ng-click="updateMenuModal(menu)"><i class="fa fa-cog"></i></button>
                </li>
            </ul>
        </div>
        <div class="_container _pagesListing ipsPagesContainer" ng-cloak>
            <div ng-repeat="menu in menuList" class="tree" ng-show="menu.id == activeMenu.id">
                <div id="pages_{{menu.languageCode}}_{{menu.alias}}">
                    <div class="_actions" ng-show="{{allowActions}}">
                        <button class="bttn bttn-new ipsAddPage" ng-click="addPageModal()" role="button">
                            <i class="fa fa-plus"></i>
                            <?php _e('Add', 'Construct-admin'); ?>
                        </button>
                        <div class="bttn-group">
                            <button class="bttn bttn-default" title="<?php _e('Cut', 'Construct-admin'); ?>" ng-click="cutPage()" ng-class="{disabled: !selectedPageId}" role="button">
                                <i class="fa fa-cut"></i>
                            </button>
                            <button class="bttn bttn-default" title="<?php _e('Copy', 'Construct-admin'); ?>" ng-click="copyPage()" ng-class="{disabled: !selectedPageId}" role="button">
                                <i class="fa fa-copy"></i>
                            </button>
                            <button class="bttn bttn-default" title="<?php _e('Paste', 'Construct-admin'); ?>" ng-click="pastePage()" ng-class="{disabled: !copyPageId && !cutPageId}" role="button">
                                <i class="fa fa-paste"></i>
                            </button>
                        </div>
                    </div>
                    <div class="_tree ipsPages"></div>
                </div>
            </div>
        </div>
        <div class="_container _properties ipsProperties" ng-show="selectedPageId && allowActions"></div>
    </div>
    <?php echo ipView('Construct/Internal/Pages/view/addPageModal.php', $this->getVariables())->render(); ?>
    <?php echo ipView('Construct/Internal/Pages/view/addMenuModal.php', $this->getVariables())->render(); ?>
    <?php echo ipView('Construct/Internal/Pages/view/updateMenuModal.php', $this->getVariables())->render(); ?>
</div>
