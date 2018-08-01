<?php

namespace Plugin\PluginCreator;


class Event
{
    public static function ipBeforeController()
    {
        //Add CSS, JS, set block content
        //ipAddCss('assets/application.css');
        ipAddJs('assets/app.js');
    }

}
