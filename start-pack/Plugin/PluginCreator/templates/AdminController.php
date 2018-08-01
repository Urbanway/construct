<?php

namespace Plugin\#NAME#;


class AdminController
{
     public function index()
    {
        $config = array(
            'title' => '#NAME#',
            'table' => '#NAMELOWER#',
            'sortField' => '#NAMELOWER#Order',
            'createPosition' => 'top',
            'pageSize' => 5,
            'fields' => array(
                #FIELDS#
            )
        );
        return crud($config);
    }

}
