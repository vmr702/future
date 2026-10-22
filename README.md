# Запуск миграций
- docker compose exec php vendor/bin/phinx migrate -c phinx.php

# Наполнение категорий тестовыми данными
- docker compose exec php vendor/bin/phinx seed:run -c phinx.php -s CategorySeeder

# Наполнение задач тестовыми данными
- docker compose exec php vendor/bin/phinx seed:run -c phinx.php -s TaskSeeder