<?php

namespace App\Exceptions\Todo;

use Exception;

class TodoNotFoundException extends Exception
{
    protected $message = 'Todo not found';
    protected $code = 404;
}