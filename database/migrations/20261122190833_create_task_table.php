<?php
use Phinx\Migration\AbstractMigration;

final class CreateTaskTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('tasks')
            ->addColumn('title', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'null' => true,
            ])
            ->addColumn('due_at', 'datetime', [
                'null' => true,
            ])
            ->addColumn('status_id', 'integer', [
                'null' => false,
                'default' => 1,
            ])
            ->addColumn('priority', 'string', [
                'limit' => 20,
                'default' => 'medium',
                'null' => false,
            ])
            ->addColumn('category_id', 'integer', [
                'null' => true,
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => true,
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
            ])
            ->addForeignKey('category_id', 'categories', 'id', [
                'delete' => 'SET_NULL',
            ])
            ->addForeignKey('status_id', 'statuses', 'id', [
                'delete' => 'SET_NULL',
            ])
            ->create();
    }
}
