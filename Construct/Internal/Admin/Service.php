<?php
/**
 * @package   construct
 */

namespace Construct\Internal\Admin;

class Service
{
    public static function isSafeMode()
    {
        return \Construct\Internal\Admin\Model::isSafeMode();

    }

    public static function adminId()
    {
        return Model::getUserId();
    }

    public static function setAdminLogin($username)
    {
        $model = Model::instance();
        $model->setAdminLogin($username);
    }

}
