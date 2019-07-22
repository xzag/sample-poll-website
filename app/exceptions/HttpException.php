<?php

namespace app\exceptions;

use Throwable;

class HttpException extends Exception
{
    private $status;

    public function __construct($message = "", $status = 500, $code = 0, Throwable $previous = null)
    {
        $this->status = $status;
        parent::__construct($message, $code, $previous);
    }

    public function getStatus()
    {
        return $this->status;
    }
}