<?php
declare(strict_types=1);

namespace App\Http\Exception;

use Exception;

class ValidationException extends Exception
{
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
