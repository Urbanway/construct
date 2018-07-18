<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;

use Construct\Form\Validator;


/**
 * Number field validator
 */
class Number extends Validator
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
        $value = $values[$valueKey];
        if (!preg_match('/^[0-9]+$/', $value)) {
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = __('Must be a number.', 'Construct-admin');
            } else {
                $errorText = __('Must be a number.', 'Construct');
            }

            return $errorText;
        } else {
            return false;
        }
    }

}
