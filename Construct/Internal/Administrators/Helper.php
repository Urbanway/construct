<?php
/**
 * @package construct
 *
 *
 */
namespace Construct\Internal\Administrators;


class Helper
{
    public static function createForm()
    {
        $form = new \Construct\Form();
        $form->addAttribute('autocomplete', 'off');


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'username', // HTML "name" attribute
                'label' => __('User name', 'Construct-admin', false), // Field label that will be displayed next to input field
            ));
        $field->addValidator("Required");
        $field->addValidator(array('Unique', array('table' => 'administrator')));
        $form->addField($field);


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'email', // HTML "name" attribute
                'label' => __('Email', 'Construct-admin', false), // Field label that will be displayed next to input field
            ));
        $field->addValidator("Required");
        $form->addField($field);

        $field = new \Construct\Form\Field\Password(
            array(
                'name' => 'password', // HTML "name" attribute
                'label' => __('Password', 'Construct-admin', false), // Field label that will be displayed next to input field
            ));
        $field->addValidator("Required");
        $form->addField($field);

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'aa', // HTML "name" attribute
                'value' => 'Administrators.add'
            ));
        $form->addField($field);

        return $form;
    }


    public static function updateForm()
    {
        $form = new \Construct\Form();
        $form->addAttribute('autocomplete', 'off');


        $field = new \Construct\Form\Field\Text(
            array(
                'name' => 'username', // HTML "name" attribute
                'label' => __('User name', 'Construct-admin', false), // Field label that will be displayed next to input field
                'value' => '{{activeAdministrator.username}}'
            ));
        $field->addValidator("Required");
        $form->addField($field);


        $field = new \Construct\Form\Field\Email(
            array(
                'name' => 'email', // HTML "name" attribute
                'label' => __('Email', 'Construct-admin', false), // Field label that will be displayed next to input field
                'value' => '{{activeAdministratorEmail}}'
            ));
        $field->addValidator("Required");
        $form->addField($field);

        $field = new \Construct\Form\Field\Password(
            array(
                'name' => 'password',
                // HTML "name" attribute
                'label' => __('New password (optional)', 'Construct-admin', false),
                // Field label that will be displayed next to input field
                'value' => ''
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'aa', // HTML "name" attribute
                'value' => 'Administrators.update'
            ));
        $form->addField($field);

        $field = new \Construct\Form\Field\Hidden(
            array(
                'name' => 'id', // HTML "name" attribute
                'value' => ''
            ));
        $form->addField($field);

        return $form;
    }

}
