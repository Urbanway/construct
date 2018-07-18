<?php
/**
 * @package   construct
 */


namespace Construct\Internal\Grid\Model\Transformation;

/**
 * Created by PhpStorm.
 * User: mangirdas
 * Date: 8/10/14
 * Time: 10:54 PM
 */

class NullIfEmpty implements \Construct\Internal\Grid\Model\Transformation {
    public function transform($value, $options = [])
    {
        if (empty($value)) {
            return null;
        }
        return $value;
    }
}
