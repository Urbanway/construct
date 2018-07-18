<?php
/**
 * @package   construct
 *
 *
 */

namespace Construct\Internal\Transform;

class None extends \Construct\Transform
{
    public function transform($sourceFile, $destinationFile)
    {
        copy($sourceFile, $destinationFile);
    }

}
