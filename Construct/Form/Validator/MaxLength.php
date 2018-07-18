<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;

use Construct\Form\Validator;


class MaxLength extends Validator
{
    protected $maxLength;

    public function __construct($data = array(), $errorMessage = null)
    {
        $this->maxLength = (int)$data;
        $this->errorMessage = $errorMessage;
        if (!is_numeric($data)) {
            throw new \Construct\Exception('MaxLength validator expect integer number as a first parameter');
        }
        parent::__construct($data, $errorMessage);
    }

    /**
     * Get error
     *
     * @param array $values all submitted data
     * @param int $valueKey key of value to be validated
     * @param $environment
     * @return string|bool
     */
    public function getError($values, $valueKey, $environment)
    {
        if (array_key_exists($valueKey, $values) && mb_strlen($values[$valueKey]) > $this->maxLength ) {
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = sprintf(__('Max %d characters', 'Construct-admin'), $this->maxLength);
            } else {
                $errorText = sprintf(__('Max %d characters', 'Construct'), $this->maxLength);
            }

            return $errorText;
        } else {
            return false;
        }
    }

    /**
     * Validator attributes
     *
     * @return string
     */
    public function validatorAttributes()
    {
        return 'maxlength="'.$this->maxLength.'"';
    }

}
