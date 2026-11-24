# BiraConsultant – Full Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Installation & Setup](#installation--setup)
4. [Configuration](#configuration)
5. [Maintenance Mode](#maintenance-mode)
6. [UI Skeleton & Shimmer Loading](#ui-skeleton--shimmer-loading)
7. [Admin Guide](#admin-guide)
8. [User Guide](#user-guide)
9. [Company Guide](#company-guide)
10. [Testing & Debugging](#testing--debugging)
11. [Deployment](#deployment)
12. [FAQ & Troubleshooting](#faq--troubleshooting)
13. [Appendix – CLI Commands](#appendix---cli-commands)

---

### Introduction
BiraConsultant is a Laravel-based job-board platform designed for **Applicants**, **Companies**, and **Admins**. It features a modern **dark Material 3** UI, Tailwind utility classes, and a **shimmer** skeleton loading pattern for a smooth perceived performance.

### Prerequisites
- Android 13 device with Termux installed (or any Linux environment).
- PHP 8.5+, Composer, SQLite (bundled with PHP).
- Node 20+ (for front-end asset compilation).
- Git (optional, for source control).

### Installation & Setup
```bash
# 1. Clone the repository
git clone https://github.com/your-repo/biraconsultant.git ~/biraconsultant
cd ~/biraconsultant

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies & compile assets
npm ci && npm run dev

# 4. Set up the SQLite database
mkdir -p database && touch database/database.sqlite
php artisan migrate --seed

# 5. Serve the application (Termux)
php artisan serve --host=127.0.0.1 --port=8080
```
Open `http://127.0.0.1:8080` in the browser.

### Configuration
- **Environment** – copy `.env.example` to `.env` and adjust `APP_URL`, `APP_KEY` (run `php artisan key:generate`).
- **Tailwind Colors** – `resources/css/app.css` contains the custom dark background (`bg-custom-dark #0E0E10`).
- **Mail & Recaptcha** – configure `MAIL_` and `RECAPTCHA_` variables if needed.

### Maintenance Mode
A middleware `CheckMaintenanceMode` reads the `maintenance_mode` flag from `biraconsultant_settings`.
```sql
INSERT INTO biraconsultant_settings (key, value) VALUES ('maintenance_mode', '1');
-- set to 0 to disable
```
When enabled the whole site returns **HTTP 503** with a friendly "Site Under Maintenance" page.

### UI Skeleton & Shimmer Loading
- Global CSS definitions (`@keyframes shimmer`) provide a pulsing placeholder.
- **Admin Dashboard** – chart area now shows a gray animated box while data loads.
- **User Dashboard** – recent applications and recommended jobs display three skeleton cards when empty.
- All placeholders automatically switch to dark colors when `prefers-color-scheme: dark`.

### Admin Guide
1. **Login** – use the *Sign In* button; admins have the `role = 1` flag.
2. **Dashboard** – shows stats, charts, and quick links.
3. **Manage Users** – CRUD via `/admin/users`.
4. **Manage Companies & Jobs** – `/admin/companies`, `/admin/jobs`.
5. **Settings** – toggle `maintenance_mode` from the *Settings* page or via the `biraconsultant_settings` table.
6. **Logs** – view Laravel logs at `storage/logs/laravel.log`.
7. **Permissions** – defined in `app/Http/Middleware/RoleCheck.php`.

### User Guide
- **Registration** – choose *Applicant*; fields auto-populate username and email.
- **Search** – use the top search bar with autocomplete icons.
- **Apply** – click **Apply** on a job; a confirmation toast appears.
- **Saved Jobs** – click the bookmark icon to add to your saved list.
- **Profile** – edit personal details; only light/dark input styles are applied.

### Company Guide
- **Sign-up** – select *Company*; the form hides DOB/Gender and shows a domain hint.
- **Post Jobs** – access `/company/jobs/create` – fill title, description, category, and type.
- **Manage Applicants** – view applications, change status, contact candidates.
- **Branding** – edit company logo and description from the *Company Settings* page.
- **Analytics** – see impressions and click-through rates on the company dashboard.

### Testing & Debugging
```bash
# Run unit tests (PHPUnit)
php artisan test
# Run feature tests with parallel workers
php artisan test --parallel=4
# Lint Blade templates
blade-linter resources/views/**/*.blade.php
# Check for missing Tailwind classes
npm run lint:css
```
Use `php artisan tinker` to manually toggle maintenance mode or inspect DB records.

### Deployment
1. **Production server** – install PHP, Composer, and a web server (NGINX/Apache).
2. **Set `APP_ENV=production` and enable caching**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. **Database** – migrate to MySQL/PostgreSQL if needed; adjust `DB_CONNECTION` in `.env`.
4. **SSL** – obtain a certificate and configure HTTPS.
5. **Background jobs** – set up Laravel Horizon or Supervisor for queues.

### FAQ & Troubleshooting
- **Site shows 503 after enabling maintenance mode?** – Confirm the flag in `biraconsultant_settings`. Set back to `0` to restore.
- **Skeleton placeholders never disappear?** – Ensure AJAX endpoints (`/latest-jobs`, `/featured-jobs`) return valid HTML and the scripts are loaded.
- **Dark mode colors look wrong?** – Verify `dark:bg-custom-dark` is present on container elements and that the Tailwind `dark` variant is enabled in `tailwind.config.js`.
- **Search returns no results** – Run `php artisan scout:import` if using Laravel Scout for full-text search.

### Appendix – CLI Commands
| Command | Description |
|--------|-------------|
| `php artisan serve` | Start the development server |
| `php artisan migrate` | Run migrations |
| `php artisan db:seed` | Seed demo data |
| `php artisan tinker` | Interactive REPL |
| `php artisan queue:work` | Process queued jobs |
| `php artisan schedule:run` | Run scheduled tasks |
| `php artisan config:cache` | Cache configuration |
| `php artisan route:cache` | Cache routes |
| `php artisan view:cache` | Cache compiled Blade views |

---

*Generated on $(date) for Mr CN – © 2026 BiraConsultant*