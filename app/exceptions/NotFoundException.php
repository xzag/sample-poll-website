<?php

namespace app\exceptions;

class NotFoundException extends HttpException
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, 404, $code = 0, $previous);
    }
}
