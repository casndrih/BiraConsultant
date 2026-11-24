#!/usr/bin/env python3
"""BiraConsultant API v1 endpoint test suite.

Tests every public, user, company and admin endpoint including the
newly added ones (register, meta, apply, withdraw, save, follow,
notifications, profile update, change-password, job status, company
settings update). Cleans up all rows it creates.
"""
import json
import urllib.request
import urllib.parse
import urllib.error
import http.cookiejar
import sys

BASE = "http://127.0.0.1:8000/api/v1"
RESULTS = []


def client():
    """Return (opener, headers) with a fresh cookie jar."""
    jar = http.cookiejar.CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))
    return opener


def req(opener, method, path, body=None, headers=None):
    url = BASE + path if path.startswith("/") else path
    data = None
    hdrs = {"Accept": "application/json"}
    if headers:
        hdrs.update(headers)
    if body is not None:
        data = json.dumps(body).encode()
        hdrs["Content-Type"] = "application/json"
    r = urllib.request.Request(url, data=data, method=method, headers=hdrs)
    try:
        with opener.open(r, timeout=30) as resp:
            raw = resp.read().decode()
            try:
                return resp.status, json.loads(raw)
            except Exception:
                return resp.status, {"_raw": raw[:300]}
    except urllib.error.HTTPError as e:
        raw = e.read().decode()
        try:
            return e.code, json.loads(raw)
        except Exception:
            return e.code, {"_raw": raw[:300]}


def check(name, cond, detail=""):
    status = "PASS" if cond else "FAIL"
    RESULTS.append((status, name, detail))
    print(f"[{status}] {name}" + (f"  -- {detail}" if (detail and not cond) else ""))


def section(title):
    print(f"\n===== {title} =====")


# ------------------------------------------------------------------
guest = client()

section("PUBLIC ENDPOINTS")

s, j = req(guest, "GET", "/jobs")
check("GET /jobs list", s == 200 and j.get("success") and len(j["data"]["data"]) > 0, f"status={s}")
first_job = j["data"]["data"][0] if s == 200 and j.get("data", {}).get("data") else {}
job_slug = first_job.get("slug", "")
job_id = first_job.get("id")

s, j = req(guest, "GET", f"/jobs?search=software&per_page=5")
check("GET /jobs?search filter", s == 200 and j.get("success"), f"status={s}")

s, j = req(guest, "GET", f"/jobs/{job_slug}")
check("GET /jobs/{slug} detail", s == 200 and j.get("success") and j["data"].get("slug") == job_slug, f"status={s}")

s, j = req(guest, "GET", "/jobs-search?q=dev")
check("GET /jobs-search", s == 200 and j.get("success"), f"status={s}")

s, j = req(guest, "GET", "/companies")
check("GET /companies list", s == 200 and j.get("success"), f"status={s}")
companies = j["data"]["data"] if s == 200 and j.get("data", {}).get("data") else []
company_slug = companies[0]["slug"] if companies else ""
company_id = companies[0]["id"] if companies else None

s, j = req(guest, "GET", f"/companies/{company_slug}")
check("GET /companies/{slug} detail", s == 200 and j.get("success"), f"status={s}")

s, j = req(guest, "GET", "/blogs")
check("GET /blogs list", s == 200 and j.get("success"), f"status={s}")
blogs = j["data"]["data"] if s == 200 and j.get("data", {}).get("data") else []
blog_slug = blogs[0]["slug"] if blogs else ""

s, j = req(guest, "GET", f"/blogs/{blog_slug}")
check("GET /blogs/{slug} detail", s == 200 and j.get("success"), f"status={s}")

s, j = req(guest, "GET", "/home/jobs")
check("GET /home/jobs", s == 200 and j.get("success"), f"status={s}")
s, j = req(guest, "GET", "/home/companies")
check("GET /home/companies", s == 200 and j.get("success"), f"status={s}")
s, j = req(guest, "GET", "/home/featured-jobs")
check("GET /home/featured-jobs", s == 200 and j.get("success"), f"status={s}")

