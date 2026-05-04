# Dseva E-Learning

Multi-tenant cybersecurity training LMS built on Laravel 9. Companies onboard employees, assign courses by department, track training progress, and issue completion certificates. Includes phishing simulation templates and Wistia/YouTube video support.

## Tech Stack

- **Backend:** Laravel 9, PHP 8.0+
- **Auth:** Laravel Sanctum, Socialite (Google OAuth)
- **Authorization:** Spatie Laravel-Permission, custom Policies
- **Database:** MySQL 8+ recommended (uses UUID primary keys); SQLite supported for tests
- **Cache/Queue:** Redis (predis), database, or file
- **Frontend:** Vite + Blade + Bootstrap admin theme

## Requirements

- PHP 8.0.2 or newer with extensions: `pdo_mysql`, `mbstring`, `openssl`, `json`, `xml`, `tokenizer`, `bcmath`
- Composer 2.x
- Node.js 16+ and npm
- MySQL 8+ (or SQLite for local development)
- Redis (optional, only if `CACHE_DRIVER=redis` or `QUEUE_CONNECTION=redis`)

## Setup

```bash
# 1. Clone and install
git clone https://github.com/bagusy/dseva-elearning.git
cd dseva-elearning
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# Edit .env — at minimum set:
#   APP_URL=http://localhost:8000
#   DB_CONNECTION=mysql
#   DB_DATABASE=dseva_elearning
#   DB_USERNAME=...
#   DB_PASSWORD=...
#
# To bootstrap an admin via seeder, also set:
#   ADMIN_EMAIL=admin@yourcompany.com
#   ADMIN_PASSWORD=ChangeMe!1   (omit to auto-generate; printed once on seed)
#   ADMIN_NAME=Administrator
#
# For Google login (optional):
#   GOOGLE_CLIENT_ID=...
#   GOOGLE_CLIENT_SECRET=...
#   GOOGLE_REDIRECT=http://localhost:8000/callback/google

# 3. Database — create the schema and seed roles + admin
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\RoleAndPermissionSeeder
php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder

# Optional: seed sample videos (only runs idempotent insert if ADMIN exists)
php artisan db:seed --class=Database\\Seeders\\VideoSeeder

# Optional (LOCAL / DEV ONLY): seed one demo user per role + a demo company
# See "Demo Accounts" section below.
php artisan db:seed --class=Database\\Seeders\\DemoUsersSeeder

# 4. Build assets
npm run build       # production
# or
npm run dev         # watch mode

# 5. Serve
php artisan serve
```

The first user created by `AdminUserSeeder` gets `ROLE_ADMIN`. New self-registered users get `ROLE_USER_ADMIN` and must onboard a company before accessing the dashboard.

## Roles

| Role | Purpose |
|---|---|
| `ADMIN` | Platform super-admin. Sees all companies, all content. Bypasses policies. |
| `SUBSCRIPTION_MANAGER` | Manages billing and other users (platform-level). |
| `COURSE_CREATOR` | Creates and manages courses + quizzes (platform-level content). |
| `CONTENT_CREATOR` | Creates non-private videos (platform-level library). |
| `USER_ADMIN` | Tenant administrator: manages company, departments, employees, custom courses, training assignments. |
| `USER_EMPLOYEE` | End user assigned to training; consumes courses and takes quizzes. |

Permissions are seeded by [`RoleAndPermissionSeeder`](database/seeders/RoleAndPermissionSeeder.php) and enforced via Spatie roles plus per-resource [Policies](app/Policies/) (Course, Quiz, Video, Department, Employee, CourseEnrollment).

## Demo Accounts (DEV ONLY)

[`DemoUsersSeeder`](database/seeders/DemoUsersSeeder.php) creates one user per role plus a demo company with default departments and one employee record, so end-to-end tenant flows can be exercised without manual setup.

```bash
php artisan db:seed --class=Database\\Seeders\\DemoUsersSeeder
```

The seeder is idempotent and **refuses to run when `APP_ENV=production`**. After it runs it writes a `DEMO_CREDENTIALS.md` file to the project root listing every demo email, role, and the shared password. That file is **`.gitignore`d** — credentials never enter git history through normal use.

To tear everything down (deletes the six demo users, the demo company and its departments, all related employee / assignment / enrollment / quiz-completion rows, and removes `DEMO_CREDENTIALS.md`):

```bash
php artisan dseva:purge-demo-users          # confirms first
php artisan dseva:purge-demo-users --force  # no prompt
```

The purge command also refuses to run in production and is safe to re-run when there is nothing to delete.

> **Do not** seed demo users on any publicly reachable host, even staging. The shared password is trivial.

## Key Routes

| Route | Purpose |
|---|---|
| `GET /home` | Dashboard (intro videos, quick stats) |
| `GET /training` | Employee's enrolled trainings, or admin's published courses |
| `GET /courses` | Course library (custom + global) |
| `GET /quiz` | Quiz library |
| `GET /videos` / `GET /videos/list` | Public video catalog / private uploader list |
| `GET /company/employees` | Tenant employee management |
| `GET /company/departments` | Tenant department management |
| `GET /dashboards` | Charts and progress overview |
| `GET /trainings/{enrollment}` | Resume training (own enrollment only) |
| `GET /trainings/{enrollment}/certificate` | Download completion certificate |
| `GET /phishing` | Phishing simulation templates |

API (Sanctum-protected, web auth):

| Route | Purpose |
|---|---|
| `GET /api/courses/{course}/videos` | Video picker for course composition |
| `GET /api/courses/{course}/quiz` | Quiz picker for course composition |

## Tests

```bash
php artisan key:generate         # if not done yet
php vendor/bin/phpunit
```

The suite uses SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`) and runs migrations + seeders fresh per test class via `RefreshDatabase`. The bundled [`BugFixSmokeTest`](tests/Feature/BugFixSmokeTest.php) verifies critical IDOR / cross-tenant / mass-assignment fixes.

## Background Jobs

The app dispatches `AssignTraining` jobs from a per-minute scheduled command:

```bash
# Run scheduler in production (cron entry)
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

The command [`assign:course`](app/Console/Commands/AssignCourse.php) is idempotent: it only re-dispatches assignments that are not yet expired and not yet fully assigned (`is_assigned` flag).

For local testing, run the scheduler manually:

```bash
php artisan schedule:work
```

Queue worker (uses `QUEUE_CONNECTION=sync` by default; set to `database` or `redis` for production):

```bash
php artisan queue:work
```

## Security Notes

- Auth endpoints (`login`, `register`, `password/email`, `password/update`) are throttled at 6 req/min per IP.
- Email verification is enforced on all dashboard routes (`verified` middleware).
- All multi-tenant resources are scoped by `company_id` via Policies; cross-tenant access returns 403.
- Mass-assignment is locked: `User.company_id` and `Course.user_id` cannot be set via request input.
- API CORS origin should be restricted in `config/cors.php` before production deploy.
- Passwords must contain uppercase, lowercase, number, and a punctuation character.

## License

Proprietary. All rights reserved.
