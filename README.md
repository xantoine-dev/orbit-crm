# Orbit CRM

Laravel 12 CRM for clients, projects, and tasks with Bootstrap UI, role-based access, soft deletes/restores, activity logging, REST API (Sanctum), and CSV/PDF reporting.

## Features
- Auth (login/register) with roles: admin/staff.
- Clients → Projects → Tasks; statuses, assignee, due dates.
- Soft deletes for clients/projects/tasks + admin restore.
- Activity log for create/update/delete/status changes.
- Global search + filters (status/date/assignee) on lists.
- Reports: tasks per project/user, overdue; export CSV/PDF.
- REST API v1 (Sanctum) for clients/projects/tasks + status update.
- Seed data: admin/staff users, 2 clients, sample projects/tasks.
- Tests for auth, CRUD, assignment, status change, search.

## Stack
- PHP 8.x, Laravel 12, MySQL/MariaDB (SQLite for dev/tests)
- Sanctum, Dompdf, league/csv, Bootstrap 5 (via CDN), Vite
- PHPUnit

## Quickstart
```bash
# from repo root
cp .env.example .env
php -r "file_exists('database/database.sqlite') || (mkdir('database', 0777, true) && touch('database/database.sqlite'));"
php artisan key:generate
php artisan migrate --seed

# frontend (built via Vite; optional to rebuild)
docker run --rm -v "$PWD":/app -w /app node:20 npm install
docker run --rm -v "$PWD":/app -w /app node:20 npm run build

php artisan serve
```
- Default users: `admin@example.com` / `password`, `staff@example.com` / `password`.
- Update `.env` for MySQL/MariaDB when needed.

## API
- Auth: Bearer token via Sanctum. Create token in tinker:
  ```php
  $token = App\Models\User::first()->createToken('api')->plainTextToken;
  ```
- Base path: `/api/v1`
  - `GET /tasks` (filters: `status`, `due_before`, `due_after`)
  - `POST /tasks` (project_id, title, description?, assigned_to?, due_date?, status)
  - `PATCH /tasks/{id}/status` (status: todo|in_progress|done)
  - `GET/POST/PATCH/DELETE` for `/clients`, `/projects` similarly

## Running tests
```bash
docker run --rm -v "$PWD":/app -w /app composer php artisan test
```
(Uses sqlite in-memory.)

## Project structure
- Domain: `app/Models` (Client, Project, Task, ActivityLog, User)
- Policies: `app/Policies` (role-based access)
- Services: `ActivityLogger`, `TaskStatusService`, `ReportService`, `SearchService`
- HTTP: controllers (web/API), requests, middleware in `app/Http`
- Views: `resources/views` (Bootstrap Blade screens)
- Routes: `routes/web.php`, `routes/api.php`

## Deployment notes
- Set `APP_KEY`, DB credentials, mail settings, `APP_URL`.
- Run `php artisan migrate --force` then `php artisan config:cache route:cache view:cache`.
- If using tokens only, Sanctum config is default; adjust stateful domains if using SPA.

## License
MIT
