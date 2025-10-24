<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedPermissionException extends Exception
{
    public function __construct(string $message = 'You do not have permission to perform this action.', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
