<?php

namespace App\Exceptions;

use Exception;

class UnauthorisedExpenseAccessException extends Exception
{
    protected $message = "You are not authorized to access this expense.";
}
