-- CUEA Lost & Found V8 upgrade
-- Adds Visitor as a normal user category. Existing student/staff accounts are preserved.
ALTER TABLE users
  MODIFY COLUMN role ENUM('student','visitor','staff') NOT NULL DEFAULT 'student';
