<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;

use Construct\Form\Validator;


/**
 * Antispam field validator
 */
class Antispam extends Validator
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
        if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
            $errorText = __('Form security check has failed. Please refresh the page.', 'Construct-admin');
        } else {
            $errorText = __('Form security check has failed. Please refresh the page.', 'Construct');
        }

        if (empty($values[$valueKey])) {
            return $errorText;
        }
        $value = $values[$valueKey];

        if (!is_array($value) || count($value) != 2) {
            return $errorText;
        }

        // First value should stay empty. Or its a bot :O)
        if (!isset($value[0]) || $value[0] != '') {
            return $errorText;
        }

        // Second value should be encoded today or yesterday date. Yesterday date is needed if user started to fill in data at 23:59
        if (!isset($value[1]) || ($value[1] != md5(date('Y-m-d') . ipConfig()->get('sessionName')) && $value[1] != date(
                    'Y-m-d',
                    time() - 24 * 60 * 60
                ))
        ) {
            return $errorText;
        }

        return false;
    }

}
