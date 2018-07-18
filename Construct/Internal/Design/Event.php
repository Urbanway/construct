<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Design;


class Event
{
    public static function ipBeforeController()
    {
        $configModel = ConfigModel::instance();
        if ($configModel->isInPreviewState()) {
            ipAddJsVariable('ipRepositoryUrl', ipFileUrl('file/repository/'));
            static::initConfig();
        }

        $lessCompiler = LessCompiler::instance();
        if (ipConfig()->isDevelopmentEnvironment()) {
            if ($lessCompiler->shouldRebuild(ipConfig()->theme())) {
                $lessCompiler->rebuild(ipConfig()->theme());
            }
        }
    }

    /*
     * Loading in Preview mode
     */
    protected static function initConfig()
    {
        ipAddCss('Construct/Internal/Core/assets/admin/admin.css');
        ipAddJs('Construct/Internal/Core/assets/js/jquery-ui/jquery-ui.js');
        ipAddJsVariable('ipTranslationSaving', __('Saving...', 'Construct-admin', false));
        ipAddJs('Construct/Internal/Design/assets/optionsBox.js');
        ipAddJsVariable('ipModuleDesignConfiguration', Helper::getConfigurationBoxHtml());

        if (file_exists(ipThemeFile(Model::INSTALL_DIR . 'Options.js'))) {
            ipAddJs(ipThemeUrl(Model::INSTALL_DIR . 'Options.js'));
        } elseif (file_exists(ipThemeFile(Model::INSTALL_DIR . 'options.js'))) {
            ipAddJs(ipThemeUrl(Model::INSTALL_DIR . 'options.js'));
        }

        $model = Model::instance();
        $theme = $model->getTheme(ipConfig()->theme());
        if (!$theme) {
            throw new \Construct\Exception("Theme doesn't exist");
        }

        $options = $theme->getOptionsAsArray();

        $fieldNames = [];
        foreach ($options as $option) {
            if (empty($option['name'])) {
                continue;
            }
            $fieldNames[] = $option['name'];
        }
        ipAddJsVariable('ipModuleDesignOptionNames', $fieldNames);
    }


}
