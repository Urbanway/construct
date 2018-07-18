<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;


class InArray extends \Construct\Form\Validator
{

    /**
     * Constructor
     *
     * @param array $data
     * @param string $errorMessage
     * @throws \Construct\Exception
     */
    public function __construct($data, $errorMessage = null)
    {
        if (!is_array($data)) {
            throw new \Construct\Exception('InArray validator expect array of strings');
        }
        parent::__construct($data, $errorMessage);
    }

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

        if (!in_array($values[$valueKey], $this->data)) {
            if ($this->errorMessage) {
                return $this->errorMessage;
            }
            if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
                $errorText = __('The value has to be one of:', 'Construct-admin');
            } else {
                $errorText = __('The value has to be one of:', 'Construct');
            }
            $errorText .= ' ' . implode(', ', $this->data);

            return $errorText;
        }

        return false;
    }

}
