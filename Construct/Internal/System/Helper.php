<?php

/**
 * @package construct
 *
 */

namespace Construct\Internal\System;


class Helper
{

    public static function recoveryPageForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $pages = constructQuery()->selectAll('page', 'id, title', array('isDeleted' => 1));

        foreach ($pages as $page) {
            $field = new \Construct\Form\Field\Checkbox(
                array(
                    'name' => 'page[]',
                    'label' => $page['title'],
                    'value' => false,
                    'postValue' => $page['id']
                ));
            $form->addField($field);
        }

        return $form;
    }

    public static function emptyPageForm()
    {
        $form = new \Construct\Form();
        $form->setEnvironment(\Construct\Form::ENVIRONMENT_ADMIN);

        $pages = constructQuery()->selectAll('page', 'id, title', array('isDeleted' => 1));

        foreach ($pages as $page) {
            $field = new \Construct\Form\Field\Checkbox(
                array(
                    'name' => 'page[]',
                    'label' => $page['title'],
                    'value' => true,
                    'postValue' => $page['id']
                ));
            $form->addField($field);
        }

        return $form;
    }

}
