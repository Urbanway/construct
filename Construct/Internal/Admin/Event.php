<?php


namespace Construct\Internal\Admin;


class Event
{
    protected static function getAdminNavbarHtml()
    {
        $requestData = \Construct\ServiceLocator::request()->getRequest();
        $curModTitle = '';
        $curModUrl = '';
        $curModIcon = '';

        if (!empty($requestData['aa'])) {
            $parts = explode('.', $requestData['aa']);
            $curModule = $parts[0];
        } else {
            $curModule = "Content";
        }

        if (isset($curModule) && $curModule) {
            $title = $curModule;
            $pluginConfig = \Construct\Internal\Plugins\Service::getPluginConfig($curModule);
            
            $curModTitle = __($title, 'Construct-admin', false);
            $curModUrl = ipActionUrl(array('aa' => $curModule . '.index'));
            $curModIcon = Model::getAdminMenuItemIcon($curModule);
            
            //try to translate and get icon in config.json
            $curModTitle = isset($pluginConfig['title']) ? __($pluginConfig['title'], $curModule, false) : $curModTitle;
            $curModIcon = isset($pluginConfig['icon']) ? $pluginConfig['icon'] : $curModIcon;
        }

        $menubarButtons = array(
            array(
                'text' => '',
                'hint' => __('Logout', 'Construct-admin', false),
                'url' => ipActionUrl(array('sa' => 'Admin.logout')),
                'class' => 'ipsAdminLogout',
                'faIcon' => 'fa-power-off'
            )
        );

        $menubarButtons = ipFilter('ipAdminNavbarButtons', $menubarButtons);

        $menubarCenterElements = ipFilter('ipAdminNavbarCenterElements', []);

        $data = array(
            'menuItems' => Model::instance()->getAdminMenuItems($curModule),
            'curModTitle' => $curModTitle,
            'curModUrl' => $curModUrl,
            'curModIcon' => $curModIcon,
            'menubarButtons' => array_reverse($menubarButtons),
            'menubarCenterElements' => $menubarCenterElements
        );


        $html = ipView('view/menubar.php', $data)->render();
        return $html;
    }

    public static function ipInitFinished ()
    {
        $request = \Construct\ServiceLocator::request();
        $safeMode = $request->getQuery('safeMode');
        if ($safeMode === null) {
            $safeMode = $request->getQuery('safemode');
        }

        if ($safeMode !== null && \Construct\Internal\Admin\Backend::userId()) {
            Model::setSafeMode($safeMode);
        }
    }

    public static function ipBeforeController()
    {


        //show admin submenu if needed
        if (ipRoute()->isAdmin()) {
            ipAddJs('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.js');
            ipAddCss('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.css');

            $submenu = Submenu::getSubmenuItems();
            $submenu = ipFilter('ipAdminSubmenu', $submenu);
            if ($submenu) {
                ipResponse()->setLayoutVariable('submenu', $submenu);
            }
        }

        // Show admin toolbar if admin is logged in:
        if (ipAdminId() && !ipRequest()->getRequest('pa') || ipRequest()->getRequest('aa') && ipAdminId()) {
            if (!ipRequest()->getQuery('ipDesignPreview') && !ipRequest()->getQuery('disableAdminNavbar')) {
                ipAddJs('Construct/Internal/Admin/assets/admin.js');
                ipAddJsVariable('ipAdminNavbar', static::getAdminNavbarHtml());
            }
        }

        // Show popup with autogenerated user information if needed
        $adminIsAutogenerated = ipStorage()->get('Construct', 'adminIsAutogenerated');
        if ($adminIsAutogenerated) {
            $adminId = \Construct\Internal\Admin\Backend::userId();
            $admin = \Construct\Internal\Administrators\Model::getById($adminId);
            ipAddJs('Construct/Internal/Admin/assets/adminIsAutogenerated.js');
            $data = array(
                'adminUsername' => $admin['username'],
                'adminPassword' => ipStorage()->get('Construct', 'adminIsAutogenerated'),
                'adminEmail' => $admin['email']
            );
            ipAddJsVariable('ipAdminIsAutogenerated', ipView('view/adminIsAutoGenerated.php', $data)->render());
        }


        if (ipContent()->getCurrentPage()) {
            // initialize management
            if (ipIsManagementState()) {
                if (!ipRequest()->getQuery('ipDesignPreview') && !ipRequest()->getQuery('disableManagement')) {
                    \Construct\Internal\Content\Helper::initManagement();
                }
            }

            //show page content
            $response = ipResponse();
            $response->setDescription(\Construct\ServiceLocator::content()->getDescription());
            $response->setKeywords(ipContent()->getKeywords());
            $response->setTitle(ipContent()->getTitle());

        }


    }

    public static function ipAdminLoginFailed($data)
    {
        $securityModel = SecurityModel::instance();
        $securityModel->registerFailedLogin($data['username'], $data['ip']);
    }

    public static function ipCronExecute($data)
    {
        if ($data['firstTimeThisDay']) {
            $securityModel = SecurityModel::instance();
            $securityModel->cleanup();
        }
    }

}