s, j = req(guest, "GET", "/search?q=dev")
check("GET /search", s == 200 and j.get("success") and "jobs" in j["data"], f"status={s}")

s, j = req(guest, "GET", "/countries")
check("GET /countries", s == 200 and j.get("success") and len(j["data"]) > 0, f"status={s}")
country_id = j["data"][0]["id"] if s == 200 and j.get("data") else None

s, j = req(guest, "GET", "/meta")
check("GET /meta (NEW)", s == 200 and j.get("success") and
      all(k in j["data"] for k in ("categories", "job_types", "experience_levels", "countries", "application_statuses")),
      f"status={s} keys={list(j.get('data', {}).keys()) if isinstance(j.get('data'), dict) else 'n/a'}")

s, j = req(guest, "GET", f"/jobs/{job_slug}/status")
check("GET /jobs/{slug}/status guest (NEW)", s == 200 and j.get("success") and
      j["data"]["has_applied"] is False and j["data"]["has_saved"] is False, f"status={s} body={j}")

s, j = req(guest, "GET", "/jobs/does-not-exist-xyz/status")
check("GET /jobs/{slug}/status 404", s == 404, f"status={s}")

# ------------------------------------------------------------------
section("AUTH")

s, j = req(guest, "POST", "/auth/login", {"email": "paulus.kairo@gmail.com", "password": "wrong"})
check("POST /auth/login invalid -> 401", s == 401 and not j.get("success"), f"status={s}")

s, j = req(guest, "POST", "/auth/login", {"email": "paulus.kairo@gmail.com"})
check("POST /auth/login missing field -> 422", s == 422, f"status={s}")

user = client()
s, j = req(user, "POST", "/auth/login", {"email": "paulus.kairo@gmail.com", "password": "Password123!"})
check("POST /auth/login user role 0", s == 200 and j.get("success") and j["data"]["role"] == 0, f"status={s} body={j}")

s, j = req(user, "GET", "/auth/me")
check("GET /auth/me", s == 200 and j.get("success") and j["data"]["email"] == "paulus.kairo@gmail.com", f"status={s}")

# Register (new) — create a throwaway user
stamp = "apitest"
reg = client()
s, j = req(reg, "POST", "/auth/register", {
    "name": "API Test User", "username": f"{stamp}_user",
    "email": f"{stamp}.user@example.com", "password": "TestPass1",
    "role": 0, "birthday": "1995-05-10", "gender": "0", "country_id": country_id,
})
check("POST /auth/register role 0 (NEW)", s == 201 and j.get("success") and j["data"]["role"] == 0, f"status={s} body={j}")

s, j = req(reg, "GET", "/auth/me")
check("register auto-login (me works)", s == 200 and j.get("success") and j["data"]["email"] == f"{stamp}.user@example.com", f"status={s}")

s, j = req(reg, "POST", "/auth/register", {"name": "x", "email": "bad", "password": "short", "role": 0})
check("POST /auth/register validation -> 422", s == 422, f"status={s}")

s, j = req(reg, "POST", "/auth/register", {
    "name": "Dup", "email": f"{stamp}.user@example.com", "password": "TestPass1", "role": 0,
    "birthday": "1995-05-10", "gender": "0", "country_id": country_id})
check("POST /auth/register duplicate email -> 422", s == 422, f"status={s}")

# ------------------------------------------------------------------
section("USER ENDPOINTS (role 0)")

s, j = req(user, "GET", "/user/dashboard")
check("GET /user/dashboard", s == 200 and j.get("success") and "stats" in j["data"], f"status={s}")

s, j = req(user, "GET", "/user/profile")
check("GET /user/profile", s == 200 and j.get("success") and j["data"]["email"] == "paulus.kairo@gmail.com", f"status={s}")

