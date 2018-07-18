<?php
/**
 * @package   construct
 */

namespace Construct;

/**
 * Base class for construct application
 */


class Application
{
    const ASSETS_DIR = 'assets';
    protected $configSetting = null;

    /**
     * @param string|array $configSetting string to the configuration directory or configuration data array
     */
    public function __construct($configSetting = null)
    {
        $this->configSetting = $configSetting;
    }

    /**
     * Get framework version
     * @return string
     */
    public static function getVersion()
    {
        return '5.0.3'; //CHANGE_ON_VERSION_UPDATE
    }


    /**
     * @ignore
     */
    public function init()
    {
        //this function has been left here just to avoid any issues with old index.php fies running it.
    }

    /**
     * @ignore
     * @param array $options
     */
    public function prepareEnvironment($options = array())
    {
        if (empty($options['skipErrorHandler'])) {
            set_error_handler(array('Construct\Internal\ErrorHandler', 'ipErrorHandler'));
        }

        if (empty($options['skipError'])) {
            if (ipConfig()->showErrors()) {
                error_reporting(E_ALL | E_STRICT);
                ini_set('display_errors', '1');
            } else {
                ini_set('display_errors', '0');
            }
        }

        if (empty($options['skipSession'])) {
            if (session_id() == '' && !headers_sent()) { //if session hasn't been started yet
                session_name(ipConfig()->get('sessionName', 'impresspages'));
                if (!ipConfig()->get('disableHttpOnlySetting')) {
                    ini_set('session.cookie_httponly', 1);
                }

                session_start();
            }

            $expireIn = ipConfig()->get('sessionMaxIdle', 1800);
            if (isset($_SESSION['module']['admin']['last_activity']) && (time() - $_SESSION['module']['admin']['last_activity'] > $expireIn)) {
                session_unset();
                session_destroy();
            }
            $_SESSION['module']['admin']['last_activity'] = time();
        }

        if (empty($options['skipEncoding'])) {
            mb_internal_encoding(ipConfig()->get('charset'));
        }

        if (empty($options['skipTimezone']) && ipConfig()->get('timezone')) {
            date_default_timezone_set(ipConfig()->get('timezone')); //PHP 5 requires timezone to be set.
        }
    }


    protected function initTranslations($languageCode)
    {
        $translator = \Construct\ServiceLocator::translator();
        $translator->setLocale($languageCode);

        if (ipConfig()->adminLocale()) {
            $translator->setAdminLocale(ipConfig()->adminLocale());
        }

        $theme = ipConfig()->theme();
        $originalDir = ipFile('file/translations/original/');
        $overrideDir = ipFile('file/translations/override/');
        $themeDir = ipFile("Theme/$theme/translations/");
        $ipDir = ipFile('Construct/Internal/Translations/translations/');

        $translator->addTranslationFilePattern('json', $originalDir, "$theme-%s.json", $theme);
        $translator->addTranslationFilePattern('json', $themeDir, "$theme-%s.json", $theme);
        $translator->addTranslationFilePattern('json', $overrideDir, "$theme-%s.json", $theme);

        $translator->addTranslationFilePattern('json', $originalDir, 'Construct-admin-%s.json', 'Construct-admin');
        $translator->addTranslationFilePattern('json', $ipDir, 'Construct-admin-%s.json', 'Construct-admin');
        $translator->addTranslationFilePattern('json', $overrideDir, 'Construct-admin-%s.json', 'Construct-admin');

        $translator->addTranslationFilePattern('json', $originalDir, 'Construct-%s.json', 'Construct');
        $translator->addTranslationFilePattern('json', $ipDir, 'Construct-%s.json', 'Construct');
        $translator->addTranslationFilePattern('json', $overrideDir, 'Construct-%s.json', 'Construct');
    }

    /**
     * @ignore
     * @param Request $request
     * @param array $options
     * @param bool $subrequest
     * @return Response\Json|Response\PageNotFound|Response\Redirect
     * @throws Exception
     * @ignore
     */

