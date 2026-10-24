<?php

use App\Enums\TaskPriority;
use App\Controllers\TaskController;
use App\Models\Category;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class CreateTaskTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require_once __DIR__ . '/../config/database.php';
        Capsule::connection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        Capsule::connection()->rollBack();
        parent::tearDown();
    }

    public function test_create_task_with_status_201(): void
    {
        $taskData = [
            'title' => 'Тестовая задача',
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::MEDIUM->value,
        ];

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($taskData);

        $response = (new TaskController())->add($request, new Response());

        self::assertSame(201, $response->getStatusCode());
    }

    public function test_create_task_with_status_422(): void
    {
        $taskData = [
            'title' => null, // вызовет ошибку добавления
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::MEDIUM->value,
        ];

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($taskData);

        $response = (new TaskController())->add($request, new Response());

        self::assertSame(422, $response->getStatusCode());
    }
}
