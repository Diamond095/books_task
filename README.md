# Task Manager — simple PHP CRUD project

Структура проекта:
- config.php — настройки подключения к БД (добавлен в .gitignore).
- index.php — отображение списка задач / книг.
- add.php — форма и обработчик добавления.
- edit.php — форма и обработчик редактирования.
- delete.php — скрипт удаления (редиректит на index.php).
- update_status.php — скрипт смены статуса (редиректит на index.php).
- db_init.sql — SQL для создания БД и таблицы.

Инструкция:
1. Подключите MySQL / MariaDB.
2. Выполните `db_init.sql` (например: `mysql -u root -p < db_init.sql`).
3. Настройте `config.php` — установите реальные значения $DB_USER и $DB_PASS.
4. Загрузите проект в ваш PHP-сервер (htdocs / www).
5. Откройте index.php в браузере.

Защита:
- Используются подготовленные выражения (prepared statements).
- При выводе данных используется htmlspecialchars() для защиты от XSS.
# books_task