    public function _handleOnlyRequest(\Construct\Request $request, $options = array(), $subrequest = true)
    {
        if (empty($options['skipInitEvents'])) {
            \Construct\ServiceLocator::dispatcher()->_bindApplicationEvents();
        }


        $result = ipJob('ipRouteLanguage', array('request' => $request, 'relativeUri' => $request->getRelativePath()));
        if ($result) {
            $requestLanguage = $result['language'];
            $routeLanguage = $requestLanguage->getCode();
            ipRequest()->_setRoutePath($result['relativeUri']);
        } else {
            $routeLanguage = null;
            $requestLanguage = ipJob('ipRequestLanguage', array('request' => $request));
            ipRequest()->_setRoutePath($request->getRelativePath());
        }

        if ($requestLanguage) {
            $this->setLocale($requestLanguage);
            ipContent()->_setCurrentLanguage($requestLanguage);
            $_SESSION['ipLastLanguageId'] = $requestLanguage->getId();
        }

        if (empty($options['skipTranslationsInit'])) {
            if (!empty($options['translationsLanguageCode'])) {
                $languageCode = $options['translationsLanguageCode'];
            } else {
                $languageCode = $requestLanguage->getCode();
            }
            $this->initTranslations($languageCode);
        }

        if (empty($options['skipModuleInit'])) {
            $this->modulesInit();
        }
        ipEvent('ipInitFinished');


        $routeAction = ipJob(
            'ipRouteAction',
            array('request' => $request, 'relativeUri' => ipRequest()->getRoutePath(), 'routeLanguage' => $routeLanguage)
        );

        if (!empty($routeAction)) {
            if (!empty($routeAction['page'])) {
                ipContent()->_setCurrentPage($routeAction['page']);
            }
            if (!empty($routeAction['environment'])) {
                ipRoute()->setEnvironment($routeAction['environment']);
            } else {
                if ((!empty($routeAction['controller'])) && $routeAction['controller'] == 'AdminController') {
                    ipRoute()->setEnvironment(\Construct\Route::ENVIRONMENT_ADMIN);
                } else {
                    ipRoute()->setEnvironment(\Construct\Route::ENVIRONMENT_PUBLIC);
                }
            }
            if (!empty($routeAction['controller'])) {
                ipRoute()->setController($routeAction['controller']);
            }
            if (!empty($routeAction['plugin'])) {
                ipRoute()->setPlugin($routeAction['plugin']);
            }
            if (!empty($routeAction['name'])) {
                ipRoute()->setName($routeAction['name']);
            }
            if (!empty($routeAction['action'])) {
                ipRoute()->setAction($routeAction['action']);
            }
        }


        //check for CSRF attack
        if (ipRoute()->environment() != \Construct\Route::ENVIRONMENT_PUBLIC && empty($options['skipCsrfCheck']) && $request->isPost() && ($request->getPost(
                    'securityToken'
                ) != $this->getSecurityToken(
                )) && (empty($routeAction['controller']) || $routeAction['controller'] != 'PublicController')
        ) {

            ipLog()->error('Core.possibleCsrfAttack', array('post' => ipRequest()->getPost()));
            $data = array(
                'status' => 'error'
            );
            if (ipConfig()->isDevelopmentEnvironment()) {
                $data['errors'] = array(
                    'securityToken' => __('Possible CSRF attack. Please pass correct securityToken.', 'Construct-admin')
                );
            }
            // TODO JSONRPC
            return new \Construct\Response\Json($data);
        }

        if (empty($routeAction)) {
            $routeAction = array(
                'plugin' => 'Core',
                'controller' => 'PublicController',
                'action' => 'pageNotFound'
            );
        }

        $eventInfo = $routeAction;

        if (!empty($routeAction['plugin'])) {

            $plugin = $routeAction['plugin'];
            $controller = $routeAction['controller'];

            if (in_array($plugin, \Construct\Internal\Plugins\Model::getModules())) {
                $controllerClass = 'Construct\\Internal\\' . $plugin . '\\' . $controller;
            } else {
                if (!in_array($plugin, \Construct\Internal\Plugins\Service::getActivePluginNames())) {
                    throw new \Construct\Exception("Plugin '" . esc($plugin) . "' doesn't exist or isn't activated.");
                }
                $controllerClass = 'Plugin\\' . $plugin . '\\' . $controller;
            }

            if (!class_exists($controllerClass)) {
                throw new \Construct\Exception('Requested controller doesn\'t exist. ' . esc($controllerClass));
            }

            // check if user is logged in
            if ($controller == 'AdminController' && !\Construct\Internal\Admin\Backend::userId()) {

                if (ipConfig()->get('rewritesDisabled')) {
                    return new \Construct\Response\Redirect(ipConfig()->baseUrl() . 'index.php/admin');
                } else {
                    return new \Construct\Response\Redirect(ipConfig()->baseUrl() . 'admin');
                }
            }

            if ($controller == 'AdminController') {
                if (!ipAdminPermission($plugin)) {
                    throw new \Construct\Exception('User has no permission to access ' . esc($plugin) . '');
                }
            }

            $eventInfo['controllerClass'] = $controllerClass;
            $eventInfo['controllerType'] = $controller;
        }

        if (empty($eventInfo['page'])) {
            $eventInfo['page'] = null;
        }

        // change layout if safe mode
        if (\Construct\Internal\Admin\Service::isSafeMode()) {
            ipSetLayout(ipFile('Construct/Internal/Admin/view/safeModeLayout.php'));
        } else {
            if ($eventInfo['page']) {
                ipSetLayout($eventInfo['page']->getLayout());
            }
        }

        if (ipConfig()->database()) {
            ipEvent('ipBeforeController', $eventInfo);
        }

        $controllerAnswer = ipJob('ipExecuteController', $eventInfo);

        return $controllerAnswer;
    }

