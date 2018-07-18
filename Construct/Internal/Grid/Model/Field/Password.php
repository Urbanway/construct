<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Grid\Model\Field;


class Password extends \Construct\Internal\Grid\Model\Field
{


    public function createField()
    {
        $field = new \Construct\Form\Field\Password(array(
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
        $field = new \Construct\Form\Field\Password(array(
            'label' => $this->label,
            'name' => $this->field,
            'layout' => $this->layout,
            'attributes' => $this->attributes,
            'value' => ''
        ));
        return $field;
    }

    public function updateData($postData)
    {
        if (empty($postData[$this->field])) {
            return [];
        }
        return array($this->field => $this->passwordHash($postData[$this->field]));
    }

    protected function passwordHash($password)
    {
        $stretching = ipGetOption('Admin.passwordStretchingIterations', 8);
        $hasher = new \Construct\Lib\PasswordHash($stretching, ipGetOption('Construct.portableAdminHashes', true));
        return $hasher->HashPassword($password);
    }


    public function searchField($searchVariables)
    {
        return false;
    }

    public function searchQuery($searchVariables)
    {
        return '';
    }

    public function preview($recordData)
    {
        return '';
    }
}
