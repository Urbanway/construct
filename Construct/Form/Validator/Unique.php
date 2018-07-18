<?php

/**
 * @package construct
 *
 */

namespace Construct\Form\Validator;


class Unique extends \Construct\Form\Validator
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
        if (empty($data['table'])) {
            throw new \Construct\Exception('Unique validator expect table name');
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
        if (!array_key_exists($valueKey, $values)) {
            return false;
        }

        if ($values[$valueKey] == '' && empty($this->data['allowEmpty'])) {
            return false;
        }

        $table = $this->data['table'];

        $idField = empty($this->data['idField']) ? 'id' : $this->data['idField'];

        $row = ipDb()->selectRow($table, '*', array($valueKey => $values[$valueKey]));

        if (!$row) {
            return false;
        }

        if (isset($values[$idField]) && $values[$idField] == $row[$idField]) {
            return false;
        }

        if ($this->errorMessage !== null) {
            return $this->errorMessage;
        }

        if ($environment == \Construct\Form::ENVIRONMENT_ADMIN) {
            $errorText = __('The value should be unique', 'Construct-admin');
        } else {
            $errorText = __('The value should be unique', 'Construct');
        }

        return $errorText;
    }

}
