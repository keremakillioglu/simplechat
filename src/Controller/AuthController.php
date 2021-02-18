<?php

namespace App\Controller;

use App\Http\CustomResponse;
use App\Http\Exception\APIException;
use App\Model\User;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Service\TokenService;

class AuthController extends Controller
{
    public function test(Request $request, Response $response, $args)
    {
        return CustomResponse::generate(true, $response, "Application is running")->withStatus(200);
    }

    public function register(Request $request, Response $response, $args)
    {
        // request validation
        $data= $this->validate($request, [
            'username' => ['required'],
            'password' => ['required'],
        ]);

        //db validation
        if (User::query()->where('username', '=', ($data['username']))->exists()) {
            throw new APIException("User already exists!");
        }

        User::query()->create([
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'uuid'=> Uuid::uuid4()
        ]);

        // return response
        return CustomResponse::generate(true, $response, "User successfully created!")->withStatus(201);
    }

    public function login(Request $request, Response $response, $args)
    {
        // request validation
        $data= $this->validate($request, [
            'username' => ['required'],
            'password' => ['required'],
        ]);

        //db validation
        $user = User::query()->where('username', '=', ($data['username']))->first();

        if (password_verify($data['password'], $user->password) == false) {
            throw new APIException("Password is not correct!");
        }

        $token = TokenService::generate($user->uuid);
        return CustomResponse::generate(true, $response, $token)->withStatus(200);
    }

    public function viewUsers(Request $request, Response $response, $args)
    {
        $users = User::query()->orderBy('created_at', 'desc')->get(['username', 'created_at']);
        return CustomResponse::generate(true, $response, $users)->withStatus(200);
    }
}
