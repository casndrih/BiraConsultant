# BiraConsultant

BiraConsultant is a Laravel 11 recruitment and job marketplace application.

## Requirements
- PHP 8.2+
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database

## Setup
1. Copy `.env.example` to `.env` and configure the database, mail, application URL, and integrations.
2. Run `composer install`.
3. Generate an application key with `php artisan key:generate`.
4. Run migrations with `php artisan migrate`.
5. Install frontend dependencies with `npm install`.
6. Build frontend assets with `npm run production`.
7. Start the application with `php artisan serve`.

## Branding
The BiraConsultant interface uses a yellow/gold accent palette. The theme source is
`resources/css/biraconsultant-theme.css` and its published copy is in
`public/css/biraconsultant-theme.css`. The Bira Consultants logo and favicon assets
are stored under `resources/images/favicon` and `public/images/favicon`.

## License
See [LICENSE](LICENSE).
