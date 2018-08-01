<?php

namespace Construct\Exception;

/**
 * This exception does not extends Construct\Exception!
 *
 * Purpose of this exception is to show error on the line database method was called.
 *
 * @package Construct\Exception
 */
class Db extends \PDOException
{
    public function __construct($message = "", $code = 0, \PDOException $previous = null)
    {
        $this->message = $message;
        if ($previous) {
            $this->message = $previous->message;
            $this->code = $previous->code;
            $this->file = $previous->file;
            $this->line = $previous->line;
            $this->trace = $previous->getTrace();
            $this->previous = $previous;
        }

        $backtrace = debug_backtrace();

        // We need directory separator for Windows
        $constructQueryPath = 'Construct' . DIRECTORY_SEPARATOR . 'Db.php';
        $pathLength = strlen($constructQueryPath);

        // We usually want exception to show error in the code that uses Db class
        foreach ($backtrace as $info) {
            if (substr($info['file'], -$pathLength) != $constructQueryPath) {
                $this->file = $info['file'];
                $this->line = $info['line'];
                break;
            }
        }
    }
}
