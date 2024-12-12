<?php

namespace App\Exceptions;

use Exception;

class UserExpenseNotFoundException extends Exception
{
    protected $message = 'User expense not found.';
}
