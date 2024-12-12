<?php

namespace App\Exceptions;

use Exception;

class UnauthorisedExpenseAccessException extends Exception
{
    protected $message = "You are not authorised to access this expense.";
}
