<?php

/**
 * @package construct
 *
 */

namespace Construct\Internal\System;


class AdminController extends \Construct\Controller
{
    private $composerUpdateError = null;

    public function __construct() {
        $this->composerUpdateError = __('Composer based installation can\'t be updated via admin. Please use "composer update" from the command line. Then come back here to execute migrations.', 'Construct-admin', false);
    }

    public function index()
    {
        $migrationsUrl = ipActionUrl(array('pa' => 'Update'));
        $model = Model::instance();
        ipAddJs('Construct/Internal/Grid/assets/grid.js');
        ipAddJs('Construct/Internal/Grid/assets/gridInit.js');
        ipAddJs('Construct/Internal/Grid/assets/subgridField.js');
        ipAddJsVariable('isComposerBasedInstallation', (int)ipConfig()->isComposerCore());
        ipAddJsVariable('composerUpdateError', $this->composerUpdateError);
        ipAddJsVariable('migrationsUrl', $migrationsUrl);


        $notes = [];

        if (isset($_SESSION['Construct']['notes']) && is_array($_SESSION['Construct']['notes'])) {
            $notes = $_SESSION['Construct']['notes'];
        }

        unset($_SESSION['Construct']['notes']);

        $enableUpdate = !defined('MULTISITE_WEBSITES_DIR'); // Disable update in MultiSite installation.

        $trash = array(
            'size' => \Construct\Internal\Pages\Service::trashSize()
        );

        $data = array(
            'notes' => $notes,
            'version' => \Construct\ServiceLocator::storage()->get('Construct', 'version'),
            'changedUrl' => $model->getOldUrl() != $model->getNewUrl(),
            'oldUrl' => $model->getOldUrl(),
            'newUrl' => $model->getNewUrl(),
            'migrationsAvailable' => \Construct\Internal\Update\Service::migrationsAvailable(),
            'migrationsUrl' => $migrationsUrl,
            'recoveryPageForm' => \Construct\Internal\System\Helper::recoveryPageForm(),
            'emptyPageForm' => \Construct\Internal\System\Helper::emptyPageForm(),
            'trash' => $trash,
        );

        $content = ipView('view/index.php', $data)->render();

        if ($enableUpdate) {
            ipAddJs('Construct/Internal/System/assets/update.js');
        }
        if ($trash['size'] > 0) {
            ipAddJs('Construct/Internal/Core/assets/js/angular.js');
            ipAddJs('Construct/Internal/System/assets/trash.js');
        }
        ipAddJs('Construct/Internal/System/assets/migrations.js');
        ipAddJs('assets/cache.js');

        return $content;
    }

    public function startUpdate()
    {
        if (ipConfig()->isComposerCore()) {
            return new \Construct\Response\Json(array(
                'error' => $this->composerUpdateError
            ));
        }


        try {
            $successNote = __('construct has been successfully updated.', 'Construct-admin');
            \Construct\Internal\Update\Service::update();
            header('Content-type: application/json; charset=utf-8');
            $_SESSION['Construct']['notes'][] = $successNote;
            echo '{"status":"success"}';
            constructQuery()->disconnect();
            exit; //we can't keep executing the code as all files have been replaced.
        } catch (\Exception $e) {
            return new \Construct\Response\Json(array(
                'error' => $e->getMessage()
            ));
        }
    }

    public function updateLinks()
    {
        Service::updateLinks();
        $_SESSION['Construct']['notes'][] = __('Links have been successfully updated.', 'Construct-admin');

        return new \Construct\Response\Redirect(ipActionUrl(array('aa' => 'System')));
    }

    protected function indexUrl()
    {
        return ipConfig()->baseUrl() . '?aa=System.index';
    }

    public function getConstructNotifications()
    {
        $systemInfo = Model::getConstructNotifications();

        if (isset($_REQUEST['afterLogin'])) { // Request after login.
            if ($systemInfo == '') {
                $_SESSION['ipSystem']['show_system_message'] = false; // Don't display system alert at the top.
                return null;
            } else {
                $md5 = \Construct\ServiceLocator::storage()->get('Construct', 'lastSystemMessageShown');
                if ($systemInfo && (!$md5 || $md5 != md5(serialize($systemInfo)))) { // We have a new message.
                    $newMessage = false;

                    foreach (json_decode($systemInfo) as $infoValue) {
                        if ($infoValue->type != 'status') {
                            $newMessage = true;
                        }
                    }

                    $_SESSION['ipSystem']['show_system_message'] = $newMessage; // Display system alert.
                } else { // This message was already seen.
                    $_SESSION['ipSystem']['show_system_message'] = false; // Don't display system alert at the top.
                    return null;
                }
            }
        } else { // administrator/system tab.
            \Construct\ServiceLocator::storage()->set('Construct', 'lastSystemMessageShown', md5(serialize($systemInfo)));
            $_SESSION['ipSystem']['show_system_message'] = false; // Don't display system alert at the top.
        }

        return new \Construct\Response\Json($systemInfo);
    }

    public function recoveryTrash()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();

        if (!isset($data['pages'])) {
            throw new \Construct\Exception('Missing required parameters');
        }

        $data['pages'] = explode('|', $data['pages']);
        unset($data['pages'][0]);

        \Construct\Internal\Pages\Service::recoveryTrash($data['pages']);

        $answer = array(
            'status' => 'success'
        );

        return new \Construct\Response\Json($answer);
    }

    public function emptyTrash()
    {
        ipRequest()->mustBePost();
        $data = ipRequest()->getPost();

        if (!isset($data['pages'])) {
            throw new \Construct\Exception('Missing required parameters');
        }

        $data['pages'] = explode('|', $data['pages']);
        unset($data['pages'][0]);

        \Construct\Internal\Pages\Service::emptyTrash($data['pages']);

        $answer = array(
            'status' => 'success'
        );

        return new \Construct\Response\Json($answer);
    }

    public function sendUsageStatisticsAjax()
    {
        ipRequest()->mustBePost();

        $usageStatistics = false;

        // Send stats just after admin login
        if (isset($_SESSION['module']['system']['adminJustLoggedIn'])) {
            $usageStatistics = array(
                'action' => 'Admin.login',
                'data' => array(
                    'admin' => ipAdminId()
                )
            );

            // Removing session variable to send these stats only once
            unset($_SESSION['module']['system']['adminJustLoggedIn']);
        }

        // if we have some kind of definition then we send data
        if ($usageStatistics !== false) {
            \Construct\Internal\System\Model::sendUsageStatistics($usageStatistics);
        }

        return \Construct\Response\JsonRpc::result('ok');
    }

    public function clearCache()
    {
        ipRequest()->mustBePost();
        Service::clearCache();
        $_SESSION['Construct']['notes'][] = __('Cache has been cleared.', 'Construct-admin');
        return new \Construct\Response\Json(array('status' => 'success'));
    }
}
