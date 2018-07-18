<?php
/**
 * @package construct

 *
 */
namespace Construct\Internal\Content\Widget\Form;




class Model{

    static $fields;


    /**
     * @return \Construct\Internal\Content\FieldType[]
     */
    public static function getAvailableFieldTypes() {
        if (!self::$fields) {
            self::collectFieldTypes();
        }

        return self::$fields;
    }

    /**
     * @param $key
     * @return \Construct\Internal\Content\FieldType
     */
    public static function getFieldType($key) {
        if (!self::$fields) {
            self::collectFieldTypes();
        }
        if(isset(self::$fields[$key])) {
            return self::$fields[$key];
        }

        return false;
    }

    /**
     * @return \Construct\Form\Field[]
     */
    private static function collectFieldTypes() {
        self::$fields = ipFilter('ipWidgetFormFieldTypes', []);
    }
}
