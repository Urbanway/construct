<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Field;

use Construct\Form\Field;


class Currency extends Field
{

    /**
     * Render field
     *
     * @param string $doctype
     * @param $environment
     * @return string
     */
    public function render($doctype, $environment)
    {
        return '<input type="number" min="0.00" step="0.01" ' . $this->getAttributesStr($doctype) . ' class="form-control ' . implode(
            ' ',
            $this->getClasses()
        ) . '" name="' . htmlspecialchars($this->getName()) . '" ' . $this->getValidationAttributesStr(
            $doctype
        ) . ' value="' . htmlspecialchars($this->getValue()) . '" />';
    }


    /**
     * Check if field passes validation
     *
     * @param string $values
     * @param string $valueKey
     * @param string $environment \Construct\Form::ENVIRONMENT_ADMIN or \Construct\Form::ENVIRONMENT_PUBLIC
     * @return bool
     */
    public function validate($values, $valueKey, $environment)
    {
        if (!empty($values[$valueKey]) && !preg_match('/^[0-9]+(?:\.[0-9]{0,2})?$/', $values[$valueKey])) {
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                return __('Please enter correct currency format. Eg. 10.50', 'Construct-admin', false);
            } else {
                return __('Please enter correct currency format. Eg. 10.50', 'Construct', false);
            }
        }

        return parent::validate($values, $valueKey, $environment);
    }




}
