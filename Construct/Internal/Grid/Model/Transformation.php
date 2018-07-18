<?php
/**
 * @package   construct
 */


/**
 * Created by PhpStorm.
 * User: mangirdas
 * Date: 8/10/14
 * Time: 10:53 PM
 */

namespace Construct\Internal\Grid\Model;


interface Transformation
{
    public function transform($value, $options = []);
}
