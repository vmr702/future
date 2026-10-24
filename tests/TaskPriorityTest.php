<?php

use App\Enums\TaskPriority;
use PHPUnit\Framework\TestCase;

final class TaskPriorityTest extends TestCase
{
    public function test_priority_values_are_defined(): void
    {
        $values = array_map(
            fn (TaskPriority $priority) => $priority->value,
            TaskPriority::cases()
        );

        self::assertSame(['low', 'medium', 'high'], $values);
    }
}
