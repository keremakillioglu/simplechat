<?php

namespace App\Service;

use Firebase\JWT\JWT;

class TokenService
{
    public static function generate($uuid)
    {
        $now = time();
        $future = strtotime('+1 hour', $now);
        $secretKey = getenv('JWT_SECRET_KEY');
        $payload = [
            "jti"=>$uuid,
            "iat"=>$now,
            "exp"=>$future,
        ];

        return JWT::encode($payload, $secretKey, "HS256");
    }
}
