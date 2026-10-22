<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Task;

class TaskController
{
    public function __construct()
    {
        
    }

    // public function check(Request $request, Response $response): Response
    // {
    //     $response->getBody()->write(json_encode(['status' => 'ok']));
    //     return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    // }

    public function getList(Request $request, Response $response): Response
    {
        $tasks = Task::all();
        $response->getBody()->write($tasks->toJson());
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        // ...
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function add(Request $request, Response $response): Response
    {
        // ...
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        // ...
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        // ...
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
