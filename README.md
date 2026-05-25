# Task Manager

A collaborative task management application built with [Laravel](https://laravel.com), [Livewire](https://livewire.laravel.com), and [Flux UI](https://fluxui.dev).

## Features

- **Authentication** — Login, registration, passkeys, and two-factor authentication via Laravel Fortify
- **Role-based access** — Admin and member roles with different permissions
- **Project management** — Create and manage projects with descriptions and admins
- **Team collaboration** — Add members to projects for team-based workflows
- **Task management** — Create, prioritize, and organize tasks within projects
- **Dashboard** — Central overview of your projects and tasks

## Tech Stack

- **[Laravel 13](https://laravel.com)** — Backend framework
- **[Livewire 4](https://livewire.laravel.com)** — Dynamic UI components
- **[Flux UI 2](https://fluxui.dev)** — UI component library
- **[Laravel Fortify](https://laravel.com/docs/fortify)** — Authentication backend
- **[Tailwind CSS 4](https://tailwindcss.com)** — Styling
- **[SQLite](https://sqlite.org)** — Database
- **[Vite](https://vite.dev)** — Asset bundling

## Requirements

- PHP ^8.3
- Node.js & npm
- SQLite (or your preferred database driver)

## Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate key
cp .env.example .env
php artisan key:generate

# Create SQLite database and run migrations
touch database/database.sqlite
php artisan migrate

# Install frontend dependencies and build
npm install
npm run build
```

Or use the convenience script:

```bash
composer run setup
```

## Seeding

Since registration is disabled, seed an admin user to log in:

```bash
php artisan db:seed --class=UserAdminSeeder
```

This creates an admin account with:
- **Email:** `admin@example.com`
- **Password:** `password`

You can also seed demo projects and tasks:

```bash
php artisan db:seed --class=ProjectsSeeder
php artisan db:seed --class=TaskSeeder
```

## Development

Run all services concurrently (server, queue, logs, and Vite):

```bash
composer run dev
```

## Testing & Linting

```bash
composer run test
```

## Project Structure

```
app/
├── Actions/          # Application actions
├── Livewire/         # Livewire components
├── Models/           # Eloquent models (User, Project, Task)
└── Providers/        # Service providers
database/
├── factories/        # Model factories
├── migrations/       # Database migrations
└── seeders/          # Database seeders
resources/
└── views/            # Blade templates (components, layouts, pages)
routes/
├── web.php           # Web routes
├── settings.php      # Settings routes
└── console.php       # Console commands
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
