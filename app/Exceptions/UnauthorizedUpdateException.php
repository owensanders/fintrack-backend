<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedUpdateException extends Exception
{
    protected $message = 'You are not authorized to update this profile.';
    protected $code = 403;

    public function __construct($message = null, $code = 403)
    {
        if ($message) {
            $this->message = $message;
        }
        $this->code = $code;

        parent::__construct($this->message, $this->code);
    }
}
