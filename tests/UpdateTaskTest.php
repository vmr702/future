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

final class UpdateTaskTest extends TestCase
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

    public function test_update_task_with_200_status(): void
    {
        $taskData = Task::create([
            'title' => 'Тестовая задача',
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::MEDIUM->value,
        ]);

        $updatedTaskData = [
            'title' => 'Измененный заголовок задачи',
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::HIGH->value,
        ];

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($updatedTaskData);

        $response = (new TaskController())->update(
            $request, 
            new Response(),
            ['id' => $taskData->id]
        );

        self::assertSame(200, $response->getStatusCode());

        $updatedTask = Task::findOrFail($taskData->id);
        self::assertSame('Измененный заголовок задачи', $updatedTask->title);
        self::assertSame(TaskPriority::HIGH, $updatedTask->priority);
    }

    public function test_update_task_not_found_with_404_status(): void
    {
        $response = (new TaskController())->update(
            $this->createStub(ServerRequestInterface::class), 
            new Response(),
            ['id' => '9999999']
        );

        self::assertSame(404, $response->getStatusCode());
    }

    public function test_update_task_with_422_status(): void
    {
        $taskData = Task::create([
            'title' => 'Тестовая задача',
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::MEDIUM->value,
        ]);

        $updatedTaskData = [
            'title' => null,
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::HIGH->value,
        ];

        $request = $this->createStub(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn($updatedTaskData);

        $response = (new TaskController())->update(
            $request, 
            new Response(),
            ['id' => $taskData->id]
        );

        self::assertSame(422, $response->getStatusCode());
    }
}
