<?php

namespace Construct\Lib;




class Random
{

    public static function string($length)
    {
        return substr(sha1(rand()), 0, $length);
    }


}
