<?php
declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\MessageController;
use Slim\Routing\RouteCollectorProxy;

$app->get('/test', AuthController::class . ':test');
$app->get('/users', AuthController::class . ':viewUsers');

$app->group("/auth", function (RouteCollectorProxy $group) {
    $group->post('/register', AuthController::class . ':register');
    $group->post('/login', AuthController::class . ':login');
});

$app->group("/messages", function (RouteCollectorProxy $group) {
    $group->post('/delete', MessageController::class . ':delete');
    $group->post('/send', MessageController::class . ':sendMessage');
    $group->post('/read/{id}', MessageController::class . ':readMessage');

    $group->get('/all', MessageController::class . ':getAllMessages');
    $group->get('/from', MessageController::class . ':getOutgoing');
    $group->get('/to', MessageController::class . ':getIncoming');

    $group->get('/{uuid}', MessageController::class . ':getMessageFromId');
    $group->get('/with/{uuid}', MessageController::class . ':getMessagesWithUser');
    $group->get('/from/{uuid}', MessageController::class . ':getMessagesFromUser');
    $group->get('/to/{uuid}', MessageController::class . ':getMessagesSentToUser');
});
