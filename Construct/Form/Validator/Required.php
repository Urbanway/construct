<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;

use Construct\Form\Validator;


class Required extends Validator
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
        if (!array_key_exists($valueKey, $values) || in_array(
                $values[$valueKey],
                array(null, false, '', array()),
                true
            )
        ) {
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = __('Required field', 'Construct-admin');
            } else {
                $errorText = __('Required field', 'Construct');
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
        return 'required="required"';
    }

}
