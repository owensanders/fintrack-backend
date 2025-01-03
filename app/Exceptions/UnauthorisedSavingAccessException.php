<?php

namespace App\Exceptions;

use Exception;

class UnauthorisedSavingAccessException extends Exception
{
    protected $message = "You are not authorised to access this saving.";
}
