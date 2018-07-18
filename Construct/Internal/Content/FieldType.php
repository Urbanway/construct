<?php
/**
 * @package construct
 *
 */
namespace Construct\Internal\Content;


class FieldType
{

    protected $key;
    protected $fieldClass;
    protected $title;
    protected $jsOptionsFunction;
    protected $jsSaveOptionsFunction;
    protected $jsOptionsHtml;

    /**
     *
     * $jsOptionsFunction and $jsSaveOptionsFunction should both present or both be skipped.
     * @param string $key
     * @param string $fieldClass
     * @param string $title
     * @param string $jsOptionsInitFunction
     * @param string $jsOptionsSaveFunction
     * @param array $jsOptionsHtml
     */
    public function __construct(
        $key,
        $fieldClass,
        $title,
        $jsOptionsInitFunction = null,
        $jsOptionsSaveFunction = null,
        $jsOptionsHtml = null
    ) {
        $this->key = $key;
        $this->fieldClass = $fieldClass;
        $this->title = $title;
        $this->jsOptionsInitFunction = $jsOptionsInitFunction;
        $this->jsOptionsSaveFunction = $jsOptionsSaveFunction;
        $this->jsOptionsHtml = $jsOptionsHtml;
    }

    /**
     *
     * Create field that could be used in form class.
     * @param array $fieldData will be passed to field constructor
     * @throws \Construct\Exception\Content
     * @throws \Exception
     * @return \Construct\Form\Field
     */
    public function createField($fieldData)
    {
        if (!isset($fieldData['label'])) {
            $fieldData['label'] = '';
        }
        if (!isset($fieldData['name'])) {
            throw new \Exception('Field name not specified');
        }

        $fieldOptions = array(
            'label' => $fieldData['label'],
            'name' => $fieldData['name'],
            'required' => $fieldData['required'],
            'options' => $fieldData['options']
        );

        $fieldClass = $this->getFieldClass();

        if (isset($fieldData['options'])) {
            switch ($fieldClass) {
                case '\Construct\Form\Field\Select':
                case '\Construct\Form\Field\Radio':
                case '\Construct\Form\Field\Checkboxes':
                    $selectValues = [];
                    if (isset($fieldData['options']['list']) && is_array($fieldData['options']['list'])) {
                        foreach ($fieldData['options']['list'] as $option) {
                            if (is_string($option)) {
                                $selectValues[] = array($option, $option);
                            }

                        }
                    }
                    $fieldOptions['values'] = $selectValues;
                    break;
                case '\Construct\Form\Field\Confirm':
                    if (isset($fieldData['options']['text']) && is_string($fieldData['options']['text'])) {
                        $fieldOptions['text'] = $fieldData['options']['text'];
                    }
                    break;
                default:
                    //other classes do not have their options. If you write your custom field type, extend this class and change this behaviour
                    break;
            }
        }

        if (!class_exists($fieldClass)) {
            throw new \Construct\Exception\Content('Required field type class doesn\'t exist. ' . esc(
                $fieldClass
            ), array('fieldClass' => $fieldClass));
        }
        $field = new $fieldClass($fieldOptions);

        if (isset($fieldData['required']) && $fieldData['required']) {
            $field->addValidator('Required');
        }

        return $field;

    }

    public function getKey()
    {
        return $this->key;
    }

    public function getFieldClass()
    {
        return $this->fieldClass;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getJsOptionsInitFunction()
    {
        return $this->jsOptionsInitFunction;
    }

    public function getJsOptionsSaveFunction()
    {
        return $this->jsOptionsSaveFunction;
    }

    public function getJsOptionsHtml()
    {
        return $this->jsOptionsHtml;
    }
}
