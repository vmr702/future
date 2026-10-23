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
$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("<a href='/docs'>Docs</a>");
    return $response;
});

$app->get('/docs', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/../public/swagger.html');
    $response->getBody()->write($html);
    return $response;
});

$app->get('/openapi.json', function (Request $request, Response $response) {
    $json = file_get_contents(__DIR__ . '/../openapi.json');
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});

// API
$app->get('/api/tasks', TaskController::class . ':getList');
$app->get('/api/tasks/{id}', TaskController::class . ':getOne');
$app->post('/api/tasks', TaskController::class . ':add');
$app->put('/api/tasks/{id}', TaskController::class . ':update');
$app->delete('/api/tasks/{id}', TaskController::class . ':delete');

$app->run();