s, j = req(user, "GET", "/user/applications")
check("GET /user/applications", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "GET", "/user/skills")
check("GET /user/skills", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "POST", "/user/skills", {"name": "APITestSkill"})
check("POST /user/skills add", s == 200 and j.get("success"), f"status={s} body={j}")
skill_id = j["data"]["id"] if s == 200 and j.get("data") else None

s, j = req(user, "DELETE", f"/user/skills/{skill_id}")
check("DELETE /user/skills/{id}", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "GET", "/user/work-experiences")
check("GET /user/work-experiences", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "POST", "/user/work-experiences", {
    "company_name": "API Test Co", "position": "Tester",
    "description": "Testing", "start_date": "2020-01-01", "end_date": "2021-01-01"})
check("POST /user/work-experiences add", s == 200 and j.get("success"), f"status={s} body={j}")
we_id = j["data"]["id"] if s == 200 and j.get("data") else None

s, j = req(user, "DELETE", f"/user/work-experiences/{we_id}")
check("DELETE /user/work-experiences/{id}", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "GET", "/user/followed-companies")
check("GET /user/followed-companies", s == 200 and j.get("success"), f"status={s}")

# --- NEW: apply / withdraw / save / status ---
cover = ("This is a test cover letter written by the automated API test suite. "
         "It is intentionally longer than fifty characters to satisfy validation.")

s, j = req(user, "GET", f"/jobs/{job_slug}/status")
check("GET /jobs/{slug}/status authed (NEW)", s == 200 and j.get("success") and "can_apply" in j["data"], f"status={s} body={j}")

# pick a job this user has NOT already applied to
s, j = req(user, "GET", "/user/applications?per_page=100")
applied_job_ids = {a["job_id"] for a in j["data"]["data"]} if s == 200 and j.get("data", {}).get("data") else set()
s, j = req(guest, "GET", "/jobs?per_page=100")
target = None
for cand in j["data"]["data"]:
    if cand["id"] not in applied_job_ids:
        target = cand
        break
if target is None:
    # every job applied to — create one via the company account later; for now skip
    target = j["data"]["data"][0]
target_job_id = target["id"]
target_job_slug = target["slug"]
print(f"  (test target job: id={target_job_id} slug={target_job_slug} already_applied={target_job_id in applied_job_ids})")

s, j = req(user, "POST", f"/user/jobs/{target_job_id}/apply", {"cover_letter": "short"})
check("POST apply too-short cover letter -> 422", s == 422, f"status={s} body={j}")

s, j = req(user, "POST", f"/user/jobs/{target_job_id}/apply", {"cover_letter": cover})
check("POST /user/jobs/{id}/apply (NEW)", s == 201 and j.get("success"), f"status={s} body={j}")
created_app_id = j["data"]["id"] if s == 201 and isinstance(j.get("data"), dict) else None

s, j = req(user, "POST", f"/user/jobs/{target_job_id}/apply", {"cover_letter": cover})
check("POST apply duplicate -> 409", s == 409, f"status={s} body={j}")

s, j = req(user, "GET", f"/jobs/{target_job_slug}/status")
check("status shows has_applied=true", s == 200 and j["data"]["has_applied"] is True and j["data"]["can_apply"] is False, f"status={s} body={j}")

app_id = created_app_id
check("apply response returned application id", app_id is not None, f"created_app_id={created_app_id}")

s, j = req(user, "DELETE", f"/user/applications/{app_id}")
check("DELETE /user/applications/{id} withdraw (NEW)", s == 200 and j.get("success"), f"status={s} body={j}")

s, j = req(user, "DELETE", f"/user/applications/{app_id}")
check("withdraw again -> 404", s == 404, f"status={s}")

s, j = req(user, "POST", f"/user/jobs/{target_job_id}/save")
check("POST /user/jobs/{id}/save toggle ON (NEW)", s == 200 and j.get("saved") is True, f"status={s} body={j}")

