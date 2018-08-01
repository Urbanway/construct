<?php

namespace Plugin\#NAME#;


class Event
{
    public static function ipBeforeController()
    {
        //Add CSS, JS, set block content
        ipAddCss('assets/#NAMELOWER#.css');
        ipAddJs('assets/#NAMELOWER#.js');
    }

}
