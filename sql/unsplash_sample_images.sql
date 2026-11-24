-- Populate empty demo profiles and home-feed company placeholders; preserve existing values.
BEGIN IMMEDIATE;
UPDATE biraconsultant_users SET image = 'profiles/sample-user-03.jpg' WHERE id = 3 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-04.jpg' WHERE id = 4 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-05.jpg' WHERE id = 5 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-06.jpg' WHERE id = 6 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-07.jpg' WHERE id = 7 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-08.jpg' WHERE id = 8 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-09.jpg' WHERE id = 9 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_users SET image = 'profiles/sample-user-20.jpg' WHERE id = 20 AND role = 0 AND (image IS NULL OR image = '');
UPDATE biraconsultant_companies SET logo = 'logos/sample-company-01.jpg' WHERE id = 1 AND (logo IS NULL OR logo = '');
UPDATE biraconsultant_companies SET logo = 'logos/sample-company-02.jpg' WHERE id = 2 AND (logo IS NULL OR logo = '');
UPDATE biraconsultant_companies SET logo = 'logos/sample-company-03.jpg' WHERE id = 3 AND (logo IS NULL OR logo = '');
UPDATE biraconsultant_companies SET logo = 'logos/sample-company-04.jpg' WHERE id = 4 AND (logo IS NULL OR logo = '');
UPDATE biraconsultant_companies SET logo = 'logos/sample-company-05.jpg' WHERE id = 5 AND (logo IS NULL OR logo = '');
COMMIT;
