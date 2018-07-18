<?php
/**
 * @package   construct
 */


/**
 * Created by PhpStorm.
 * User: maskas
 * Date: 2/19/14
 * Time: 10:17 PM
 */

namespace Construct\Internal\System;


class Submenu
{
    public static function getModuleNames()
    {
        return array('System', 'Administrators', 'Log', 'Email');
    }

    public static function getSubmenuUrls()
    {
        $moduleNames = self::getModuleNames();
        $urls = [];
        foreach ($moduleNames as $moduleName) {
            $urls[] = ipActionUrl(array('aa' => $moduleName . '.index'));
        }

        return $urls;
    }

    protected static function getControllerNames()
    {
        $controllerNames = [];
        foreach (self::getModuleNames() as $name) {
            $controllerNames[] = 'Construct\Internal\\' . $name . '\AdminController';
        }
        return $controllerNames;
    }


    public static function isControllerInSystemSubmenu()
    {
        return in_array(ipRoute()->controllerClass(), self::getControllerNames());
    }

    /**
     * @return \Construct\Menu\Item[]
     */
    public static function getSubmenuItems()
    {
        $modules = self::getModuleNames();

        $submenuItems = [];

        if (0) { // It is for translation engine to find following strings
            __('Content', 'Construct-admin');
            __('Pages', 'Construct-admin');
            __('Design', 'Construct-admin');
            __('Plugins', 'Construct-admin');
            __('Config', 'Construct-admin');
            __('Languages', 'Construct-admin');
            __('System', 'Construct-admin');
        }

        foreach ($modules as $module) {
            $menuItem = new \Construct\Menu\Item();
            $title = $module;
            if ($title == 'Email') {
                $title = 'Email log';
            }
            $menuItem->setTitle(__($title, 'Construct-admin', false)); //
            $menuItem->setUrl(ipActionUrl(array('aa' => $module . '.index')));
            if (ipRoute()->controllerClass() == 'Construct\Internal\\' . $module . '\AdminController') {
                $menuItem->markAsCurrent(true);
            }
            if (ipAdminPermission($module)) {
                $submenuItems[] = $menuItem;
            }
        }
        return $submenuItems;
    }

}