    /**
     * Handle HMVC request
     * @param Request $request
     * @param array $options
     * @param bool $subrequest
     * @return Response\Json|Response\Layout|Response\PageNotFound|Response\Redirect|string
     * @throws Exception
     */
    public function handleRequest(Request $request, $options = array(), $subrequest = true)
    {

        \Construct\ServiceLocator::addRequest($request);

        $rawResponse = $this->_handleOnlyRequest($request, $options, $subrequest);

        if (!empty($options['returnRawResponse'])) {
            if ($subrequest) {
                \Construct\ServiceLocator::removeRequest();
            }
            return $rawResponse;
        }

        if (empty($rawResponse) || is_string($rawResponse) || $rawResponse instanceof \Construct\View) {
            if ($rawResponse instanceof \Construct\View) {
                $rawResponse = $rawResponse->render();
            }
            if (empty($rawResponse)) {
                $rawResponse = '';
            }

            $response = \Construct\ServiceLocator::response();
            $response->setContent($rawResponse);
        } elseif ($rawResponse instanceof \Construct\Response) {
            \Construct\ServiceLocator::setResponse($rawResponse);
            if ($subrequest) {
                \Construct\ServiceLocator::removeRequest();
            }
            return $rawResponse;
        } elseif ($rawResponse === null) {
            $response = \Construct\ServiceLocator::response();
        } else {
            \Construct\ServiceLocator::removeRequest();
            throw new \Construct\Exception('Unknown response');
        }

        if ($subrequest) {
            \Construct\ServiceLocator::removeRequest();
        }

        return $response;
    }

