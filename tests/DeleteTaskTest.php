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

final class DeleteTaskTest extends TestCase
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

    public function test_delete_task_with_200_status(): void
    {
        $task = Task::create([
            'title' => 'Тестовая задача',
            'status_id' => '1',
            'category_id' => '1',
            'priority' => TaskPriority::MEDIUM->value,
        ]);

        $response = (new TaskController())->delete(
            $this->createStub(ServerRequestInterface::class),
            new Response(),
            ['id' => $task->id]
        );

        self::assertSame(200, $response->getStatusCode());
    }

    public function test_delete_task_with_404_status(): void
    {
        $response = (new TaskController())->delete(
            $this->createStub(ServerRequestInterface::class),
            new Response(),
            ['id' => '9999999']
        );

        self::assertSame(404, $response->getStatusCode());
    }
}
