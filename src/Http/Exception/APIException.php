<?php
declare(strict_types=1);

namespace App\Http\Exception;

use Exception;

class APIException extends Exception
{
    protected $errorMsg;

    public function __construct($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
}
