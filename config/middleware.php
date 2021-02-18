<?php
declare(strict_types=1);

use App\Http\Exception\Handler;
use App\Model\User;
use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->setBasePath("/simplechat/public");

    $errorMiddleware =$app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(
        new Handler(
            $app->getResponseFactory()
        )
    );

    $app->add(
        new JwtAuthentication([
            "ignore"=>["/simplechat/public/auth/register", "/simplechat/public/auth/login", "/simplechat/public/test"],
            "secret"=> getenv('JWT_SECRET_KEY'),
            "before" => function ($request, $arguments) {
                $id = User::query()->where('uuid', $arguments["decoded"]["jti"])->first()->id;
                return $request->withAttribute('id', $id);
            },
            "error"=> function ($response, $arguments) {
                $data["success"] = false;
                $data["response"] = $arguments["message"];
                $data["status_code"] = "401";

                return $response->withHeader("Content-Type", "application/json")
                    ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        ])
    );
};
