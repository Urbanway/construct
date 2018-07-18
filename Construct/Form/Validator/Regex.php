<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;


class Regex extends \Construct\Form\Validator
{

    /**
     * Get error
     *
     * @param array $values
     * @param int $valueKey
     * @param $environment
     * @return string|bool
     */
    public function getError($values, $valueKey, $environment)
    {
        if (empty($values[$valueKey])) {
            return false;
        }

        if (!preg_match($this->data, $values[$valueKey])) {
            if ($this->errorMessage) {
                return $this->errorMessage;
            }
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = __('Please correct this value', 'Construct-admin');
            } else {
                $errorText = __('Please correct this value', 'Construct');
            }
            return $errorText;
        }

        return false;
    }

}
