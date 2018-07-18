<?php
/**
 * @package   construct
 */


namespace Construct\Internal\Update;


class PublicController extends \Construct\Controller
{
    public function index()
    {
        Model::runMigrations();
        return new \Construct\Response\Json(array('success' => 1));
    }
}
