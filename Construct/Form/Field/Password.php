<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Field;

use Construct\Form\Field;


class Password extends Field
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
        ) . '" name="' . escAttr($this->getName()) . '" ' . $this->getValidationAttributesStr(
            $doctype
        ) . ' type="password" value="' . escAttr($this->getValue()) . '" />';
    }



}
