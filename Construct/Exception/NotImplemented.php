<?php


namespace Construct\Exception;


/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
class NotImplemented extends \Construct\Exception
{

    public function __construct($message = null, $data = null, $code = 0, \Exception $previous = null)
    {
        if ($message === null) {
            $message = 'Not implemented.';
        }
        parent::__construct($message, $code, $previous);
    }


}
