# BiraConsultant

<p align="center">
  <strong>A recruitment and career platform connecting job seekers with employers.</strong>
</p>

<p align="center">
  <a href="https://biraconsultants.com">Live website</a> ·
  <a href="https://github.com/casndrih/BiraConsultant">Source code</a> ·
  <a href="https://laravel.com/docs/11.x">Laravel 11 documentation</a>
</p>

---

## Overview

**BiraConsultant** is a web-based recruitment portal built to support the job-search and hiring process in one place. Job seekers can discover opportunities, maintain their profiles and submit applications. Employers can manage company information, publish vacancies and review applicants. Administrators oversee platform content, users and configuration.

The application is built with Laravel and a server-rendered Blade interface, with a responsive frontend powered by Tailwind CSS, Flowbite and Laravel Mix.

## Platform capabilities

### Public website
- Browse and search job vacancies.
- Explore company profiles and published opportunities.
- View blog articles and informational pages.
- Submit enquiries through the contact form.
- Register, sign in and recover account access.
- Optional Google authentication integration.

### Job-seeker workspace
- Personal dashboard and profile management.
- Browse vacancies and submit job applications.
- Save jobs for later and withdraw applications.
- Maintain work experience and skills.
- Follow companies.
- Manage notifications and notification preferences.
- Submit identity information and complete supported verification steps.

### Employer workspace
- Company dashboard and profile settings.
- Create and manage job listings.
- Review applications for company vacancies.
- Update applicant stages.
- Access applicant-submitted files where permitted.
- Submit company verification information.

### Administration
- Administrative dashboard.
- Manage users, companies and job listings.
- Review and manage applications.
- Manage blog content.
- Review contact messages.
- Configure platform settings and verification workflows.

Access to workspace routes is protected by authentication and role/access middleware.

## Technology stack

| Layer | Technologies |
| --- | --- |
| Backend | PHP 8.2+, Laravel 11 |
| Application UI | Laravel Blade |
| Styling | Tailwind CSS, Flowbite |
| JavaScript | Alpine.js, Axios |
| Asset pipeline | Laravel Mix 6, Webpack |
| UI and visualisations | ApexCharts, Lottie |
| Database | SQLite for the current development/deployment phase |
| Authentication | Laravel session authentication, optional Google OAuth |
| Hosting | Hostinger |
| CI/CD | GitHub Actions |

## Getting started

### Requirements

Install the following before setting up the project:

- PHP **8.2 or newer**
- Composer
- Node.js and npm
- PHP extensions required by Laravel, including **PDO SQLite** and **SQLite3**
- Git

### 1. Clone the repository

```bash
git clone https://github.com/casndrih/BiraConsultant.git
cd BiraConsultant
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure the environment

Create your local environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure the application environment in `.env`. For local development, use SQLite:

```dotenv
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create the SQLite database file:

**Linux / macOS**
```bash
mkdir -p database
touch database/database.sqlite
```

**Windows PowerShell**
```powershell
New-Item -ItemType Directory -Force database
New-Item -ItemType File -Force database/database.sqlite
```

Ensure the PHP CLI has the `pdo_sqlite` extension enabled.

### 4. Run migrations and seed development data

```bash
php artisan migrate --seed
```

The project seeders provide synthetic records for local interface and workflow testing. If demo sign-in is required, configure a private `BIRA_DEMO_PASSWORD` in your local environment before running the seeders. Never commit passwords, production credentials, or populated database files.

### 5. Build frontend assets

For development:

```bash
npm run dev
```

For a production asset build:

```bash
npm run prod
```

### 6. Start the application

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Useful development commands

```bash
# Run the automated test suite
php artisan test

# Run database migrations
php artisan migrate

# Clear Laravel's cached configuration and compiled files
php artisan optimize:clear

# Rebuild frontend assets
npm run prod

# Run the Laravel code formatter
./vendor/bin/pint
```

## Database and deployment

The current application configuration uses **SQLite** so development and deployment validation can proceed without depending on the Hostinger MySQL service. The production deployment workflow prepares the SQLite database in the persistent application directory and runs Laravel migrations against it.

Important operational notes:

- The SQLite database is runtime data and must not be committed to Git.
- Keep the database in persistent storage; do not place it in a directory replaced during asset publishing.
- Back up the database before deployments that may alter schema or data.
- Do not change the production database driver to MySQL until the application is approved for that migration and the production connection, credentials, backup and migration plan have been verified.
- Never place secrets in source files, README examples, commit messages or logs.

### GitHub Actions deployment

The repository includes a GitHub Actions workflow for deploying the Laravel application to Hostinger. Deployment depends on repository Actions secrets for the hosting connection and application configuration. Maintain those values in the repository's **Settings → Secrets and variables → Actions**; do not store them in the codebase.

After a deployment, verify the workflow result and the public site at [https://biraconsultants.com](https://biraconsultants.com). A successful workflow is not a substitute for checking the key public pages and authenticated role-based areas.

## Project structure

The main Laravel directories follow the standard framework layout:

```text
app/
  Http/Controllers/     Application, authentication and dashboard controllers
  Models/               Eloquent models
  Providers/             Service providers
bootstrap/               Framework bootstrap
config/                  Application configuration
database/
  factories/             Model factories
  migrations/             Database schema
  seeders/                Development/demo seed data
public/                   Public entry point and compiled assets
resources/
  js/                     Frontend JavaScript
  sass/                   Stylesheets
  views/                  Blade templates
routes/
  web.php                 Web routes
  console.php              Console routes
storage/                   Logs, cache and uploaded files
tests/                     Automated tests
webpack.mix.js             Laravel Mix asset configuration
```

## Security

- Keep `.env` and all credentials private.
- Use HTTPS in deployed environments.
- Keep `APP_DEBUG=false` in production.
- Review authentication, role middleware and authorization before changing protected routes.
- Preserve request validation, CSRF protection, throttling and configured reCAPTCHA checks.
- Validate and authorize uploaded files and downloads.
- Do not use real personal information in demo or test records.
- Report suspected security issues privately to the repository maintainers rather than publishing exploit details.

## Contributing

Contributions should be focused and tested before they are merged.

1. Create a feature branch from `main`.
2. Make the smallest coherent change that addresses the task.
3. Run relevant automated tests and rebuild frontend assets when applicable.
4. Review the affected public and role-protected views at desktop and mobile sizes.
5. Open a pull request with a clear description, implementation notes and test results.

## License

The repository currently identifies itself as **MIT** in its Composer project metadata. Confirm the repository's intended distribution and licensing terms before redistributing the application.

---

<p align="center">
  Built for a more accessible job-search and hiring experience.
</p>
