<?php
/**
 * @package construct
 *
 */

namespace Construct;


/**
 *
 * Locate system services
 *
 */
class ServiceLocator
{
    protected static $requests = array();
    protected static $routes = array();
    protected static $dispatchers = array();
    protected static $contents = array();
    protected static $ecommerce = array();
    protected static $responses = array();
    protected static $config = null;
    protected static $log = null;
    protected static $options = null;
    protected static $storage = null;
    protected static $db;
    protected static $translator;
    protected static $permissions;
    protected static $slots;
    protected static $pageAssets = array();
    protected static $routers = array();

    protected static $serviceClasses = array(
        'db' => '\Construct\Db',
        'reflection' => '\Construct\Reflection',
        'options' => '\Construct\Options',
        'storage' => '\Construct\Storage',
        'log' => '\Construct\Internal\Log\Logger',
        'translator' => '\Construct\Internal\Translations\Translator',
        'dispatcher' => '\Construct\Dispatcher',
        'response' => '\Construct\Response\Layout',
        'content' => '\Construct\Content',
        'adminPermissions' => '\Construct\Internal\AdminPermissions',
        'slots' => '\Construct\Internal\Slots',
        'pageAssets' => '\Construct\Internal\PageAssets',
        'router' => '\Construct\Router',
        'ecommerce' => '\Construct\Ecommerce',
        'route' => '\Construct\Route',
    );

    /**
     * @return \Construct\Options
     */
    public static function options()
    {
        if (self::$options == null) {
            self::$options = static::loadService('options');
        }
        return self::$options;
    }


    /**
     * @return \Construct\Storage
     */
    public static function storage()
    {
        if (self::$storage == null) {
            self::$storage = static::loadService('storage');
        }
        return self::$storage;
    }

    /**
     * @return \Construct\Internal\PageAssets
     */
    public static function pageAssets()
    {
        return end(self::$pageAssets);
    }

    /**
     * @return \Construct\Config
     */
    public static function config()
    {
        return self::$config;
    }

    /**
     * @param \Construct\Config $config
     */
    public static function setConfig($config)
    {
        self::$config = $config;

        $serviceClasses = $config->get('services');
        if ($serviceClasses) {
            static::$serviceClasses = array_merge(static::$serviceClasses, $serviceClasses);
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public static function log()
    {
        if (self::$log == null) {
            self::$log = static::loadService('log');
        }
        return self::$log;
    }

    /**
     * @return Dispatcher
     */
    public static function dispatcher()
    {
        return end(self::$dispatchers);
    }


    /**
     * @return \Construct\Application
     */
    public static function application()
    {
        global $application;
        return $application;
    }


    /**
     * Add new request to HMVC queue
     * Used by Application. Never add requests manually.
     * @param $request
     */
    public static function addRequest($request)
    {
        self::$requests[] = $request;
        self::$dispatchers[] = static::loadService('dispatcher');
        self::$contents[] = static::loadService('content');
        self::$responses[] = static::loadService('response');
        self::$slots[] = static::loadService('slots');
        self::$pageAssets[] = static::loadService('pageAssets');
        self::$routers[] = static::loadService('router');
        self::$ecommerce[] = static::loadService('ecommerce');
        self::$routes[] = static::loadService('route');
    }

    /**
     * Remove request from HMVC. Last request should always stay intact and can't be removed as it is needed for application close action
     */
    public static function removeRequest()
    {
        if (count(self::$requests) > 1) {
            array_pop(self::$dispatchers);
            array_pop(self::$requests);
            array_pop(self::$contents);
            array_pop(self::$responses);
            array_pop(self::$slots);
            array_pop(self::$pageAssets);
            array_pop(self::$routers);
            array_pop(self::$ecommerce);
            array_pop(self::$routes);
        }
    }

    /**
     * @return \Construct\Request
     */
    public static function request()
    {
        return end(self::$requests);
    }

    /**
     * @return \Construct\Content
     */
    public static function content()
    {
        return end(self::$contents);
    }

    /**
     * @return \Construct\Ecommerce
     */
    public static function ecommerce()
    {
        return end(self::$ecommerce);
    }

    /**
     * @return \Construct\Internal\Slots
     */
    public static function slots()
    {
        return end(self::$slots);
    }


    /**
     * @return \Construct\Response\Layout
     */
    public static function response()
    {
        return end(self::$responses);
    }

    /**
     * @param Response $response
     */
    public static function setResponse(\Construct\Response $response)
    {
        array_pop(self::$responses);
        self::$responses[] = $response;
    }

    /**
     * @return \Construct\Db
     */
    public static function db()
    {
        if (static::$db === null) {
            static::$db = static::loadService('db');
        }

        return static::$db;
    }

    /**
     * @param $db
     * @return Db
     */
    public static function setDb($db)
    {
        $curDb = self::db();
        static::$db = $db;
        return $curDb;
    }

    /**
     * @return \Construct\Internal\Translations\Translator
     */
    public static function translator()
    {
        if (static::$translator === null) {
            static::$translator = static::loadService('translator');
        }

        return static::$translator;
    }

    protected static function loadService($name)
    {
        return new static::$serviceClasses[$name]();
    }

    /**
     * @return \Construct\Internal\AdminPermissions
     */
    public static function adminPermissions()
    {
        if (static::$permissions === null) {
            static::$permissions = static::loadService('adminPermissions');
        }

        return static::$permissions;
    }

    /**
     * @return \Construct\Router
     */
    public static function router()
    {
        return end(self::$routers);
    }

    /**
     * @return \Construct\Route
     */
    public static function route()
    {
        return end(self::$routes);
    }


}
