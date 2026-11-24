# BiraConsultant – Company Documentation

## Overview
Companies use the **Company** role to publish jobs, manage applications, and maintain a public profile. The registration flow auto-generates a company-specific email domain and username.

## Registering as a Company
1. Click **Sign Up** on the home page.
2. Choose the *Company* radio button.
3. Fill **Company Name** (required). The system will:
   - Generate a slug (`company-name`), used for email domain (`@company-name.com`).
   - Auto-populate the **Username** field with the slug.
   - Show a *domain hint* under the email input.
4. Complete the rest of the form (password, contact phone, etc.) and submit.
5. After registration, an admin may need to approve the company (default `active` = `1`).

## Company Dashboard
- **Statistics**: total posted jobs, active applications, profile completeness.
- **Job Posting**: click **Post Job** in the sidebar.
- **Applications**: view incoming applications, change status, and send templated emails.
- **Profile Editing**: update logo, description, social media links, and contact details.
- **Skeleton Loading**: charts and recent activity panels use shimmer placeholders while data loads.

## Posting a Job
1. Navigate to **Company → Post Job**.
2. Fill in required fields:
   - Title, Description (rich text area).
   - Category (select), Job Type, Experience Level.
   - Salary range, Location, and optional remote flag.
3. Choose **Publish** (or **Save as Draft**).
4. The job appears on the public listings immediately.

## Managing Applications
- **Inbox**: list of candidates who applied.
- **Status Workflow**: `New → Reviewed → Interview → Hired / Rejected`.
- **Email Templates**: configurable in **Admin → Settings → Email Templates**; companies can select a template when updating status.
- **Bulk Actions**: select multiple applications to change status at once.

## Account Settings
- **Change Password**: via the *Account Settings* page.
- **Deactivate Account**: removes all jobs and data; irreversible.
- **Notification Preferences**: toggle email alerts for new applications, status changes.

## Best Practices
- **Complete Profile**: add logo, company description, and social links to improve trust.
- **Clear Job Titles**: include seniority level and location in the title.
- **Responsive Descriptions**: use bullet points, avoid large blocks of text.
- **Timely Communication**: update application statuses promptly; candidates appreciate fast feedback.

## Troubleshooting
- **Cannot Register**: ensure the company name contains only alphanumeric characters; special characters are stripped for the slug.
- **Email Domain Not Displayed**: clear the form and re-enter the company name; the JavaScript listener updates the domain hint automatically.
- **Jobs Not Showing**: run `php artisan view:clear` and verify the job is *published* (not a draft).

---

*Prepared for companies using BiraConsultant.*