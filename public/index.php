<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\TaskController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

require __DIR__ . '/../config/database.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("base");
    return $response;
});

$app->get('/api/tasks', TaskController::class . ':getList');
$app->get('/api/tasks/{id}', TaskController::class . ':getOne');
$app->post('/api/tasks', TaskController::class . ':add');
$app->put('/api/tasks/{id}', TaskController::class . ':update');
$app->delete('/api/tasks/{id}', TaskController::class . ':delete');

$app->run();
