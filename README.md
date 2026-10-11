<p align="center">
    <img src="public/images/logo.svg" width="96" alt="Cool School Hub logo">
</p>

# Cool School Hub

A web application for a language school, built with Laravel 13. Teachers publish their courses, students enroll in them, and an admin manages the school.

## What it does

- **Visitors** see the school's course offer on the home page: language, level and schedule.
- **Students** browse the courses they are not in yet, enroll and unenroll, and see their own courses on "My courses".
- **Teachers** create courses and edit or delete their own. On each of their courses they see the list of enrolled students.
- **The admin** can edit or delete any course, choose the teacher of a course, remove a student from a course, edit users and promote a user to teacher.

Every new account starts as a student. Only the admin can make someone a teacher.

## Installation

Requirements: PHP 8.4 and Composer. No Node.js is needed, because Tailwind CSS and Alpine.js are loaded from a CDN.

```bash
git clone https://github.com/tonygallo85/csh.git
cd csh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

Then open http://localhost:8000. With Laravel Herd, skip `php artisan serve` and open http://csh.test.

## Test accounts

`php artisan migrate:fresh --seed` creates these accounts. The password of every seeded user is `password`.

| Role    | Email             |
| ------- | ----------------- |
| Admin   | `admin@admin.com` |
| Teacher | `teacher@csh.com` |
| Student | `student@csh.com` |

It also creates 10 more students, 3 more teachers and 8 courses in total, with 2 enrollments per student.

## Where to find things

| What                    | Where                                                        |
| ----------------------- | ------------------------------------------------------------ |
| Models                  | `app/Models`: `User`, `Course`, `Enrollment`                 |
| Controllers             | `app/Http/Controllers`                                       |
| Full CRUD (7 methods)   | `CourseController`, registered with `Route::resource`        |
| Authorization rules     | `app/Policies` and `app/Http/Middleware/EnsureUserIsAdmin.php` |
| Routes                  | `routes/web.php`                                             |
| Views                   | `resources/views`                                            |
| Migrations, factories, seeder | `database`                                             |
| Tests                   | `tests/Feature`                                              |

The design decisions, the data model and the full list of routes are in [PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md). A short map of the pages is in [structure.txt](structure.txt).

## Tests

```bash
php artisan test
```

## Credits

Built on the educational Laravel starter pack by Nico Deblauwe (`ndeblauw/starterpack`), which provides Laravel Breeze authentication, the layouts and the form components.
