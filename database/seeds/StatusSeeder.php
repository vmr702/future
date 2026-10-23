<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class StatusSeeder extends AbstractSeed
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $statuses = [
            [
                'name' => 'Не выполнена',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Выполнена',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];         

        $this->table('statuses')->insert($statuses)->save();
    }
}
