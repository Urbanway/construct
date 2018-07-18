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

class Trim implements \Construct\Internal\Grid\Model\Transformation {
    public function transform($value, $options = [])
    {

        if (is_array($value)) {
            $answer = [];
            foreach($value as $item) {
                $answer[] = trim($item);
            }
            return $answer;
        }

        return trim($value);
    }
}
