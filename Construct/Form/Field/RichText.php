<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Field;

use Construct\Form\Field;


class RichText extends Field
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
        return '<textarea ' . $this->getAttributesStr($doctype) . ' class="form-control ' . implode(
            ' ',
            $this->getClasses()
        ) . '" name="' . esc($this->getName(), 'attr') . '" ' . $this->getValidationAttributesStr(
            $doctype
        ) . ' >' . esc($this->getValue(), 'textarea') . '</textarea>';
    }

}
