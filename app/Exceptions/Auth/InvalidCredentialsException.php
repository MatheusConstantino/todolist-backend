<?php

namespace App\Exceptions\Auth;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $message = 'Invalid credentials provided';
    protected $code = 401;
}