s, j = req(user, "GET", "/user/saved-jobs")
saved_ids = [x["id"] for x in j["data"]] if s == 200 and j.get("data") else []
check("GET /user/saved-jobs contains job (NEW)", target_job_id in saved_ids, f"status={s} saved={saved_ids}")

s, j = req(user, "POST", f"/user/jobs/{target_job_id}/save")
check("POST save toggle OFF (NEW)", s == 200 and j.get("saved") is False, f"status={s} body={j}")

s, j = req(user, "GET", "/user/saved-jobs")
saved_ids = [x["id"] for x in j["data"]] if s == 200 and j.get("data") else []
check("saved-jobs empty after untoggle", target_job_id not in saved_ids, f"status={s}")

# --- NEW: follow / unfollow ---
s, j = req(user, "POST", f"/user/companies/{company_id}/follow")
check("POST /user/companies/{id}/follow (NEW)", s == 200 and j.get("following") is True, f"status={s} body={j}")

s, j = req(user, "GET", "/user/followed-companies")
followed = [c["id"] for c in j["data"]] if s == 200 and j.get("data") else []
check("followed-companies contains company", company_id in followed, f"status={s} followed={followed}")

s, j = req(user, "DELETE", f"/user/companies/{company_id}/unfollow")
check("DELETE unfollow (NEW)", s == 200 and j.get("following") is False, f"status={s} body={j}")

# --- NEW: notifications ---
s, j = req(user, "GET", "/user/notifications")
check("GET /user/notifications (NEW)", s == 200 and j.get("success") and "unread_count" in j["data"], f"status={s} body={str(j)[:200]}")

notif_id = j["data"]["data"][0]["id"] if s == 200 and j["data"].get("data") else None
if notif_id:
    s, j = req(user, "POST", f"/user/notifications/{notif_id}/read")
    check("POST notifications/{id}/read (NEW)", s == 200 and j.get("success"), f"status={s} body={j}")
else:
    check("POST notifications/{id}/read (NEW) — skipped, no notifications", True, "no notification rows for this user")

s, j = req(user, "POST", "/user/notifications/read-all")
check("POST notifications/read-all (NEW)", s == 200 and j.get("success"), f"status={s} body={j}")

s, j = req(user, "GET", "/user/notifications")
check("unread_count == 0 after read-all", s == 200 and j["data"]["unread_count"] == 0, f"status={s} body={str(j)[:200]}")

# --- NEW: profile update + change password ---
s, j = req(user, "POST", "/user/profile", {"name": "Paulus Kairo", "email": "paulus.kairo@gmail.com", "bio": "Updated by API test"})
check("POST /user/profile update (NEW)", s == 200 and j.get("success") and j["data"]["bio"] == "Updated by API test", f"status={s} body={str(j)[:200]}")

s, j = req(user, "POST", "/user/profile", {"name": "", "email": "not-an-email"})
check("POST /user/profile validation -> 422", s == 422, f"status={s}")

s, j = req(user, "POST", "/user/change-password", {"current_password": "wrong", "password": "NewPass123", "password_confirmation": "NewPass123"})
check("change-password wrong current -> 422", s == 422, f"status={s} body={j}")

s, j = req(user, "POST", "/user/change-password", {"current_password": "Password123!", "password": "NewPass123", "password_confirmation": "NewPass123"})
check("POST /user/change-password (NEW)", s == 200 and j.get("success"), f"status={s} body={j}")

# login with the new password to prove it, then change back
chk = client()
s, j = req(chk, "POST", "/auth/login", {"email": "paulus.kairo@gmail.com", "password": "NewPass123"})
check("login with changed password", s == 200 and j.get("success"), f"status={s}")
s, j = req(user, "POST", "/user/change-password", {"current_password": "NewPass123", "password": "Password123!", "password_confirmation": "Password123!"})
check("change-password back to original", s == 200 and j.get("success"), f"status={s} body={j}")

