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

class UpperCase implements \Construct\Internal\Grid\Model\Transformation {
    public function transform($value, $options = [])
    {

        if (is_array($value)) {
            $answer = [];
            foreach($value as $item) {
                $answer[] = mb_strtoupper($item);
            }
            return $answer;
        }

        return mb_strtoupper($value);
    }
}
