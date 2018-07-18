<?php
/**
 * @package construct
 *
 */

namespace Construct\Internal\Email;


class AdminController extends \Construct\GridController
{



    protected function config()
    {
        return array(
            'type' => 'table',
            'allowCreate' => false,
            'allowUpdate' => false,
            'allowDelete' => false,
            'orderField' => 'id',
            'orderDirection' => 'desc',
            'table' => 'email_queue',
            'title' => __('Email log', 'Construct-admin', false),
            'actions' => [],
            'fields' => array(
                array(
                    'label' => __('Subject', 'Construct-admin', false),
                    'field' => 'subject'
                ),
                array(
                    'label' => __('Recipient name', 'Construct-admin', false),
                    'field' => 'toName',
                    'preview' => false
                ),
                array(
                    'label' => __('Recipient email', 'Construct-admin', false),
                    'field' => 'to',
                    'preview' => __CLASS__ . '::to'
                ),
                array(
                    'label' => __('Sender name', 'Construct-admin', false),
                    'field' => 'fromName',
                    'preview' => false
                ),
                array(
                    'label' => __('Sender email', 'Construct-admin', false),
                    'field' => 'from',
                    'preview' => __CLASS__ . '::from'
                ),
                array(
                    'label' => __('Sent at', 'Construct-admin', false),
                    'field' => 'send',
                    'preview' => true
                ),
                array(
                    'label' => __('Attachment', 'Construct-admin', false),
                    'field' => 'fileNames'
                ),
                array(
                    'label' => '',
                    'field' => 'id',
                    'preview' => '<a href="#" class="ipsEmailPreview">' . __('Preview', 'Construct-admin') . '</a>',
                    'allowUpdate' => false,
                    'allowInsert' => false,
                    'allowSearch' => false
                )
            )
        );
    }

    public function index()
    {
        ipAddJs('assets/email.js');
        ipAddCss('assets/email.css');

        $previewModal = ipView('view/previewModal.php');
        return parent::index() . $previewModal;
    }


    public function preview()
    {
        $id = ipRequest()->getQuery('id');
        if (!$id) {
            throw new \Construct\Exception('Email not found');
        }
        $email = Db::getEmail($id);
        $viewData = array(
            'email' => $email
        );
        $content = ipView('view/preview.php', $viewData);
        $response = new \Construct\Response($content);
        return $response;
    }




    public static function to($value, $recordData)
    {
        return esc($recordData['toName'] . ' ' . $value);
    }

    public static function from($value, $recordData)
    {
        return esc($recordData['fromName'] . ' ' . $value);
    }


}