    /**
     * @ignore
     */
    public function modulesInit()
    {
        if (!ipConfig()->database()) {
            return;
        }
        $translator = \Construct\ServiceLocator::translator();
        $overrideDir = ipFile("file/translations/override/");

        $plugins = \Construct\Internal\Plugins\Service::getActivePluginNames();
        foreach ($plugins as $plugin) {

            $translationsDir = ipFile("Plugin/$plugin/translations/");
            $translator->addTranslationFilePattern('json', $translationsDir, "$plugin-%s.json", $plugin);
            $translator->addTranslationFilePattern('json', $overrideDir, "$plugin-%s.json", $plugin);

            $translator->addTranslationFilePattern('json', $translationsDir, "$plugin-admin-%s.json", $plugin . '-admin');
            $translator->addTranslationFilePattern('json', $overrideDir, "$plugin-admin-%s.json", $plugin . '-admin');
        }


        foreach ($plugins as $plugin) {
            $routesFile = ipFile("Plugin/$plugin/routes.php");
            $this->addFileRoutes($routesFile, $plugin);
        }
        $this->addFileRoutes(ipFile('Construct/Internal/Ecommerce/routes.php'), 'Ecommerce');

    }

    protected function addFileRoutes($routesFile, $plugin)
    {
        $router = \Construct\ServiceLocator::router();
        if (file_exists($routesFile)) {
            $routes = array();
            include $routesFile;

            $router->addRoutes(
                $routes,
                array(
                    'plugin' => $plugin,
                    'controller' => 'PublicController',
                )
            );
        }
    }

    /**
     * @ignore
     * @param array $options
     */
    public function run($options = array())
    {
        $config = new \Construct\Config($this->configSetting);
        \Construct\ServiceLocator::setConfig($config);

        require_once __DIR__ . '/Functions.php';

        $this->prepareEnvironment($options);
        $request = new \Construct\Request();

        $request->setQuery($_GET);
        $request->setPost($_POST);
        $request->setServer($_SERVER);
        $request->setRequest($_REQUEST);


        $response = $this->handleRequest($request, $options, false);
        $this->handleResponse($response);
        $this->close();
    }

    /**
     * @ignore
     * @param \Construct\Response $response
     * @throws \Construct\Exception
     */
    public function handleResponse(\Construct\Response $response)
    {
        $response = ipFilter('ipSendResponse', $response);
        ipEvent('ipBeforeResponseSent', array('response' => $response));
        if (method_exists($response, 'execute')) {
            $response = $response->execute();
        }
        $response->send();
    }

    /**
     * @ignore
     */
    public function close()
    {
        ipEvent('ipBeforeApplicationClosed');
        if (ipConfig()->database()) {
            ipDb()->disconnect();
        }
    }

    /**
     * Get security token used to prevent cross site scripting attacks
     *
     * @return string security token
     */
    public function getSecurityToken()
    {
        if (empty($_SESSION['ipSecurityToken'])) {
            $_SESSION['ipSecurityToken'] = md5(uniqid(rand(), true));
        }
        return $_SESSION['ipSecurityToken'];
    }

    /**
     * @param $requestLanguage
     */
    protected function setLocale($requestLanguage)
    {
        //find out and set locale
        $locale = $requestLanguage->getCode();
        if (strlen($locale) == '2') {
            $locale = strtolower($locale) . '_' . strtoupper($locale);
        } else {
            $locale = str_replace('-', '_', $locale);
        }
        $locale .= '.utf8';
        if ($locale == "tr_TR.utf8" && (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION < 5)) { //Overcoming this bug https://bugs.php.net/bug.php?id=18556
            setlocale(LC_COLLATE, $locale);
            setlocale(LC_MONETARY, $locale);
            setlocale(LC_NUMERIC, $locale);
            setlocale(LC_TIME, $locale);
            setlocale(LC_MESSAGES, $locale);
            setlocale(LC_CTYPE, "en_US.utf8");
        } else {
            setLocale(LC_ALL, $locale);
        }
        setlocale(LC_NUMERIC, "C"); //user standard C syntax for numbers. Otherwise you will get funny things with when autogenerating CSS, etc.
    }
}
