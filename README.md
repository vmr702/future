# Future Task APP
## Описание
CRUD приложение задач

## Запуск (важен порядок)
- docker compose run --rm --build php composer install
- docker compose up -d --build
- docker compose exec php vendor/bin/phinx migrate -c phinx.php
- docker compose exec php vendor/bin/phinx seed:run -c phinx.php -s CategorySeeder
- docker compose exec php vendor/bin/phinx seed:run -c phinx.php -s StatusSeeder
- docker compose exec php vendor/bin/phinx seed:run -c phinx.php -s TaskSeeder

## Запуск тестов
- docker compose exec php vendor/bin/phpunit tests

## Документация
Будет доступна по ссылки типа localhost:8080/docs
