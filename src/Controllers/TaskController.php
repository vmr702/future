<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Enums\TaskPriority;
use App\Models\Category;
use App\Models\Status;
use App\Models\Task;

class TaskController
{
    public function __construct() {}

    /**
     * Получение списка задач с пагинацией, поиском и сортировкой
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getList(Request $request, Response $response): Response
    {
        // получаем параметры запроса
        $params = $request->getQueryParams();

        // выделим страницу и кол-во записей на ней
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $onPage = isset($params['on_page']) ? (int)$params['on_page'] : 10;
        // получим подстроку для поиска по названию задачи
        $search = trim((string) ($params['search'] ?? ''));
        // получим столбцы для сортировки
        $sorts = explode(',', (string) ($params['sort'] ?? 'created_at'));

        $query = Task::query()
            ->with('category')
            ->with('status');

        // приминяем поиск если есть
        if ($search) 
        {
            $query->where('title', 'ILIKE', '%' . $search . '%');
        }

        // применяем сортировку если есть
        foreach ($sorts as $column)
        {
            if (in_array(trim($column), Task::SORTABLE_COLUMNS)) 
            {
                $query->orderBy($column);
            }
        }

        $tasks = $query
            ->orderBy('id')
            ->paginate($onPage, ['*'], 'page', $page);
            
        $response->getBody()->write($tasks->toJson());

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Получение одной запси по id
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function getOne(Request $request, Response $response, array $args): Response
    {
        $task = Task::with('category', 'status')->find($args['id']);

        if (!$task)
        {
            $response->getBody()->write(json_encode(['error' => 'Задача не найдена']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write($task->toJson());
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    /**
     * Создание новой задачи
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function add(Request $request, Response $response): Response
    {
        $data = $this->validateTask($request->getParsedBody());

        $params = $data['params'];
        $errors = $data['errors'];

        // Если есть ошибки - вызодим с 422
        if ($errors !== []) 
        {
            $response
                ->getBody()
                ->write(json_encode(
                    ['errors' => $errors], 
                    JSON_UNESCAPED_UNICODE
                ));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        $task = Task::create([
            'title' => trim($params['title']),
            'description' => $params['description'],
            'due_at' => $params['due_at'],
            'status_id' => $params['status_id'],
            'priority' => $params['priority'],
            'category_id' => $params['category_id'],
        ])->load(['category', 'status']);

        $response->getBody()->write(json_encode(
            ['id' => $task->id, 'message' => 'Задача успешно создана'], 
            JSON_UNESCAPED_UNICODE
        ));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * Обновление задачи по id
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $task = Task::find($args['id']);

        if (!$task) 
        {
            $response->getBody()->write(json_encode(['error' => 'Задача не найдена'], JSON_UNESCAPED_UNICODE));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $input = $request->getParsedBody();
        $data = $this->validateTask($input);

        if ($data['errors'] !== []) 
        {
            $response->getBody()->write(json_encode(
                ['errors' => $data['errors']],
                JSON_UNESCAPED_UNICODE
            ));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        $task->update($data['params']);
        $task->load(['category', 'status']);

        $response->getBody()->write(json_encode(['message' => 'Задача успешно обновлена']));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Удаление задачи по id
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $task = Task::find($args['id']);

        if (!$task) 
        {
            $response->getBody()->write(json_encode(['error' => 'Задача не найдена']));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $task->delete();

        $response->getBody()->write(json_encode(['message' => 'Задача успешно удалена']));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * Валидация данных задачи
     * @param $input parsed data
     * @return array ['params' => [...], 'errors' => [...]]
     */
    private function validateTask($input): array
    {
        $errors = [];
        $params = [];

        if (!is_array($input)) 
        {
            $input = [];
            $errors['body'] = 'invalid JSON body';
        }

        // Получаем параметры с дефолтными значениями
        $params['title'] = $input['title'] ?? null;
        $params['description'] = $input['description'] ?? null;
        $params['due_at'] = $input['due_at'] ?? null;
        $params['priority'] = $input['priority'] ?? TaskPriority::MEDIUM->value;
        $params['category_id'] = $input['category_id'] ?? 1;
        $params['status_id'] = $input['status_id'] ?? 1;

        if (!is_string($params['title']) || trim($params['title']) === '' || mb_strlen(trim($params['title'])) > 255) 
        {
            $errors['title'] = 'Название обязательно и должно содержать не более 255 символов';
        }

        if ($params['description'] !== null && !is_string($params['description'])) 
        {
            $errors['description'] = 'Описание должно быть строкой или null';
        }

        if ($params['due_at'] !== null && !is_string($params['due_at'])) 
        {
            $errors['due_at'] = 'Срок выполнения должен быть датой-временем или null';
        } 
        elseif (is_string($params['due_at'])) 
        {
            try 
            {
                new \DateTimeImmutable($params['due_at']);
            } 
            catch (\Exception $exception) 
            {
                $errors['due_at'] = 'Укажите корректную дату и время';
            }
        }

        if (!is_string($params['priority']) || TaskPriority::tryFrom($params['priority']) === null) 
        {
            $errors['priority'] = 'Приоритет должен быть low, medium или high';
        }

        if (!Category::find($params['category_id']))
        {
            $errors['category_id'] = 'Указанная категория не найдена';
        }

        if (!Status::find($params['status_id'])) 
        {
            $errors['status_id'] = 'Указанный статус не найден';
        }

        return ['params' => $params, 'errors' => $errors];
    }
}