s, j = req(user, "POST", "/auth/logout")
check("POST /auth/logout", s == 200 and j.get("success"), f"status={s}")

s, j = req(user, "GET", "/user/dashboard")
check("after logout /user/dashboard -> 401", s == 401, f"status={s}")

# ------------------------------------------------------------------
section("COMPANY ENDPOINTS (role 2)")

comp = client()
s, j = req(comp, "POST", "/auth/login", {"email": "owner@pngdigital.com.pg", "password": "Password123!"})
check("login company role 2", s == 200 and j.get("success") and j["data"]["role"] == 2, f"status={s} body={j}")

s, j = req(comp, "GET", "/company/dashboard")
check("GET /company/dashboard", s == 200 and j.get("success") and "stats" in j["data"], f"status={s}")

s, j = req(comp, "GET", "/company/applications")
check("GET /company/applications", s == 200 and j.get("success"), f"status={s}")

s, j = req(comp, "GET", "/company/jobs")
check("GET /company/jobs", s == 200 and j.get("success"), f"status={s}")
comp_job_id = j["data"]["data"][0]["id"] if s == 200 and j.get("data", {}).get("data") else None

s, j = req(comp, "GET", f"/company/applications/{comp_job_id}")
check("GET /company/applications/{job}", s == 200 and j.get("success"), f"status={s}")

s, j = req(comp, "GET", "/company/verification")
check("GET /company/verification", s == 200 and j.get("success") and "verification_status" in j["data"], f"status={s}")

s, j = req(comp, "GET", "/company/settings")
check("GET /company/settings", s == 200 and j.get("success"), f"status={s}")

# NEW: settings update + revert
s, j = req(comp, "GET", "/company/settings")
orig_desc = j["data"].get("description", "")
s, j = req(comp, "POST", "/company/settings", {"name": "PNG Digital Solutions", "description": (orig_desc or "") + " [API-TEST]"})
check("POST /company/settings update (NEW)", s == 200 and j.get("success"), f"status={s} body={str(j)[:200]}")
s, j = req(comp, "POST", "/company/settings", {"name": "PNG Digital Solutions", "description": orig_desc})
check("POST /company/settings revert", s == 200 and j.get("success"), f"status={s}")

# job CRUD round-trip
s, j = req(comp, "POST", "/company/jobs", {
    "title": "API Test Job", "description": "Created by the API test suite for verification.",
    "location": "Port Moresby", "salary": "5000", "duration": 30})
check("POST /company/jobs store", s == 201 and j.get("success"), f"status={s} body={j}")
new_job_id = j["data"]["id"] if s == 201 and j.get("data") else None

s, j = req(comp, "GET", f"/company/jobs/{new_job_id}")
check("GET /company/jobs/{id} show", s == 200 and j.get("success"), f"status={s}")

s, j = req(comp, "PUT", f"/company/jobs/{new_job_id}", {
    "title": "API Test Job Updated", "description": "Updated by the API test suite.",
    "location": "Lae", "salary": "6000"})
check("PUT /company/jobs/{id} update", s == 200 and j.get("success") and j["data"]["title"] == "API Test Job Updated", f"status={s} body={str(j)[:200]}")

s, j = req(comp, "PATCH", f"/company/jobs/{new_job_id}/toggle")
check("PATCH /company/jobs/{id}/toggle", s == 200 and j.get("success"), f"status={s}")

s, j = req(comp, "DELETE", f"/company/jobs/{new_job_id}")
check("DELETE /company/jobs/{id}", s == 200 and j.get("success"), f"status={s}")

# stage update on an existing application (if any)
s, j = req(comp, "GET", "/company/applications")
apps = j["data"]["data"] if s == 200 and j.get("data", {}).get("data") else []
if apps:
    aid = apps[0]["id"]
    s, j = req(comp, "PATCH", f"/company/application/{aid}/stage", {"stage": 2})
    check("PATCH /company/application/{id}/stage", s == 200 and j.get("success"), f"status={s} body={j}")
    s, j = req(comp, "PATCH", f"/company/application/{aid}/stage", {"stage": apps[0]["status_id"]})
    check("stage reverted", s == 200 and j.get("success"), f"status={s}")
