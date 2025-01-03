<?php

namespace App\Exceptions;

use Exception;

class UserSavingNotFoundException extends Exception
{
    protected $message = 'User saving not found.';
}
