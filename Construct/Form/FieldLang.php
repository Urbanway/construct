<?php

/**
 * @package construct
 *
 */

namespace Construct\Form;


/**
 * Multilingual form field.
 * All Multilingual form fields have to extend this class so that any plugin could check if
 * input field object is multilingual or not using following code: $object instanceof \Construct\Form\FieldLang
 *
 * @package Construct\Form
 */
abstract class FieldLang extends Field
{

}
