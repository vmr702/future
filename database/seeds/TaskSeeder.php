<?php
use Phinx\Seed\AbstractSeed;

class TaskSeeder extends AbstractSeed
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $tasks = [];

        for ($i = 1; $i <= 30; $i++) 
        {
            $tasks[] = [
                'title' => "Задача $i",
                'description' => "Описание задачи $i",
                'category_id' => rand(1, 3),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->table('tasks')->insert($tasks)->save();
    }
}
