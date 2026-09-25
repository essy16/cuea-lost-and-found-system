USE cuea_lost_found;

DROP PROCEDURE IF EXISTS add_col_if_missing;
DELIMITER $$
CREATE PROCEDURE add_col_if_missing(IN tbl VARCHAR(64), IN col VARCHAR(64), IN definition_sql TEXT)
BEGIN
  IF NOT EXISTS (SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=tbl AND COLUMN_NAME=col) THEN
    SET @sql = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` ', definition_sql);
    PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
  END IF;
END$$
DELIMITER ;

CALL add_col_if_missing('users','phone','VARCHAR(50) NULL');
CALL add_col_if_missing('users','gender','VARCHAR(40) NULL');
CALL add_col_if_missing('users','physical_address','VARCHAR(255) NULL');
CALL add_col_if_missing('users','id_passport_no','VARCHAR(100) NULL');
CALL add_col_if_missing('users','can_report_items','TINYINT(1) NOT NULL DEFAULT 0');
CALL add_col_if_missing('users','can_issue_items','TINYINT(1) NOT NULL DEFAULT 0');
CALL add_col_if_missing('users','staff_no','VARCHAR(100) NULL');
ALTER TABLE users MODIFY reg_no VARCHAR(100) NULL;
UPDATE users SET can_report_items=1 WHERE role='staff';

SET @idx := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND INDEX_NAME='uq_users_staff_no');
SET @sql := IF(@idx=0,'ALTER TABLE users ADD UNIQUE KEY uq_users_staff_no (staff_no)','SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CALL add_col_if_missing('items','reporter_reg_no','VARCHAR(100) NULL');
CALL add_col_if_missing('items','reporter_staff_no','VARCHAR(100) NULL');
CALL add_col_if_missing('items','brand','VARCHAR(100) NULL');
CALL add_col_if_missing('items','model','VARCHAR(120) NULL');
CALL add_col_if_missing('items','color','VARCHAR(80) NULL');
CALL add_col_if_missing('items','serial_number','VARCHAR(150) NULL');
CALL add_col_if_missing('items','matched_lost_item_id','INT NULL');
ALTER TABLE items MODIFY description TEXT NULL;
ALTER TABLE items MODIFY status ENUM('pending','approved','claimed','issued','rejected') DEFAULT 'pending';

CALL add_col_if_missing('claims','claimant_type',"ENUM('student','staff','other') NULL");
CALL add_col_if_missing('claims','id_passport_no','VARCHAR(100) NULL');
CALL add_col_if_missing('claims','registration_no','VARCHAR(100) NULL');
CALL add_col_if_missing('claims','staff_no','VARCHAR(100) NULL');
CALL add_col_if_missing('claims','physical_address','VARCHAR(255) NULL');
CALL add_col_if_missing('claims','gender','VARCHAR(40) NULL');
CALL add_col_if_missing('claims','police_abstract_file','VARCHAR(255) NULL');
CALL add_col_if_missing('claims','captured_by_type','VARCHAR(30) NULL');
CALL add_col_if_missing('claims','captured_by_id','INT NULL');
CALL add_col_if_missing('claims','captured_by_name','VARCHAR(150) NULL');
CALL add_col_if_missing('claims','verified_onsite','TINYINT(1) NOT NULL DEFAULT 0');
CALL add_col_if_missing('claims','reviewed_by_staff_id','INT NULL');
CALL add_col_if_missing('claims','reviewed_by_admin_id','INT NULL');
CALL add_col_if_missing('claims','reviewed_at','DATETIME NULL');
CALL add_col_if_missing('claims','issued_at','DATETIME NULL');
ALTER TABLE claims MODIFY claimant_email VARCHAR(150) NULL;
ALTER TABLE claims MODIFY message TEXT NULL;
ALTER TABLE claims MODIFY status ENUM('pending','approved','rejected','issued') DEFAULT 'pending';

CREATE TABLE IF NOT EXISTS issued_items (
 id INT AUTO_INCREMENT PRIMARY KEY,item_id INT NOT NULL,claim_id INT NOT NULL,issued_to_user_id INT NULL,issued_to_name VARCHAR(150) NOT NULL,issued_by_staff_id INT NULL,issued_by_admin_id INT NULL,issued_by_name VARCHAR(150) NOT NULL,issuer_role ENUM('staff','admin') NOT NULL,verification_notes TEXT NULL,issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,UNIQUE KEY uq_claim_issue(claim_id)
);
CREATE TABLE IF NOT EXISTS activity_logs (id INT AUTO_INCREMENT PRIMARY KEY,actor_type VARCHAR(30) NOT NULL,actor_id INT NULL,actor_name VARCHAR(150) NOT NULL,action VARCHAR(150) NOT NULL,details TEXT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);

UPDATE users SET staff_no='CUEA/SF/001' WHERE role='staff' AND email='staff@cuea.edu' AND staff_no IS NULL;
DROP PROCEDURE IF EXISTS add_col_if_missing;
