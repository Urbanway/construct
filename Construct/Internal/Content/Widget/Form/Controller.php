<?php
/**
 * @package construct

 *
 */
namespace Construct\Internal\Content\Widget\Form;




class Controller extends \Construct\WidgetController{


    public function getTitle() {
        return __('Form', 'Construct-admin', false);
    }

    public function post ($widgetId, $data) {
        $postData = ipRequest()->getPost();

        $form = $this->createForm($widgetId, $data);
        $errors = $form->validate($postData);

        if (empty($data['success'])) {
            $data['success'] = __('Thank You', 'Construct');
        }
        $successHtml = ipView('helperView/success.php', array('success' => $data['success']))->render();


        if ($errors) {
            $data = array(
                'status' => 'error',
                'errors' => $errors
            );
        } else {
            $this->sendEmail($form, $postData, $data);

            $data = array(
                'status' => 'success',
                'replaceHtml' => $successHtml
            );
        }


        return new \Construct\Response\Json($data);
    }


    public function adminHtmlSnippet()
    {

        $fieldObjects = Model::getAvailableFieldTypes();

        $fieldTypes = array ();
        foreach($fieldObjects as $fieldObject){
            $fieldTypes[] = array(
                'key' => $fieldObject->getKey(),
                'title' => $fieldObject->getTitle()
            );
        }
        usort($fieldTypes, array($this, 'sortFieldTypes'));
        $data['fieldTypes'] = $fieldTypes;
        $data['optionsForm'] = $this->optionsForm();

        $snippet = ipView('snippet/popup.php', $data)->render();
        return $snippet;

    }

