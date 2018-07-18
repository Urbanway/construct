<?php
namespace Construct\Internal\Plugins;

use Construct\Response\JsonRpc;

class AdminController extends \Construct\Controller
{

    public function index()
    {
        ipAddJs('Construct/Internal/Core/assets/js/angular.js');
        ipAddJs('Construct/Internal/Plugins/assets/plugins.js');
        ipAddJs('Construct/Internal/Plugins/assets/jquery.pluginProperties.js');
        ipAddJs('Construct/Internal/Plugins/assets/pluginsLayout.js');

        $allPlugins = Model::getAllPluginNames();

        $plugins = [];
        foreach ($allPlugins as $pluginName) {
            $plugin = Helper::getPluginData($pluginName);
            $plugin['icon'] = $this->pluginIcon($pluginName);
            $plugins[] = $plugin;
        }

        ipAddJsVariable('pluginList', $plugins);
        ipAddJsVariable('ipTranslationAreYouSure', __('This action will remove plugin\'s files and database records. Do you want to proceed.?', 'Construct-admin', false));

        $data = [];
        $view = ipView('view/layout.php', $data);

        ipResponse()->setLayoutVariable('removeAdminContentWrapper', true);

        return $view->render();
    }

    public function pluginPropertiesForm()
    {
        $pluginName = ipRequest()->getQuery('pluginName');
        if (!$pluginName) {
            throw new \Construct\Exception('Missing required parameters');
        }

        $variables = array(
            'plugin' => Helper::getPluginData($pluginName),
        );

        if (in_array($pluginName, Model::getActivePluginNames())) {
            $variables['form'] = Helper::pluginPropertiesForm($pluginName);
        }

        $variables['icon'] = $this->pluginIcon($pluginName);

        $layout = ipView('view/pluginProperties.php', $variables)->render();

        $layout = ipFilter('ipPluginPropertiesHtml', $layout, array('pluginName' => $pluginName));

        $data = array(
            'html' => $layout
        );
        return new \Construct\Response\Json($data);
    }

    protected function pluginIcon($pluginName)
    {
        if (file_exists(ipFile('Plugin/' . $pluginName . '/assets/icon.svg'))) {
            return ipFileUrl('Plugin/' . $pluginName . '/assets/icon.svg');
        }
        if (file_exists(ipFile('Plugin/' . $pluginName . '/assets/icon.png'))) {
            return ipFileUrl('Plugin/' . $pluginName . '/assets/icon.png');
        }
    }

    public function activate()
    {
        $post = ipRequest()->getPost();
        if (empty($post['params']['pluginName'])) {
            throw new \Construct\Exception('Missing parameter');
        }
        $pluginName = $post['params']['pluginName'];

        try {
            Service::activatePlugin($pluginName);
        } catch (\Construct\Exception $e) {
            $answer = array(
                'jsonrpc' => '2.0',
                'error' => array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ),
                'id' => null,
            );

            return new \Construct\Response\Json($answer);
        }

        $answer = array(
            'jsonrpc' => '2.0',
            'result' => array(
                1
            ),
            'id' => null,
        );

        return new \Construct\Response\Json($answer);
    }

    public function deactivate()
    {
        $post = ipRequest()->getPost();
        if (empty($post['params']['pluginName'])) {
            throw new \Construct\Exception('Missing parameter');
        }
        $pluginName = $post['params']['pluginName'];

        try {
            Service::deactivatePlugin($pluginName);
        } catch (\Construct\Exception $e) {
            $answer = array(
                'jsonrpc' => '2.0',
                'error' => array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ),
                'id' => null,
            );

            return new \Construct\Response\Json($answer);
        }

        $answer = array(
            'jsonrpc' => '2.0',
            'result' => array(
                1
            ),
            'id' => null,
        );

        return new \Construct\Response\Json($answer);
    }

    public function remove()
    {
        $post = ipRequest()->getPost();
        if (empty($post['params']['pluginName'])) {
            throw new \Construct\Exception('Missing parameter');
        }
        $pluginName = $post['params']['pluginName'];

        try {
            Service::removePlugin($pluginName);
        } catch (\Construct\Exception $e) {
            $answer = array(
                'jsonrpc' => '2.0',
                'error' => array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ),
                'id' => null,
            );

            return new \Construct\Response\Json($answer);
        }

        $answer = array(
            'jsonrpc' => '2.0',
            'result' => array(
                1
            ),
            'id' => null,
        );

        return new \Construct\Response\Json($answer);
    }

    public function updatePlugin()
    {
        $pluginName = ipRequest()->getPost('pluginName');
        $data = ipRequest()->getPost();

        $result = Helper::savePluginOptions($pluginName, $data);

        if ($result === true) {
            return \Construct\Response\JsonRpc::result($result);
        } else {
            $errors = $result;
            $data = array (
                'status' => 'error',
                'errors' => $errors
            );
            return new \Construct\Response\Json($data);
        }
    }

    public function market()
    {
        ipAddJs('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.js');
        ipAddCss('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.css');
        ipAddJs('Construct/Internal/Core/assets/js/easyXDM/easyXDM.min.js');

        ipAddJs('Construct/Internal/Plugins/assets/market.js');
        $data = array(
            'marketUrl' => Model::marketUrl(),
        );


        $contentView = ipView('view/market.php', $data);

        ipResponse()->setLayoutVariable('removeAdminContentWrapper', true);

        return $contentView->render();
    }

    public function downloadPlugins()
    {
        ipRequest()->mustBePost();
        $plugins = ipRequest()->getPost('plugins');

        if (!is_writable(Model::pluginInstallDir())) {
            return JsonRpc::error(
                __(
                    'Directory is not writable. Please check your email and install the plugin manually.',
                    'Construct-admin',
                    false
                ),
                777
            );
        }

        try {
            if (!is_array($plugins)) {
                return JsonRpc::error(__('Download failed: invalid parameters', 'Construct-admin', false), 101);
            }

            if (function_exists('set_time_limit')) {
                set_time_limit(count($plugins) * 180 + 30);
            }

            $pluginDownloader = new PluginDownloader();

            foreach ($plugins as $plugin) {
                if (!empty($plugin['url']) && !empty($plugin['name']) && !empty($plugin['signature'])) {
                    $pluginDownloader->downloadPlugin($plugin['name'], $plugin['url'], $plugin['signature']);
                }
            }
        } catch (\Construct\Exception $e) {
            return JsonRpc::error($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return JsonRpc::error(__('Unknown error. Please see logs.', 'Construct-admin', false), 987);
        }

        return JsonRpc::result(array('plugins' => $plugins));
    }


}
