<?php
declare(strict_types=1);

namespace App\Http;

class CustomResponse
{
    public static function generate($success, $response, $responseMessage)
    {
        $responseMessage = json_encode(["success"=>$success,"response"=>$responseMessage]);
        $response->getBody()->write($responseMessage);
        return $response->withHeader("Content-Type", "application/json");
    }
}