else:
    check("PATCH /company/application/{id}/stage — skipped, no applications", True, "no applications")

# ------------------------------------------------------------------
section("ADMIN ENDPOINTS (role 1)")

admin = client()
s, j = req(admin, "POST", "/auth/login", {"email": "admin@biraconsultants.com", "password": "Admin@2025!"})
check("login admin role 1", s == 200 and j.get("success") and j["data"]["role"] == 1, f"status={s} body={j}")

s, j = req(admin, "GET", "/admin/dashboard")
check("GET /admin/dashboard", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/jobs")
check("GET /admin/jobs", s == 200 and j.get("success"), f"status={s}")
admin_job_slug = j["data"]["data"][0]["slug"] if s == 200 and j.get("data", {}).get("data") else ""

s, j = req(admin, "GET", f"/admin/jobs/{admin_job_slug}")
check("GET /admin/jobs/{slug}", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/companies")
check("GET /admin/companies", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/users")
check("GET /admin/users", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/applications")
check("GET /admin/applications", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/blogs")
check("GET /admin/blogs", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/settings")
check("GET /admin/settings", s == 200 and j.get("success"), f"status={s}")

s, j = req(admin, "GET", "/admin/users/verification")
check("GET /admin/users/verification", s == 200 and j.get("success"), f"status={s}")

# approve/reject round-trip on a pending job if one exists
s, j = req(admin, "GET", "/admin/jobs?status=pending")
pend = j["data"]["data"] if s == 200 and j.get("data", {}).get("data") else []
if pend:
    pslug = pend[0]["slug"]
    s, j = req(admin, "PATCH", f"/admin/jobs/{pslug}/approve")
    check("PATCH /admin/jobs/{slug}/approve", s == 200 and j.get("success"), f"status={s} body={j}")
else:
    check("PATCH /admin/jobs/{slug}/approve — skipped, no pending jobs", True, "none pending")

# ------------------------------------------------------------------
section("RBAC NEGATIVE TESTS")

s, j = req(guest, "GET", "/user/dashboard")
check("guest /user/dashboard -> 401", s == 401, f"status={s}")

s, j = req(user, "GET", "/admin/dashboard")
check("role-0 /admin/dashboard -> 401/403", s in (401, 403), f"status={s}")

s, j = req(comp, "GET", "/admin/dashboard")
check("role-2 /admin/dashboard -> 401/403", s in (401, 403), f"status={s}")

s, j = req(user, "GET", "/company/dashboard")
check("role-0 /company/dashboard -> 401/403/404", s in (401, 403, 404), f"status={s}")

# ------------------------------------------------------------------
section("CLEANUP")

# delete the throwaway registered user via admin API
s, j = req(admin, "GET", f"/admin/users?search={stamp}.user@example.com")
rows = j["data"]["data"] if s == 200 and j.get("data", {}).get("data") else []
if rows:
    s, j = req(admin, "DELETE", f"/admin/users/{rows[0]['slug']}")
    check("cleanup: delete API test user", s == 200, f"status={s} body={j}")
else:
    check("cleanup: API test user not found (already gone)", True, "")

# ------------------------------------------------------------------
print("\n================ SUMMARY ================")
passed = sum(1 for r in RESULTS if r[0] == "PASS")
failed = sum(1 for r in RESULTS if r[0] == "FAIL")
print(f"TOTAL: {len(RESULTS)}  PASS: {passed}  FAIL: {failed}")
for status, name, detail in RESULTS:
    if status == "FAIL":
        print(f"  FAIL: {name}  -- {detail}")
sys.exit(1 if failed else 0)
