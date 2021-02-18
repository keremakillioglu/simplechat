<?php
declare(strict_types=1);

namespace App\Http\Exception;

use App\Http\CustomResponse;
use App\Http\Exception\ValidationException;
use Psr\Http\Message\ServerRequestInterface as Request;

use ReflectionClass;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\ResponseFactory;
use Throwable;

class Handler
{
    protected $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory =$responseFactory;
    }

    public function __invoke(Request $request, Throwable $exception)
    {
        if (method_exists($this, $handler='handle' . (new ReflectionClass($exception))->getShortName())) {
            return $this->{$handler}($request, $exception);
        }
        throw $exception;
    }

    public function handleHttpNotFoundException(Request $request, Throwable  $exception)
    {
        $response = $this->responseFactory->createResponse();
        return CustomResponse::generate(false, $response, "Page not found")->withStatus(404);
    }

    public function handleValidationException(Request $request, Throwable  $exception)
    {
        $response = $this->responseFactory->createResponse();
        return CustomResponse::generate(false, $response, $exception->getErrors())->withStatus(400);
    }

    public function handleAPIException(Request $request, Throwable  $exception)
    {
        $response = $this->responseFactory->createResponse();
        return CustomResponse::generate(false, $response, $exception->getErrorMsg())->withStatus(400);
    }

    public function handleHttpMethodNotAllowedException(Request $request, Throwable  $exception)
    {
        $response = $this->responseFactory->createResponse();
        return CustomResponse::generate(false, $response, "Method Not Allowed")->withStatus(405);
    }

    public function handleModelNotFoundException(Request $request, Throwable  $exception)
    {
        $response = $this->responseFactory->createResponse();
        return CustomResponse::generate(false, $response, "Record Not Found")->withStatus(400);
    }
}
