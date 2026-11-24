-- Full SQLite dump for Bira project, including maintenance mode setting
-- Generated for deployment purposes

PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;

CREATE TABLE "bira_settings" ("id" integer primary key autoincrement not null, "key" varchar not null, "value" text, "created_at" datetime, "updated_at" datetime);

INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('1', 'site_name', 'Bira Consultants', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('2', 'site_description', 'Your Gateway to Better Career Opportunities', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('3', 'contact_email', 'info@biraconsultants.com', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('4', 'contact_phone', '+675 321 0000', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('5', 'jobs_per_page', '12', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('6', 'enable_registration', '1', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('7', 'enable_company_registration', '1', '2026-06-02 23:54:55', '2026-06-02 23:54:55');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('8', 'site_logo', '/images/logo.png', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('9', 'default_language', 'en', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('10', 'timezone', 'Pacific/Port_Moresby', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('11', 'date_format', 'd/m/Y', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('12', 'default_company_logo', '/images/company-default.png', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('13', 'max_companies_per_user', '3', '2026-06-03 00:24:05', '2026-06-03 00:24:05');
-- NEW: maintenance mode toggle (0 = off, 1 = on)
INSERT INTO 'bira_settings' ('id', 'key', 'value', 'created_at', 'updated_at') VALUES ('14', 'maintenance_mode', '0', datetime('now'), datetime('now'));

-- (Other tables would follow here – omitted for brevity; import your existing dump after this line)

COMMIT;
PRAGMA foreign_keys=ON;