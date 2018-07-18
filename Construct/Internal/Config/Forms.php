<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Config;


class Forms
{
    public static function getForm()
    {
        $form = new \Construct\Form();
        $form->addClass('ipsConfigForm');
        $form->setAjaxSubmit(0);


        $field = new FieldOptionTextLang(
            array(
                'optionName' => 'Config.websiteTitle',
                'name' => 'websiteTitle', //html "name" attribute
                'label' => __('Website title', 'Construct-admin', false), //field label that will be displayed next to input field
                'hint' => __('Used as a sender name in emails and as default website logo.', 'Construct-admin')
            ));
        $field->addClass('ipsAutoSave');
        $form->addField($field);


        $field = new FieldOptionTextLang(
            array(
                'optionName' => 'Config.websiteEmail',
                'name' => 'websiteEmail', //html "name" attribute
                'value' => ipGetOptionLang('Config.websiteEmail'),
                'label' => __('Website email', 'Construct-admin', false), //field label that will be displayed next to input field
                'hint' => __('Email address used as a sender to send emails on behalf of the website.', 'Construct-admin')
            ));
        $field->addValidator('Email');
        $field->addClass('ipsAutoSave');
        $form->addField($field);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'gmapsApiKey',
                'value' => ipGetOption('Config.gmapsApiKey'),
                'label' => __('Google Maps API key', 'Construct-admin', false),
                'note' => __('You must provide Google Maps API key for Map widget to work.', 'Construct-admin', false) . ' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __('Follow instructions.', 'Construct-admin', false) . '</a>'
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);

        return $form;
    }


    public static function getAdvancedForm()
    {
        $form = new \Construct\Form();
        $form->addClass('ipsConfigForm');
        $form->addClass('ipsConfigFormAdvanced');
        $form->addClass('hidden');
        $form->setAjaxSubmit(0);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'automaticCron',
                //html "name" attribute
                'value' => ipGetOption('Config.automaticCron', 1),
                'label' => __('Execute cron automatically', 'Construct-admin', false),
                //field label that will be displayed next to input field
                'hint' => __(
                    'construct execute cron once every hour on randomly selected visitor page load. I you have setup cron manually, you can disable automatic cron functionality.',
                    'Construct-admin'
                ),
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);

        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'cronPassword', //html "name" attribute
                'value' => ipGetOption('Config.cronPassword', 1),
                'label' => __('Cron password', 'Construct-admin', false), //field label that will be displayed next to input field
                'hint' => __('Protect cron from being abusively executed by the strangers.', 'Construct-admin', false),
                'note' => '<span class="ipsUrlLabel">' . __(
                        'Cron URL: ',
                        'Construct-admin'
                    ) . '</span><a target="_blank" class="ipsUrl"></a>'
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);




        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'removeOldEmails',
                //html "name" attribute
                'value' => ipGetOption('Config.removeOldEmails', 0),
                'label' => __('Remove old emails from the log', 'Construct-admin', false)
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);




        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'removeOldEmailsDays',
                //html "name" attribute
                'value' => ipGetOption('Config.removeOldEmailsDays', 720),
                'label' => __('Days to keep emails', 'Construct-admin', false),
                'hint' => __('Meaningful only if "Remove old emails" is on.', 'Construct-admin', false)
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'removeOldRevisions',
                //html "name" attribute
                'value' => ipGetOption('Config.removeOldRevisions', 0),
                'label' => __('Remove old page revisions', 'Construct-admin', false)
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);




        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'removeOldRevisionsDays',
                //html "name" attribute
                'value' => ipGetOption('Config.removeOldRevisionsDays', 720),
                'label' => __('Days to keep revisions', 'Construct-admin', false),
                'hint' => __('Meaningful only if "Remove old page revisions" is on.', 'Construct-admin', false)
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);


        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'allowAnonymousUploads',
                //html "name" attribute
                'value' => ipGetOption('Config.allowAnonymousUploads', 1),
                'label' => __('Allow anonymous uploads', 'Construct-admin', false),
                'hint' => __('Disabling this feature will prevent users from uploading files to your website. E.g. in contact forms.', 'Construct-admin')
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);



        $field = new \Construct\Form\Field\Checkbox(
            array(
                'name' => 'trailingSlash',
                //html "name" attribute
                'value' => ipGetOption('Config.trailingSlash', 1),
                'label' => __('Add trailing slash at the end of page URL', 'Construct-admin', false),
                'hint' => __('This won\'t change existing URLs. Only new and updated pages will get slash at the end.', 'Construct-admin')
            ));
        $field->addClass('ipsAutoSave');
        $field->addAttribute('data-fieldid', $field->getName());
        $field->addAttribute('id', $field->getName());
        $field->addAttribute('data-fieldname', $field->getName());
        $form->addField($field);


        return $form;
    }
}
