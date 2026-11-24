# BiraConsultant – Comprehensive Documentation

*Version: 1.0 | Generated on $(date)*

---

## 📖 Introduction
**BiraConsultant** is a Laravel-based platform that connects applicants, companies, and administrators. It features a dark-mode Material 3 UI, slug-based routing, reCAPTCHA protection, and a fully-responsive Tailwind design.

---

## ⚙️ Getting Started
### Prerequisites
- **PHP 8.2+**, **Composer**, **Node >= 20** (for Tailwind assets)
- **SQLite/MySQL** database (configured in `.env`)
- **Termux** environment (Android) – the project resides in `~/biraconsultant/`
- Optional: **Magisk-root** for APK packaging (outside the scope of this document)

### Installation
```bash
cd ~/biraconsultant
composer install          # PHP dependencies
npm install               # Tailwind & JS tooling
npm run dev               # Compile assets (or `npm run prod` for production)
php artisan migrate      # Create DB tables
php artisan key:generate  # Set APP_KEY in .env
php artisan serve --host=0.0.0.0 --port=8080
```
Open **http://127.0.0.1:8080** in a browser.

---

## 👥 User Roles Overview
| Role | Value | Primary UI | Key Permissions |
|------|-------|-----------|-----------------|
| **Applicant** | `0` | *User Dashboard* | Browse jobs, apply, save, follow companies, edit profile |
| **Admin** | `1` | *Admin Panel* | Approve/reject jobs, manage companies & users, view logs, adjust settings |
| **Company** | `2` | *Company Dashboard* | Post & edit jobs, review applications, upload verification documents |

*Note:* An applicant can also register a company (dual-role). In that case the user keeps role `0` and gains access to the company dashboard via the `CompanyAccess` middleware.

---

## 🛠️ Admin Guide
### 1. Login & Dashboard
- Access the admin panel at **`/admin`**. The auth modal is used for sign-in; direct `/login` redirects to the home page.
- The dashboard provides quick stats (jobs pending approval, new registrations, system health).

### 2. Managing Jobs
1. **Pending Jobs** → `Admin → Jobs → Pending`
2. Click **Approve** or **Reject**. Approved jobs become visible on the frontend via slug URLs (`/jobs/{slug}`).
3. Edit or delete jobs using the **Edit**/**Delete** icons (slug-based routes).

### 3. Managing Companies & Users
- **Companies** → `Admin → Companies` – view verification status, enable/disable accounts.
- **Users** → `Admin → Users` – change roles, reset passwords, deactivate accounts.

### 4. Settings
- **General** – site name, email sender, asset URLs.
- **Upload Limits** – navigate to **Admin → Settings → Upload Limits**; each field is in KB (default 2 MB). Values are stored in `biraconsultant_settings` and enforced by `UploadLimitHelper`.
- **Advanced** – toggle **reCAPTCHA** and supply Google keys. All protected forms (sign-in, sign-up, job application, contact) now require a valid token.

### 5. Viewing Logs & Debugging
```bash
tail -f storage/logs/laravel.log   # real-time error stream
php artisan view:clear             # clear compiled Blade views after edits
php artisan route:list              # verify slug routes
```
Common pitfalls are listed in the **Appendix** (dark-mode classes, missing Blade includes, escaped `$` in regex replacements).

---

## 👤 Applicant Guide
### Authentication
- Click **Sign-In** or **Sign-Up** in the top-right corner to open the modal.
- During registration choose **Applicant** or **Company**. Selecting **Company** hides applicant-only fields (DOB, gender) and automatically creates a `BiraConsultantCompany` linked to the user.

### Browsing & Applying
1. **Job List** – `/jobs` displays cards with a skeleton loader while fetching data.
2. **Job Detail** – click a card to view `/jobs/{slug}`.
3. **Apply** – fill the application form; file uploads respect the limits set by admin.
4. **Save Job** – click the **heart** icon to add to "Saved Jobs" (shown in the user dashboard).
5. **Follow Company** – on a company page click **Follow**; guests are prompted to log in via the modal.

### Profile Management
- Access your dashboard via the user avatar → **Dashboard**.
- Update profile picture, personal data, and NID documents. All inputs contain both light (`bg-gray-100`) and dark (`dark:bg-custom-dark`) Tailwind classes.

---

## 🏢 Company Guide
### Registration
- In the **Sign-Up** modal select **Company**. Provide company name, email domain, and upload verification docs (logo, registration certificate).
- After registration you are redirected to the **Company Dashboard**.

### Posting Jobs
1. **Create Job** – `/company/jobs/create` (slug is auto-generated from the title).
2. Fill title, description, location, salary, and upload optional PDFs.
3. Submit – the job enters the **Pending** queue for admin approval.

### Managing Applications
- View incoming applications under **Applications** tab. Download CVs, change status (reviewed, shortlisted, rejected).

### Verification & Settings
- Upload verification documents via **Verification** tab. Admin will mark the company as `verified` once approved.
- Adjust company profile (logo, description, contact info) under **Settings**.

---

## 🔗 API Reference (excerpt)
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/jobs` | `GET` | none | List all approved jobs (slug URLs) |
| `/api/jobs` | `POST` | **Company** | Create a new job (pending approval) |
| `/api/jobs/{slug}` | `PUT` | **Company** | Update own job |
| `/api/jobs/{slug}` | `DELETE` | **Company** | Delete own job |
| `/api/companies` | `GET` | none | List verified companies |
| `/api/applications` | `POST` | **Applicant** | Submit a job application |
| `/api/follow/company/{slug}` | `POST` | **Applicant** | Follow a company |
| `/api/follow/company/{slug}` | `DELETE` | **Applicant** | Unfollow a company |

All API routes are protected by Laravel Sanctum; include the `Authorization: Bearer <token>` header.

---

## 🧹 Development & Maintenance
- **Cache** – run `php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear` after any Blade or config change.
- **Testing** – PHPUnit tests are located in `tests/Feature/`. Run with `php artisan test`.
- **CI** – the project ships with a GitHub Actions workflow that runs linting, tests, and builds assets.
- **Skeleton Loading** – components in `resources/views/components/skeleton-*.blade.php` use `dark:bg-custom-dark` for dark mode.
- **Dark-Mode Colors** – avoid `dark:bg-gray-800`; always use `dark:bg-custom-dark` (`#0E0E10`).

---

## 📚 Appendix & Common Pitfalls
1. **Missing Blade includes** – always run `php artisan view:clear` after adding a new `@include`.
2. **Escaped `$` in regex replacements** – after bulk `sed` runs, verify with `grep -rn '\\$' resources/views/`.
3. **Queue configuration** – notifications use `ShouldQueue`. Set `QUEUE_CONNECTION=sync` in `.env` unless a queue worker is running.
4. **Font warnings in PDF** – Unicode emojis are omitted from the PDF; they do not affect the content.
5. **Tailwind purge** – in `tailwind.config.js` ensure all Blade files are scanned (`./resources/**/*.blade.php`).

---

## 🤝 Contributing
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/xyz`).
3. Follow the **Coding Standards** (PSR-12, Tailwind naming).
4. Submit a Pull Request – CI will run tests automatically.

---

## 📞 Support & Contact
- **Issue Tracker:** https://github.com/your-org/biraconsultant/issues
- **Documentation Repository:** https://github.com/your-org/biraconsultant/wiki
- **Slack Channel:** #biraconsultant-dev (invite on request)

---

*© 2026 BiraConsultant – All rights reserved.*