    protected function optionsForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $field = new \Construct\Form\Field\RichText(
            array(
                'name' => 'success',
                'label' => __('Thank you message', 'Construct-admin', false)
            ));
        $form->addfield($field);

        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'sendTo',
                'label' => __('Recipient', 'Construct-admin', false)
            ));

        $values = array(
            array('default', __('Website\'s email', 'Construct-admin', false). ' (' . ipGetOptionLang('Config.websiteEmail') . ')'),
            array('custom', __('Custom emails separated by space', 'Construct-admin', false))
        );
        $field->setValues($values);
        $form->addfield($field);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'emails',
                'label' => __('Custom emails separated by space', 'Construct-admin', false),
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'buttonText',
                'label' => __('Submit button text (leave empty for default)', 'Construct-admin', false)
            ));
        $form->addField($field);

        return $form; // Output a string with generated HTML form
    }


    /**
     * @param \Construct\Form $form
     * @param array $postData
     * @param array $data
     */
    public function sendEmail ($form, $postData, $data) {

        $contentData = [];

        $websiteName = ipGetOptionLang('Config.websiteTitle');
        $websiteEmail = ipGetOptionLang('Config.websiteEmail');


        $from = $websiteEmail;
        $files = [];

        foreach($form->getFields() as $field) {

            if ($field->getType() == \Construct\Form\Field::TYPE_REGULAR) {
                if (!isset($postData[$field->getName()])) {
                    $postData[$field->getName()] = null;
                }

                $title = $field->getLabel();
                $value = $field->getValueAsString($postData, $field->getName());
                $contentData[] = array(
                    'fieldClass' => get_class($field),
                    'title' => $title,
                    'value' => $value
                );
            }

            if (get_class($field) == 'Construct\Form\Field\Email') {
                $userFrom = $field->getValueAsString($postData, $field->getName());
                if ($userFrom != '') {
                    $from = $userFrom;
                }
            }


            if (get_class($field) == 'Construct\Form\Field\File') {
                $uploadedFiles = $field->getFiles($postData, $field->getName());
                $originalNames = $field->originalFileNames($postData, $field->getName());
                foreach($uploadedFiles as $key => $uploadedFile) {
                    $files[] = array($uploadedFile, $originalNames[$key]);
                }
            }
        }
        $content = ipView('helperView/email_content.php', array('values' => $contentData))->render();


        $emailData = array(
            'content' => $content
        );


        $email = ipEmailTemplate($emailData);


        //get page where this widget sits :)
        $fullWidgetRecord = \Construct\Internal\Content\Model::getWidgetRecord($postData['widgetId']);
        $pageTitle = '';
        if (isset($fullWidgetRecord['revisionId'])) {
            $revision = \Construct\Internal\Revision::getRevision($fullWidgetRecord['revisionId']);
            if (!empty($revision['pageId'])) {
                $pageTitle = ipPage($revision['pageId'])->getTitle();
            }
        }

        $subject = $websiteName.': '.$pageTitle;

        $emailQueue = new \Construct\Internal\Email\Module();

        if (!empty($data['sendTo']) && $data['sendTo'] == 'custom') {
            if (empty($data['emails'])) {
                $data['emails'] = '';
            }
            $emailList = preg_split("/[\s,]+/", $data['emails']);
        } else {
            $emailList = array($websiteEmail);
        }

        foreach($emailList as $listItem) {
            $emailQueue->addEmail($from, '', $listItem, '',  $subject, $email, false, true, $files);
        }

        $emailQueue->send();

    }



    public function defaultData()
    {
        $data = [];
        $data['fields'] = [];
        $data['fields'][] = array (
            'type' => 'Text',
            'label' => __('Name', 'Construct', false),
            'options' => []
        );
        $data['fields'][] = array (
            'type' => 'Email',
            'label' => __('Email', 'Construct', false),
            'options' => []
        );
        $data['fields'][] = array (
            'type' => 'Textarea',
            'label' => __('Text', 'Construct', false),
            'options' => []
        );
        return $data;
    }

    public function generateHtml($revisionId, $widgetId, $data, $skin) {

        $data['form'] = $this->createForm($widgetId, $data);

        if (empty($data['success'])) {
            $data['success'] = __('Thank You', 'Construct');
        }



        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }


    public function dataForJs($revisionId, $widgetId, $data, $skin) {
        //collect available field types
        $fieldTypeObjects = Model::getAvailableFieldTypes();

        $fieldTypes = array ();
        foreach($fieldTypeObjects as $typeObject){
            $fieldTypes[$typeObject->getKey()] = array(
                'key' => $typeObject->getKey(),
                'title' => $typeObject->getTitle(),
                'optionsInitFunction' => $typeObject->getJsOptionsInitFunction(),
                'optionsSaveFunction' => $typeObject->getJsOptionsSaveFunction(),
                'optionsHtml' => $typeObject->getJsOptionsHtml()
            );
        }
        $data['fieldTypes'] = $fieldTypes;

        if (empty($data['fields'])) {
            $data['fields'] = [];
            $data['fields'][] = array (
                'type' => 'Text',
                'label' => __('Name', 'Construct', false),
                'options' => []
            );
            $data['fields'][] = array (
                'type' => 'Email',
                'label' => __('Email', 'Construct', false),
                'options' => []
            );
            $data['fields'][] = array (
                'type' => 'Textarea',
                'label' => __('Text', 'Construct', false),
                'options' => []
            );
        }



        return $data;
    }

    /**
     *
     *
     * @param int $widgetId
     * @param array $data
     * @return \Construct\Form
     */
    protected function createForm($widgetId, $data) {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_PUBLIC);

        if (empty($data['fields']) || !is_array($data['fields'])) {
            $data['fields'] = [];
        }
        foreach ($data['fields'] as $fieldKey => $field) {
            if (!isset($field['type']) || !isset($field['label'])) {
                continue;
            }


            if ($field['type'] == 'Fieldset') {
                $label = empty($field['label']) ? '' : $field['label'];
                $form->addFieldset(new \Construct\Form\Fieldset($label));
                continue;
            }

            if (!isset($field['options'])) {
                $field['options'] = [];
            }
            if (!isset($field['options']) || !is_array($field['options'])) {
                $field['options'] = [];
            }
            if (!isset($field['required'])) {
                $field['required'] = false;
            }
            $fieldType = Model::getFieldType($field['type']);
            if ($fieldType) {
                $fieldData = array (
                    'label' => $field['label'],
                    'name' => 'ipForm_field_'.$fieldKey,
                    'required' => $field['required'],
                    'options' => $field['options']
                );

                try {
                    $newField = $fieldType->createField($fieldData);
                    $form->addField($newField);
                } catch (\Construct\Exception\Content $e) {
                    ipLog()->error('FormWidget.failedAddField: Widget failed to add field.', array('widget' => 'Form', 'exception' => $e, 'fieldData' => $fieldData));
                }

            }
        }



        //special variable to post to widget controller
        $field = new \Construct\Form\Field\Hidden(
        array(
        'name' => 'sa',
        'value' => 'Content.widgetPost'
        ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Hidden(
        array(
        'name' => 'widgetId',
        'value' => $widgetId
        ));
        $form->addField($field);

        //antispam
        $field = new \Construct\Form\Field\Antispam(
        array(
        'name' => 'checkField'
        ));
        $form->addField($field);

        //submit
        if (!empty($data['buttonText'])) {
            $value = $data['buttonText'];
        } else {
            $value = __('Send', 'Construct', false);
        }

        $field = new \Construct\Form\Field\Submit(
        array(
            'value' => $value
        ));
        $form->addField($field);



        return $form;
    }

    protected function sortFieldTypes($a, $b) {
        return strcasecmp($a['title'], $b['title']);
    }
}
