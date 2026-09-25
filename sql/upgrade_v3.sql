USE cuea_lost_found;

-- Student registration/profile fields and staff permissions.
ALTER TABLE users
  ADD COLUMN IF NOT EXISTS phone VARCHAR(50) NULL AFTER reg_no,
  ADD COLUMN IF NOT EXISTS gender VARCHAR(40) NULL AFTER phone,
  ADD COLUMN IF NOT EXISTS physical_address VARCHAR(255) NULL AFTER gender,
  ADD COLUMN IF NOT EXISTS id_passport_no VARCHAR(100) NULL AFTER physical_address,
  ADD COLUMN IF NOT EXISTS can_report_items TINYINT(1) NOT NULL DEFAULT 0 AFTER password,
  ADD COLUMN IF NOT EXISTS can_issue_items TINYINT(1) NOT NULL DEFAULT 0 AFTER can_report_items;

-- Keep a separate registration number for the person who actually lost/found the item.
ALTER TABLE items
  ADD COLUMN IF NOT EXISTS reporter_reg_no VARCHAR(100) NULL AFTER reporter_phone,
  ADD COLUMN IF NOT EXISTS brand VARCHAR(100) NULL AFTER image,
  ADD COLUMN IF NOT EXISTS model VARCHAR(120) NULL AFTER brand,
  ADD COLUMN IF NOT EXISTS color VARCHAR(80) NULL AFTER model,
  ADD COLUMN IF NOT EXISTS serial_number VARCHAR(150) NULL AFTER color;

-- Issue/collection register. The issuing person is explicitly recorded.
CREATE TABLE IF NOT EXISTS issued_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL,
  claim_id INT NOT NULL,
  issued_to_user_id INT NULL,
  issued_to_name VARCHAR(150) NOT NULL,
  issued_by_staff_id INT NULL,
  issued_by_admin_id INT NULL,
  issued_by_name VARCHAR(150) NOT NULL,
  issuer_role ENUM('staff','admin') NOT NULL,
  verification_notes TEXT NULL,
  issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_claim_issue (claim_id)
);

CREATE TABLE IF NOT EXISTS activity_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  actor_type VARCHAR(30) NOT NULL,
  actor_id INT NULL,
  actor_name VARCHAR(150) NOT NULL,
  action VARCHAR(150) NOT NULL,
  details TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Existing demo staff starts with both permissions enabled. Admin can change them.
UPDATE users SET can_report_items=1, can_issue_items=1
WHERE role='staff' AND email='staff@cuea.edu';
