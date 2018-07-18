<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Grid\Model\Field;


class Checkbox extends \Construct\Internal\Grid\Model\Field
{
    protected $field = '';
    protected $label = '';
    protected $defaultValue = '';

    public function __construct($fieldConfig, $wholeConfig)
    {
        if (empty($fieldConfig['field'])) {
            throw new \Construct\Exception('\'field\' option required for text field');
        }
        $this->field = $fieldConfig['field'];

        if (!empty($fieldConfig['label'])) {
            $this->label = $fieldConfig['label'];
        }

        if (!empty($fieldConfig['defaultValue'])) {
            $this->defaultValue = $fieldConfig['defaultValue'];
        }
    }

    public function preview($recordData)
    {
        if (!empty($recordData[$this->field])) {
            return __('Yes', 'Construct-admin');
        } else {
            return __('No', 'Construct-admin');
        }
    }

    public function createField()
    {
        $field = new \Construct\Form\Field\Checkbox(array(
            'label' => $this->label,
            'name' => $this->field,
            'layout' => $this->layout,
            'attributes' => $this->attributes
        ));
        $field->setValue($this->defaultValue);
        return $field;
    }

    public function createData($postData)
    {
        return array($this->field => !empty($postData[$this->field]));
    }

    public function updateField($curData)
    {
        $field = new \Construct\Form\Field\Checkbox(array(
            'label' => $this->label,
            'name' => $this->field,
            'layout' => $this->layout,
            'attributes' => $this->attributes
        ));
        if (isset($curData[$this->field])){
        $field->setValue($curData[$this->field]);
        }
        return $field;
    }

    public function updateData($postData)
    {
        return array($this->field => !empty($postData[$this->field]));
    }


    public function searchField($searchVariables)
    {
        $values = array(
            array(null, ''),
            array('1', __('Yes', 'Construct-admin', false)),
            array('0', __('No', 'Construct-admin', false))

        );


        $field = new \Construct\Form\Field\Select(array(
            'label' => $this->label,
            'name' => $this->field,
            'values' => $values,
            'value' => null,
            'layout' => $this->layout,
            'attributes' => $this->attributes
        ));
        if (isset($searchVariables[$this->field])) {
            $field->setValue($searchVariables[$this->field]);
        }
        return $field;

    }

    public function searchQuery($searchVariables)
    {
        if (isset($searchVariables[$this->field]) && $searchVariables[$this->field] !== '') {
            if ($searchVariables[$this->field]) {
                return '`' . $this->field . '`';
            } else {
                return 'not `' . $this->field . '`';
            }

        }
        return null;
    }
}
