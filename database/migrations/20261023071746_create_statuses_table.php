<?php
use Phinx\Migration\AbstractMigration;

final class CreateStatusesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('statuses')
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'null' => true,
            ])
            ->addColumn('created_at', 'datetime', [
                'null' => true,
            ])
            ->addColumn('updated_at', 'datetime', [
                'null' => true,
            ])
            ->create();
    }
}
