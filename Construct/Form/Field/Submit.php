<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Field;

use Construct\Form\Field;


class Submit extends Field
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
        return '<button ' . $this->getAttributesStr($doctype) . ' class="bttn bttn-default ' . implode(
            ' ',
            $this->getClasses()
        ) . '" name="' . htmlspecialchars($this->getName()) . '" ' . $this->getValidationAttributesStr(
            $doctype
        ) . ' type="submit">' . htmlspecialchars($this->getValue()) . '</button>';
    }



    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return self::TYPE_SYSTEM;
    }

}
