<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Grid\Model\Field;


class Text extends \Construct\Internal\Grid\Model\Field
{


    public function createField()
    {
        $field = new \Construct\Form\Field\Text(array(
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
        if (isset($postData[$this->field])) {
            return array($this->field => $postData[$this->field]);
        }
        return [];
    }

    public function updateField($curData)
    {
        $field = new \Construct\Form\Field\Text(array(
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
        return array($this->field => $postData[$this->field]);
    }


    public function searchField($searchVariables)
    {
        $field = new \Construct\Form\Field\Text(array(
            'label' => $this->label,
            'name' => $this->field,
            'layout' => $this->layout,
            'attributes' => $this->attributes
        ));
        if (!empty($searchVariables[$this->field])) {
            $field->setValue($searchVariables[$this->field]);
        }
        return $field;
    }

    public function searchQuery($searchVariables)
    {
        if (isset($searchVariables[$this->field]) && $searchVariables[$this->field] !== '') {
            return ' `' . $this->field . '` like ' . ipDb()->getConnection()->quote(
                '%' . $searchVariables[$this->field] . '%'
            ) . '';
        }
        return null;
    }
}
