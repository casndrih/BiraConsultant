# BiraConsultant – User Documentation

## Overview
This guide is for **applicants** (role = 0) who browse jobs, create profiles, and apply for positions.

## Table of Contents
1. [Creating an Account](#creating-an-account)
2. [Logging In & Authentication](#logging-in--authentication)
3. [Dashboard Overview](#dashboard-overview)
4. [Searching & Filtering Jobs](#searching--filtering-jobs)
5. [Applying for a Job](#applying-for-a-job)
6. [Saved Jobs & Applications](#saved-jobs--applications)
7. [Profile Management](#profile-management)
8. [UI Elements & Dark Theme](#ui-elements--dark-theme)
9. [FAQ for Applicants](#faq-for-applicants)

---

### Creating an Account
- Click **Sign Up** on the home page.
- Choose **Applicant** (radio button). Fields required:
  - Full Name
  - Username (auto-generated from name, can be edited)
  - Email, Password, Date of Birth, Gender, Country.
- Agree to the Privacy Policy & T&C and submit.
- An email verification step is optional (depends on `enable_registration`).

### Logging In & Authentication
- Click **Sign In**.
- Enter email/username and password.
- After login you are redirected to the **User Dashboard**.

### Dashboard Overview
- **Welcome banner** with personalized greeting.
- **Recent Applications** – shows the last 3 applications or skeleton cards when empty.
- **Recommended Jobs** – AI-based suggestions; also displays skeleton cards if no data.
- **Search bar** – top of the page, with a search icon inside a rounded-full input.

### Searching & Filtering Jobs
- Use the **search bar** to look for keywords, titles, or company names.
- Advanced filters appear below the search bar (Category, Job Type, Experience).
- Results update via AJAX; a loading spinner appears while fetching.

### Applying for a Job
1. Click a job tile to view details.
2. Press the **Apply** button (yellow `bg-yellow-300` rounded-full).
3. A modal confirms the application; you can add a cover letter.
4. The application appears in **Recent Applications** on your dashboard.

### Saved Jobs & Applications
- Click the **bookmark** icon on a job card to add to your **Saved Jobs** list.
- Access saved jobs from the dashboard dropdown.
- View status of each application (Pending, Accepted, Rejected).

### Profile Management
- Open the **Profile** modal from the header dropdown.
- Update personal information; all inputs use `bg-gray-100` (light) and `dark:bg-black` (dark) with `rounded-full` borders.
- Change password via the **Security** tab.

### UI Elements & Dark Theme
- All buttons, inputs, and chips are **rounded-full**.
- Primary CTA color: **Yellow** (`bg-yellow-300`, hover `bg-yellow-400`, text black).
- Light mode inputs: `bg-gray-100`.
- Dark mode inputs: `bg-black`.
- Cards use `dark:bg-custom-dark #0E0E10` and have a subtle `shadow-md`.
- Skeleton loading uses the global `.skeleton-box` CSS defined in the layout.

### FAQ for Applicants
- **Why do I see gray placeholders on the dashboard?** The skeleton indicates data is loading via AJAX.
- **How to switch to dark mode?** The site follows the system `prefers-color-scheme`; you can also toggle via the theme switcher in the header.
- **Can I withdraw an application?** Yes, from the **Recent Applications** list, click **Withdraw**.
- **I forgot my password.** Use the **Forgot Password** link on the Sign In modal; an email with a reset link will be sent.

---

*Generated on $(date) for Mr CN – User Documentation*