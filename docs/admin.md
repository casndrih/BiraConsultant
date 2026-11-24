# BiraConsultant – Admin Documentation

## Overview
This guide is intended for **administrators** (role = 1). It covers daily operations, system settings, and maintenance tasks.

## Table of Contents
1. [Accessing the Admin Panel](#accessing-the-admin-panel)
2. [Dashboard Overview](#dashboard-overview)
3. [User Management](#user-management)
4. [Company Management](#company-management)
5. [Job Management](#job-management)
6. [Site Settings & Maintenance Mode](#site-settings--maintenance-mode)
7. [Content Moderation](#content-moderation)
8. [Logs & Monitoring](#logs--monitoring)
9. [Backup & Restore](#backup--restore)
10. [FAQ for Admins](#faq-for-admins)

---

### Accessing the Admin Panel
- URL: `http://127.0.0.1:8080/admin`
- Login using an account with `role = 1` (Admin). The middleware `CheckMaintenanceMode` applies to all routes, including admin.

### Dashboard Overview
- **Statistics cards** – active jobs, companies, applicants, and site traffic.
- **Chart area** – now displays a **shimmer** placeholder while the chart data loads via AJAX.
- **Quick actions** – create user, create company, toggle maintenance.

### User Management
1. Navigate to **Users**.
2. **Search / filter** by name, email, or role.
3. Actions per user:
   - **Edit** – update name, email, role.
   - **Suspend / Activate** – toggle `status` flag.
   - **Delete** – permanently remove (soft-delete first recommended).
4. Bulk actions via the checkbox list.

### Company Management
- Similar UI to Users.
- Ability to **approve** newly registered companies (they are created with `status = pending`).
- Edit company profile, logo, and description.
- View company-specific analytics (job impressions, applications).

### Job Management
- List all jobs with pagination.
- Filter by **category**, **type**, **status**.
- **Edit** job details, salary, location, and visibility.
- **Feature** a job (adds a badge on the front-end).
- Delete or **archive** jobs that are no longer active.

### Site Settings & Maintenance Mode
- **Settings page** (`/admin/settings`) reads from `biraconsultant_settings` table.
- To enable maintenance mode:
  ```sql
  UPDATE biraconsultant_settings SET value = '1' WHERE key = 'maintenance_mode';
  ```
  The site will now return a **503 Service Unavailable** page.
- To disable, set the value back to `0`.
- Other configurable flags: `enable_registration`, `recaptcha_enabled`, `jobs_per_page`.

### Content Moderation
- **Blogs & Articles** – approve or reject submissions from companies.
- **User-generated content** – flagging system (reports stored in `notifications`).
- **Spam detection** – optional integration with external anti-spam APIs.

### Logs & Monitoring
- Laravel logs: `storage/logs/laravel.log`.
- **Admin activity log** – stored in `notifications` table with `type = 'admin_action'`.
- **Performance monitoring** – you can enable Horizon for queue workers.

### Backup & Restore
```bash
# Backup database
tar -czf backup-$(date +%F).tar.gz database/database.sqlite
# Restore (replace existing file)
cp backup-2026-06-04.tar.gz database/database.sqlite
```
- Keep a copy of the `storage/app/public` directory for uploaded assets.

### FAQ for Admins
- **Why does the dashboard show a gray box?** – The chart loads asynchronously; the shimmer indicates loading.
- **How to force a user password reset?**
  ```php
  $user = \App\Models\BiraConsultantUser::find($id);
  $user->password = Hash::make('newPassword');
  $user->save();
  ```
- **Can I disable registration?** Set `enable_registration = 0` in `biraconsultant_settings`.
- **How to clear view cache after editing Blade files?**
  ```bash
  php artisan view:clear
  ```

---

*Generated on $(date) for Mr CN – Admin Documentation*