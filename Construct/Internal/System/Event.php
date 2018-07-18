<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\System;


class Event
{
    public static function ipCronExecute($info)
    {
        if ($info['firstTimeThisDay'] || $info['test']) {
            static::checkForUpdates();
            Model::sendUsageStatistics(array('action' => 'Cron.default'), 10);
        }
    }

    private static function checkForUpdates()
    {
        $module = Model::instance();
        $systemInfo = $module->getConstructNotifications();
        if ($systemInfo != '') { //send an email
            $md5 = \Construct\ServiceLocator::storage()->get('Construct', 'lastSystemMessageSent');
            if (!$md5 || $md5 != md5(serialize($systemInfo))) { //we have a new message
                $message = '';
                $messages = $systemInfo;
                if (is_array($messages)) {
                    foreach ($messages as $messageVal) {
                        $message .= '<p>' . $messageVal->message . '</p>';
                    }

                    $onlyStatusMessages = true;
                    foreach ($messages as $messageVal) {
                        if ($messageVal->type != 'status') {
                            $onlyStatusMessages = false;
                        }
                    }

                    if ($onlyStatusMessages) {
                        return; //TODO replace to something that would not terminate execution of following scripts if they will be there some day
                    }

                } else {
                    return; //TODO replace to something that would not terminate execution of following scripts if they will be there some day
                }

                ipEvent('ipSystemMessages', array('messages' => $messages));

                \Construct\ServiceLocator::storage()->set('Construct', 'lastSystemMessageSent', md5(serialize($systemInfo)));
            }


        }
    }

    public static function ipBeforeController()
    {
        if (ipIsManagementState()) {
            // Works only if admin is logged in (AJAX is sent to Admin Controller)
            if (isset($_SESSION['module']['system']['adminJustLoggedIn']) && ipAdminPermission('Super admin')) {
                ipAddJs('Construct/Internal/System/assets/usageStatistics.js');
                ipAddJsVariable('ipSystemSendUsageStatistics', 1);
            }
        }
    }

    public static function ipAdminLoginSuccessful($data)
    {
        $_SESSION['module']['system']['adminJustLoggedIn'] = $data;
    }

}


