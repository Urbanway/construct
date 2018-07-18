<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Grid\Model\Field;


class Url extends \Construct\Internal\Grid\Model\Field\Text
{

    public function createField()
    {
        $field = new \Construct\Form\Field\Url(array(
            'label' => $this->label,
            'name' => $this->field,
            'layout' => $this->layout,
            'attributes' => $this->attributes
        ));
        $field->setValue($this->defaultValue);
        return $field;
    }

    public function updateField($curData)
    {
        $field = new \Construct\Form\Field\Url(array(
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

}
