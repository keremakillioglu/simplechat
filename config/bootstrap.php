<?php
declare(strict_types=1);

use App\Http\Exception\APIException;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Dotenv\Exception\InvalidPathException;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv(__DIR__ . '/../'))->load();
} catch (InvalidPathException $e) {
    throw new APIException("Dotenv Error");
}

$container = new DI\Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

(require_once __DIR__ . '/middleware.php')($app);
require_once __DIR__.'/settings.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/routes.php';
