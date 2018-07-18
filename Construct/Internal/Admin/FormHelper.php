<?php


namespace Construct\Internal\Admin;


class FormHelper
{


    public static function getLanguageSelectForm()
    {
        //create form object
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $form->addClass('ipsLanguageSelect');

        //add text field to form object
        $field = new \Construct\Form\Field\Select(
            array(
                'name' => 'languageCode',
                'values' => self::getAvailableLocales()
            ));
        $field->setValue(ipConfig()->adminLocale());
        $form->addfield($field);

        return $form;
    }

    protected static function getAvailableLocales()
    {
        $locales = [];
        $translationDirectories = array(
            ipFile('Construct/Internal/Translations/translations'),
            ipFile('file/translations/original'),
            ipFile('file/translations/override')
        );
        $files = [];
        foreach($translationDirectories as $dir) {
            if (!is_dir($dir)) {
                continue;
            }
            $files = array_merge($files, scandir($dir));
        }


        $files = array_unique($files, SORT_STRING);
        sort($files);

        foreach ($files as $file) {
            if (preg_match('/^Construct-admin-([a-z\_A-Z]+)\.json$/', $file, $matches)) {
                $locales[] = array($matches[1], strtoupper($matches[1]));
            }
        }

        if (empty($locales)) {
            $locales = array(array('en', 'EN'));
        }
        return $locales;
    }

    public static function getLoginForm()
    {
        //create form object
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        //add text field to form object
        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'sa',
                'value' => 'Admin.loginAjax', //html "name" attribute
            ));
        $form->addfield($field);


        //add text field to form object
        $field = new \Construct\Form\Field\Blank(
            array(
                'name' => 'global_error',
            ));
        $form->addfield($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'login', //html "name" attribute
                'label' => __('Username', 'Construct-admin', false)
            ));
        $field->addValidator('Required');
        $form->addField($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Password(
            array(
                'name' => 'password', //html "name" attribute
                'label' => __('Password', 'Construct-admin', false)
            ));
        $field->addValidator('Required');
        $form->addField($field);


        //add text field to form object
        $field = new \Construct\Form\Field\Submit(
            array(
                'value' => __('Login', 'Construct-admin', false)
            ));
        $field->addClass('ipsLoginButton');
        $form->addField($field);

        return $form;
    }

    public static function getPasswordResetForm1()
    {
        //create form object
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        //add text field to form object
        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'sa',
                'value' => 'Admin.passwordResetAjax', //html "name" attribute
            ));
        $form->addfield($field);


        //add text field to form object
        $field = new \Construct\Form\Field\Blank(
            array(
                'name' => 'global_error',
            ));
        $form->addfield($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'username', //html "name" attribute
                'label' => __('Username or email', 'Construct-admin', false)
            ));
        $field->addValidator('Required');
        $form->addField($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Submit(
            array(
                'value' => __('Reset', 'Construct-admin', false)
            ));
        $field->addClass('ipsLoginButton');
        $form->addField($field);


        return $form;
    }


    public static function getPasswordResetForm2()
    {
        //create form object
        $form = new \Construct\Form();

        //add text field to form object
        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'sa',
                'value' => 'Admin.passwordResetAjax2', //html "name" attribute
            ));
        $form->addfield($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'secret',
                'value' => ipRequest()->getQuery('secret', ''), //html "name" attribute
            ));
        $form->addfield($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'userId',
                'value' => ipRequest()->getQuery('id', ''), //html "name" attribute
            ));
        $form->addfield($field);


        //add text field to form object
        $field = new \Construct\Form\Field\Blank(
            array(
                'name' => 'global_error',
            ));
        $form->addfield($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Password(
            array(
                'name' => 'password', //html "name" attribute
                'label' => __('New password', 'Construct-admin', false)
            ));
        $field->addValidator('Required');
        $form->addField($field);

        //add text field to form object
        $field = new \Construct\Form\Field\Submit(
            array(
                'value' => __('Save', 'Construct-admin', false)
            ));
        $field->addClass('ipsLoginButton');
        $form->addField($field);

        $form->addClass('ipsPasswordResetForm2');


        return $form;
    }

}
