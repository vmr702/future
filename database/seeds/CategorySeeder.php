<?php
use Phinx\Seed\AbstractSeed;

class CategorySeeder extends AbstractSeed
{

    /**
     * Заполняет таблицу категорий начальными данными
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            [
                'name' => 'Работа',
                'description' => 'Работа',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Дом',
                'description' => 'Дом',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Личное',
                'description' => 'Личное',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->table('categories')->insert($categories)->save();
    }
}
