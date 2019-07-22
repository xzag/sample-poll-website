<?php

namespace app\exceptions;

class BadRequestException extends HttpException
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, 400, $code = 0, $previous);
    }
}
