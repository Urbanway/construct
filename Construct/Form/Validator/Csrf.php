<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;

use Construct\Form\Validator;


/**
 * Check antispam field validator
 */
class Csrf extends Validator
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
            return 'error';
        }

        $session = \Construct\ServiceLocator::application();

        if ($values[$valueKey] != $session->getSecurityToken()) {
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = __('Session has expired. Please refresh the page.', 'Construct-admin');
            } else {
                $errorText = __('Session has expired. Please refresh the page.', 'Construct');
            }

            return $errorText;
        } else {
            return false;
        }
    }

}
