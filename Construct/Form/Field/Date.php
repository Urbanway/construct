<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Field;

use Construct\Form\Field;


class Date extends Field
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
        return '<input ' . $this->getAttributesStr($doctype) . ' class="form-control ' . implode(
            ' ',
            $this->getClasses()
        ) . '" name="' . htmlspecialchars($this->getName()) . '" ' . $this->getValidationAttributesStr(
            $doctype
        ) . ' type="date" value="' . htmlspecialchars($this->getValue()) . '" />';
    }



}
