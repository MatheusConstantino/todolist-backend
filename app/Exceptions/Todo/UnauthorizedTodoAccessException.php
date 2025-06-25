<?php

namespace App\Exceptions\Todo;

use Exception;

class UnauthorizedTodoAccessException extends Exception
{
    protected $message = 'Unauthorized access to todo';
    protected $code = 403;
}