<?php
/**
 * @package construct
 *
 */

namespace Construct\Internal\Core;


class Event
{
    public static function ipBeforeController()
    {
        $request = \Construct\ServiceLocator::request();

        $sessionLifetime = ini_get('session.gc_maxlifetime');
        if (!$sessionLifetime) {
            $sessionLifetime = 120;
        }
        if ($sessionLifetime > 30) {
            $sessionLifetime = $sessionLifetime - 20;
        }
        ipAddJsVariable('ipSessionRefresh', $sessionLifetime);


        if (ipConfig()->isDebugMode()) {
            ipAddJs('Construct/Internal/Core/assets/ipCore/jquery.js', null, 10); // default, global jQuery
            ipAddJs('Construct/Internal/Core/assets/ipCore/console.log.js', null, 10);
            ipAddJs('Construct/Internal/Core/assets/ipCore/functions.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/jquery.tools.form.js');

            ipAddJs('Construct/Internal/Core/assets/ipCore/form/color.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/form/file.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/form/richtext.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/form/repositoryFile.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/form/url.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/form.js');

            ipAddJs('Construct/Internal/Core/assets/ipCore/validator.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/widgets.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/ipCore.js');

        } else {
            ipAddJs('Construct/Internal/Core/assets/ipCore.min.js', null, 10);
        }


        //Form init

        $validatorTranslations = array(
            'Construct-admin' => static::validatorLocalizationData('Construct-admin'),
            ipContent()->getCurrentLanguage()->getCode() => static::validatorLocalizationData('Construct')
        );
        ipAddJsVariable('ipValidatorTranslations', $validatorTranslations);


        if (ipAdminId() || \Construct\Internal\Admin\Model::isLoginPage() || \Construct\Internal\Admin\Model::isPasswordResetPage()) {
            if (ipConfig()->isDebugMode()) {
                ipAddJs('Construct/Internal/Core/assets/admin/managementMode.js');
                ipAddJs('Construct/Internal/Core/assets/admin/functions.js');


                ipAddJs('Construct/Internal/Core/assets/admin/validator.js');
                ipAddJs('Construct/Internal/Core/assets/admin/bootstrap/bootstrap.js');
                ipAddJs('Construct/Internal/Core/assets/admin/bootstrap-switch/bootstrap-switch.js');
            } else {
                ipAddJs('Construct/Internal/Core/assets/admin.min.js', null, 10);
            }

            ipAddJs('Construct/Internal/Core/assets/tinymce/pastePreprocess.js');
            ipAddJs('Construct/Internal/Core/assets/tinymce/default.js');
        }

        if (ipAdminId()) {

            ipAddJs('Construct/Internal/Core/assets/js/tiny_mce/jquery.tinymce.min.js');
            ipAddJs('Construct/Internal/Core/assets/js/tiny_mce/tinymce.min.js');

            ipAddJsVariable(
                'ipBrowseLinkModalTemplate',
                ipView('view/browseLinkModal.php')->render()
            );


            ipAddJs('Construct/Internal/Core/assets/ipCore/plupload/plupload.full.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/plupload/plupload.browserplus.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/plupload/plupload.gears.js');
            ipAddJs('Construct/Internal/Core/assets/ipCore/plupload/jquery.plupload.queue/jquery.plupload.queue.js');

            if (is_file(ipThemeFile('setup/admin.js'))) {
                ipAddJs(ipThemeUrl('setup/admin.js'));
            }

            ipAddCss('Construct/Internal/Core/assets/admin/admin.css');

        }
    }


    protected static function validatorLocalizationData($namespace)
    {
        // TODO do this localization on client side
        if ($namespace == 'Construct') {
            $answer = array(
                '*' => __('Please correct this value', 'Construct'),
                ':email' => __('Please enter a valid email address', 'Construct'),
                ':number' => __('Please enter a valid numeric value', 'Construct'),
                ':url' => __('Please enter a valid URL', 'Construct'),
                '[max]' => __('Please enter a value no larger than $1', 'Construct'),
                '[min]' => __('Please enter a value of at least $1', 'Construct'),
                '[required]' => __('Please complete this mandatory field', 'Construct')
            );
        } elseif ($namespace == 'Construct-admin') {
            $answer = array(
                '*' => __('Please correct this value', 'Construct-admin'),
                ':email' => __('Please enter a valid email address', 'Construct-admin'),
                ':number' => __('Please enter a valid numeric value', 'Construct-admin'),
                ':url' => __('Please enter a valid URL', 'Construct-admin'),
                '[max]' => __('Please enter a value no larger than $1', 'Construct-admin'),
                '[min]' => __('Please enter a value of at least $1', 'Construct-admin'),
                '[required]' => __('Please complete this mandatory field', 'Construct-admin')
            );
        } else {
            throw new \Construct\Exception('Unknown translation domain: ' . esc($namespace));
        }
        return $answer;
    }

    public static function ipCronExecute($info)
    {
        if ($info['firstTimeThisDay'] || $info['test']) {
            self::cleanDirRecursive(ipFile('file/tmp/'));
            self::cleanDirRecursive(ipFile('file/secure/tmp/'));
        }
    }


    protected static function cleanDirRecursive($dir, $depth = 0)
    {
        if ($depth > 100) {
            return;
        }
        if (!is_dir($dir)) {
            return;
        }
        if ($handle = opendir($dir)) {
            $now = time();
            // List all the files
            while (false !== ($file = readdir($handle))) {
                if (file_exists($dir . $file) && $file != ".." && $file != ".") {
                    if (filectime($dir . $file) + 3600 * 24 * ipGetOption(
                            'Config.tmpFileExistance',
                            14
                        ) < $now
                    ) { //delete if a file is created more than two weeks ago
                        if (is_dir($dir . $file)) {
                            self::cleanDirRecursive($dir . $file . '/', $depth + 1);
                            if (self::dirIsEmpty($dir . $file)) {
                                rmdir($dir . $file);
                            }
                        } else {
                            if ($file != '.htaccess' && ($file != 'readme.txt' || $depth > 0) && ($file != 'readme.md' || $depth > 0)) {
                                unlink($dir . $file);
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    private static function dirIsEmpty($dir)
    {
        if (!is_readable($dir)) {
            return null;
        }
        return (count(scandir($dir)) == 2);
    }
}
