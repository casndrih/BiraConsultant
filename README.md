# BiraConsultant

**BiraConsultant** is a Laravel-powered recruitment and job marketplace that brings job seekers, employers, and platform administrators together in one application. Job seekers can discover opportunities and manage applications, while employers can manage company profiles, publish vacancies, and review applicants.

The project is built with **Laravel 11**, PHP 8.2+, and a Laravel Mix / Tailwind CSS frontend. Its interface uses Bira Consultants branding with a yellow-and-gold accent palette.

---

## Table of contents

- [Overview](#overview)
- [Core capabilities](#core-capabilities)
- [Technology stack](#technology-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the application](#running-the-application)
- [Frontend assets](#frontend-assets)
- [Project structure](#project-structure)
- [Testing and code quality](#testing-and-code-quality)
- [Deployment notes](#deployment-notes)
- [Contributing](#contributing)
- [License](#license)

## Overview

BiraConsultant provides separate experiences for job seekers, company users, and administrators:

| User area | Purpose |
| --- | --- |
| Job seekers | Browse and search vacancies, maintain a profile, save jobs, submit applications, and track application activity. |
| Employers | Register and manage a company profile, publish and manage vacancies, import jobs in bulk, and review applications. |
| Administrators | Manage platform content and oversee jobs, companies, users, applications, settings, and blog content. |

## Core capabilities

### Job seekers
- Browse, search, and view job vacancies.
- Create an account, sign in, and verify an email address.
- Maintain a profile, work experience, and skills.
- Apply for jobs and withdraw applications.
- Save vacancies for later.
- Follow companies.
- Manage notifications and notification preferences.
- Upload identity documents and manage phone verification.

### Employers
- Register a company and maintain its profile.
- Create, edit, publish, and manage job listings.
- Import job listings using the provided bulk-import workflow.
- Review applications and update application stages.
- Access applicant-submitted documents.
- Submit company verification documents.

### Administration and public pages
- Administrative dashboards and management areas for jobs, companies, users, applications, blog posts, and settings.
- Public job and company directories with detail pages.
- Blog, About, Advertising, Contact, Privacy, and Terms pages.
- Google authentication endpoints and protected authentication flows.

> Available functionality can depend on application configuration, database contents, and enabled third-party integrations.

## Technology stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 11 |
| Authentication | Laravel authentication, Laravel Sanctum, Google OAuth integration |
| Database | SQLite by default in the example environment; MySQL and other Laravel-supported databases can be configured |
| Frontend build | Laravel Mix 6, Webpack 5 |
| Styling | Tailwind CSS, Flowbite |
| Browser interactions | Alpine.js, Axios |
| UI components / charts | ApexCharts, Lottie |
| Package managers | Composer, npm |

## Requirements

Before installing, ensure the following are available:

- PHP **8.2 or later**.
- Composer.
- Node.js and npm.
- A supported database (SQLite is configured by default in `.env.example`; MySQL can also be configured).
- PHP extensions required by Laravel 11 and the packages in `composer.json`.

For production, use a supported web server such as Nginx or Apache and configure the document root to the Laravel `public/` directory.

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/casndrih/BiraConsultant.git
cd BiraConsultant
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, use:

```bat
copy .env.example .env
php artisan key:generate
```

### 4. Configure the database

The example environment is configured for SQLite. Create the database file if it does not exist:

```bash
touch database/database.sqlite
```

Then run the migrations:

```bash
php artisan migrate
```

To use MySQL instead, update the `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` values in `.env` before running migrations.

### 5. Install frontend dependencies and build assets

```bash
npm install
npm run production
```

### 6. Start the development server

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

## Configuration

Application configuration is managed through the `.env` file. At minimum, review these values before running the application:

| Variable | Description |
| --- | --- |
| `APP_NAME` | Application name displayed by Laravel and related services. |
| `APP_ENV` | Environment, such as `local` or `production`. |
| `APP_DEBUG` | Enable detailed error output locally; set to `false` in production. |
| `APP_URL` | Public base URL of the application. |
| `DB_*` | Database connection and credentials. |
| `MAIL_*` | Outgoing mail transport and sender configuration. |
| `QUEUE_CONNECTION` | Queue backend used by the application. |
| `SESSION_DRIVER` / `CACHE_STORE` | Session and cache storage drivers. |

If you enable external authentication, CAPTCHA, SMS, or other integrations, add the required credentials and callback URLs using the configuration expected by the corresponding application integration. Never commit real credentials, API keys, or production environment files.

## Running the application

For local development, the basic workflow is:

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: frontend asset watcher
npm run watch
```

The project also defines a Composer development script:

```bash
composer run dev
```

This script starts the Laravel server, queue listener, log viewer, and frontend development process together.

If your environment uses database-backed queues, run a queue worker when processing queued jobs:

```bash
php artisan queue:work
```

## Frontend assets

The frontend is compiled with Laravel Mix. The main theme stylesheet is:

- Source: `resources/css/biraconsultant-theme.css`
- Published asset: `public/css/biraconsultant-theme.css`

The Bira Consultants logo and favicon assets are maintained under `resources/images/favicon` and `public/images/favicon`.

Useful commands:

```bash
npm run development   # Compile development assets
npm run watch         # Watch and rebuild during development
npm run production    # Build optimized production assets
```

## Project structure

A high-level view of the Laravel application:

```text
app/
  Http/Controllers/   Application, authentication, company, and admin controllers
  Models/             Eloquent models
  ...
database/
  migrations/         Database schema migrations
  seeders/            Database seeders
public/
  css/                Compiled stylesheets
  images/             Public images and branding assets
resources/
  css/                Stylesheet sources and theme
  images/             Source image assets
  views/              Blade templates
routes/
  web.php             Web routes
tests/                Automated tests
```

## Testing and code quality

Run the Laravel test suite with:

```bash
php artisan test
```

Validate Composer configuration with:

```bash
composer validate
```

Build production frontend assets with:

```bash
npm run production
```

## Deployment notes

Before deploying to a production environment:

1. Set `APP_ENV=production`, `APP_DEBUG=false`, and the correct `APP_URL`.
2. Configure production database, mail, cache, session, and queue services.
3. Install PHP dependencies without development packages: `composer install --no-dev --optimize-autoloader`.
4. Install frontend dependencies and compile assets: `npm ci && npm run production`.
5. Run database migrations: `php artisan migrate --force`.
6. Ensure the web server serves the Laravel `public/` directory and that `storage/` and `bootstrap/cache/` are writable by the application.
7. Configure a persistent queue worker if the selected queue connection requires one.
8. Keep secrets outside source control and configure backups, HTTPS, logging, and monitoring.

Refer to the official [Laravel deployment documentation](https://laravel.com/docs/11.x/deployment) for production deployment guidance.

## Contributing

Contributions and improvements are welcome. A good contribution should:

1. Be based on a focused branch.
2. Follow the existing Laravel and frontend conventions.
3. Include or update tests where appropriate.
4. Pass the relevant test and asset-build checks.
5. Clearly describe the change in its pull request.

## License

This project is distributed under the MIT License. See the [LICENSE](LICENSE) file for the full license text.

---

**Repository:** [github.com/casndrih/BiraConsultant](https://github.com/casndrih/BiraConsultant)
