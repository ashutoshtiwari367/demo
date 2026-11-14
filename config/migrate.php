<?php
/**
 * Dynamic Database Migration & Demo Bootstrap Script
 * School Management System (Demo)
 */

function run_migrations($pdo) {
    try {
        // 1. Ensure Core Tables Exist (for zero-config fresh database setup)
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `role` VARCHAR(20) DEFAULT 'admin',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `teachers` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `teacher_id` VARCHAR(30) NOT NULL UNIQUE,
            `name` VARCHAR(100) NOT NULL,
            `subject` VARCHAR(100) NOT NULL,
            `qualification` VARCHAR(100) NOT NULL,
            `mobile` VARCHAR(20) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `address` TEXT,
            `joining_date` DATE NOT NULL,
            `status` ENUM('Active', 'Inactive') DEFAULT 'Active',
            `username` VARCHAR(50) DEFAULT NULL UNIQUE,
            `password` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `classes` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `class_name` VARCHAR(50) NOT NULL,
            `section` VARCHAR(20) NOT NULL,
            `teacher_id` INT DEFAULT NULL,
            `room_number` VARCHAR(30) NOT NULL,
            `academic_session` VARCHAR(30) NOT NULL DEFAULT '2026-2027',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `students` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `admission_no` VARCHAR(30) NOT NULL UNIQUE,
            `password` VARCHAR(255) DEFAULT NULL,
            `first_name` VARCHAR(50) NOT NULL,
            `last_name` VARCHAR(50) NOT NULL,
            `father_name` VARCHAR(100) NOT NULL,
            `mother_name` VARCHAR(100) NOT NULL,
            `dob` DATE NOT NULL,
            `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
            `class_id` INT NOT NULL,
            `section` VARCHAR(20) NOT NULL,
            `mobile` VARCHAR(20) NOT NULL,
            `email` VARCHAR(100) DEFAULT NULL,
            `address` TEXT,
            `admission_date` DATE NOT NULL,
            `photo` VARCHAR(255) DEFAULT NULL,
            `status` ENUM('Active', 'Inactive', 'Graduated') DEFAULT 'Active',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `fees` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `student_id` INT NOT NULL,
            `class_id` INT NOT NULL,
            `registration_fee` DECIMAL(10,2) DEFAULT 0.00,
            `admission_fee` DECIMAL(10,2) DEFAULT 0.00,
            `annual_fee` DECIMAL(10,2) DEFAULT 0.00,
            `tuition_fee` DECIMAL(10,2) DEFAULT 0.00,
            `library_fee` DECIMAL(10,2) DEFAULT 0.00,
            `computer_fee` DECIMAL(10,2) DEFAULT 0.00,
            `activity_fee` DECIMAL(10,2) DEFAULT 0.00,
            `magazine_fee` DECIMAL(10,2) DEFAULT 0.00,
            `exam_fee` DECIMAL(10,2) DEFAULT 0.00,
            `penalty_fee` DECIMAL(10,2) DEFAULT 0.00,
            `other_fee` DECIMAL(10,2) DEFAULT 0.00,
            `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
            `fee_type` VARCHAR(50) DEFAULT 'Admission & Term',
            `total_amount` DECIMAL(10,2) NOT NULL,
            `paid_amount` DECIMAL(10,2) NOT NULL,
            `remaining_amount` DECIMAL(10,2) NOT NULL,
            `paid_months` VARCHAR(255) DEFAULT 'April, May',
            `payment_date` DATE NOT NULL,
            `payment_method` ENUM('Cash', 'UPI', 'Card', 'Bank Transfer', 'Cheque') DEFAULT 'Cash',
            `payment_status` ENUM('Paid', 'Partial', 'Pending') NOT NULL,
            `remarks` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `attendance` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `student_id` INT NOT NULL,
            `class_id` INT NOT NULL,
            `attendance_date` DATE NOT NULL,
            `status` ENUM('Present', 'Absent', 'Late') NOT NULL,
            `remarks` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `unique_student_date` (`student_id`, `attendance_date`),
            FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `subjects` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `subject_name` VARCHAR(100) NOT NULL,
            `subject_code` VARCHAR(30) NOT NULL UNIQUE,
            `class_id` INT DEFAULT NULL,
            `teacher_id` INT DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
            FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `examinations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `exam_name` VARCHAR(100) NOT NULL,
            `academic_session` VARCHAR(30) NOT NULL DEFAULT '2026-2027',
            `start_date` DATE NOT NULL,
            `end_date` DATE NOT NULL,
            `status` ENUM('Scheduled', 'Ongoing', 'Completed', 'Published') DEFAULT 'Scheduled',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `exam_marks` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `exam_id` INT NOT NULL,
            `student_id` INT NOT NULL,
            `subject_id` INT NOT NULL,
            `marks_obtained` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
            `max_marks` DECIMAL(5,2) NOT NULL DEFAULT 100.00,
            `grade` VARCHAR(10) DEFAULT NULL,
            `remarks` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `unique_exam_student_subject` (`exam_id`, `student_id`, `subject_id`),
            FOREIGN KEY (`exam_id`) REFERENCES `examinations`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `timetables` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `class_id` INT NOT NULL,
            `subject_id` INT NOT NULL,
            `teacher_id` INT DEFAULT NULL,
            `day_of_week` ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
            `period_name` VARCHAR(50) NOT NULL DEFAULT 'Period 1',
            `start_time` TIME NOT NULL,
            `end_time` TIME NOT NULL,
            `room_number` VARCHAR(30) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `homework` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `class_id` INT NOT NULL,
            `subject_id` INT NOT NULL,
            `teacher_id` INT DEFAULT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `issue_date` DATE NOT NULL,
            `due_date` DATE NOT NULL,
            `status` ENUM('Active', 'Completed', 'Archived') DEFAULT 'Active',
            `file_path` VARCHAR(255) DEFAULT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `notices` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `target_role` ENUM('All', 'Admin', 'Teacher', 'Student') DEFAULT 'All',
            `author_name` VARCHAR(100) DEFAULT 'School Management',
            `is_important` TINYINT(1) DEFAULT 0,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $pdo->exec("CREATE TABLE IF NOT EXISTS `school_settings` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `setting_key` VARCHAR(100) NOT NULL UNIQUE,
            `setting_value` TEXT DEFAULT NULL,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // 2. Column Compatibility Checks for Existing / Legacy Imports
        $cols = $pdo->query("SHOW COLUMNS FROM `students` LIKE 'password'")->fetchAll();
        if (empty($cols)) {
            $pdo->exec("ALTER TABLE `students` ADD COLUMN `password` VARCHAR(255) DEFAULT NULL AFTER `admission_no`");
        }

        $all_fee_columns = [
            'registration_fee' => 'DECIMAL(10,2) DEFAULT 0.00',
            'admission_fee'    => 'DECIMAL(10,2) DEFAULT 0.00',
            'annual_fee'       => 'DECIMAL(10,2) DEFAULT 0.00',
            'tuition_fee'      => 'DECIMAL(10,2) DEFAULT 0.00',
            'library_fee'      => 'DECIMAL(10,2) DEFAULT 0.00',
            'computer_fee'     => 'DECIMAL(10,2) DEFAULT 0.00',
            'activity_fee'     => 'DECIMAL(10,2) DEFAULT 0.00',
            'magazine_fee'     => 'DECIMAL(10,2) DEFAULT 0.00',
            'exam_fee'         => 'DECIMAL(10,2) DEFAULT 0.00',
            'penalty_fee'      => 'DECIMAL(10,2) DEFAULT 0.00',
            'other_fee'        => 'DECIMAL(10,2) DEFAULT 0.00',
            'discount_amount'  => 'DECIMAL(10,2) DEFAULT 0.00',
            'fee_type'         => "VARCHAR(50) DEFAULT 'Admission & Term'"
        ];
        foreach ($all_fee_columns as $col_name => $col_def) {
            if (empty($pdo->query("SHOW COLUMNS FROM `fees` LIKE '{$col_name}'")->fetchAll())) {
                $pdo->exec("ALTER TABLE `fees` ADD COLUMN `{$col_name}` {$col_def}");
            }
        }

        $exam_session_cols = $pdo->query("SHOW COLUMNS FROM `examinations` LIKE 'academic_session'")->fetchAll();
        $old_exam_session_cols = $pdo->query("SHOW COLUMNS FROM `examinations` LIKE 'session'")->fetchAll();
        if (empty($exam_session_cols)) {
            $pdo->exec("ALTER TABLE `examinations` ADD COLUMN `academic_session` VARCHAR(30) NOT NULL DEFAULT '2026-2027' AFTER `exam_name`");
            if (!empty($old_exam_session_cols)) {
                $pdo->exec("UPDATE `examinations` SET `academic_session` = `session`");
            }
        }
        if (!empty($old_exam_session_cols)) {
            $pdo->exec("ALTER TABLE `examinations` MODIFY COLUMN `session` VARCHAR(30) DEFAULT '2026-2027'");
        }
        $exam_status_col = $pdo->query("SHOW COLUMNS FROM `examinations` LIKE 'status'")->fetch();
        if ($exam_status_col && strpos($exam_status_col['Type'], 'Published') === false) {
            $pdo->exec("ALTER TABLE `examinations` MODIFY COLUMN `status` ENUM('Scheduled', 'Ongoing', 'Completed', 'Published', 'Upcoming') DEFAULT 'Scheduled'");
        }

        if (empty($pdo->query("SHOW COLUMNS FROM `timetables` LIKE 'period_name'")->fetchAll())) {
            $pdo->exec("ALTER TABLE `timetables` ADD COLUMN `period_name` VARCHAR(50) NOT NULL DEFAULT 'Period 1' AFTER `day_of_week`");
        }

        $old_hw_date_cols = $pdo->query("SHOW COLUMNS FROM `homework` LIKE 'assigned_date'")->fetchAll();
        if (empty($pdo->query("SHOW COLUMNS FROM `homework` LIKE 'issue_date'")->fetchAll())) {
            $pdo->exec("ALTER TABLE `homework` ADD COLUMN `issue_date` DATE DEFAULT NULL AFTER `description`");
            if (!empty($old_hw_date_cols)) {
                $pdo->exec("UPDATE `homework` SET `issue_date` = `assigned_date` WHERE `issue_date` IS NULL");
            } else {
                $pdo->exec("UPDATE `homework` SET `issue_date` = CURDATE() WHERE `issue_date` IS NULL");
            }
        }
        if (!empty($old_hw_date_cols)) {
            $pdo->exec("ALTER TABLE `homework` MODIFY COLUMN `assigned_date` DATE DEFAULT NULL");
        }
        if (empty($pdo->query("SHOW COLUMNS FROM `homework` LIKE 'status'")->fetchAll())) {
            $pdo->exec("ALTER TABLE `homework` ADD COLUMN `status` ENUM('Active', 'Completed', 'Archived') DEFAULT 'Active' AFTER `due_date`");
        }
        if (empty($pdo->query("SHOW COLUMNS FROM `homework` LIKE 'file_path'")->fetchAll())) {
            $pdo->exec("ALTER TABLE `homework` ADD COLUMN `file_path` VARCHAR(255) DEFAULT NULL AFTER `status`");
        }

        if (empty($pdo->query("SHOW COLUMNS FROM `notices` LIKE 'author_name'")->fetchAll())) {
            $pdo->exec("ALTER TABLE `notices` ADD COLUMN `author_name` VARCHAR(100) DEFAULT 'School Management' AFTER `target_role`");
        }
        $notice_role_col = $pdo->query("SHOW COLUMNS FROM `notices` LIKE 'target_role'")->fetch();
        if ($notice_role_col && strpos($notice_role_col['Type'], "'Admin'") === false) {
            $pdo->exec("ALTER TABLE `notices` MODIFY COLUMN `target_role` ENUM('All', 'Admin', 'Teacher', 'Student') DEFAULT 'All'");
        }

        // 3. Seed Users on Fresh Database (admin/admin123 & accountant/account123)
        $is_fresh_install = ((int)$pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn() === 0);
        $zero_state_row = $pdo->query("SELECT `setting_value` FROM `school_settings` WHERE `setting_key` = 'zero_state_reset' LIMIT 1")->fetch();
        $is_zero_reset = ($zero_state_row && ($zero_state_row['setting_value'] ?? '') === '1');

        if ($is_fresh_install) {
            $admin_pwd = '$2y$10$ZeIEwZJkUAED/lVGdEPJh..SBznOtWBFkQEh9.xvWUv2QyBj.XBL.';
            $acc_pwd   = '$2y$10$UQfNF/STUMq6yNsZpZz/KepTdyZs1K1PGZBCfgAXmZGhHL683.dDq';
            $stmt = $pdo->prepare("INSERT IGNORE INTO `users` (`username`, `password`, `full_name`, `email`, `role`) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['admin', $admin_pwd, 'System Administrator', 'admin@schoolerp.demo', 'admin']);
            $stmt->execute(['accountant', $acc_pwd, 'Chief Accountant / Fees Manager', 'accounts@schoolerp.demo', 'accountant']);
        } else {
            // Ensure accountant login exists for demo switcher and fix legacy mismatched hash
            $accountant_user = $pdo->query("SELECT id, password FROM `users` WHERE `username` = 'accountant' LIMIT 1")->fetch();
            $acc_pwd = '$2y$10$UQfNF/STUMq6yNsZpZz/KepTdyZs1K1PGZBCfgAXmZGhHL683.dDq';
            if (!$accountant_user) {
                $stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `full_name`, `email`, `role`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute(['accountant', $acc_pwd, 'Chief Accountant / Fees Manager', 'accounts@schoolerp.demo', 'accountant']);
            } elseif ($accountant_user['password'] === '$2y$10$wHzMqLXVGhb7OB4HAVm97eRtgFo0VDvWGwY8Go//10VGlMug3.eW2') {
                $upd_acc = $pdo->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
                $upd_acc->execute([$acc_pwd, $accountant_user['id']]);
            }
            // Replace any leftover old school emails in users table
            $pdo->exec("UPDATE `users` SET `email` = 'admin@schoolerp.demo' WHERE `email` = 'admin@nextgenps.edu.in'");
            $pdo->exec("UPDATE `users` SET `email` = 'accounts@schoolerp.demo' WHERE `email` IN ('accounts@nextgenps.edu.in', 'accounts@rdmakids.edu')");
        }

        // 4. Seed Demo Teachers if empty
        if ((int)$pdo->query("SELECT COUNT(*) FROM `teachers`")->fetchColumn() === 0) {
            $teach_pwd = '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..';
            $t_stmt = $pdo->prepare("INSERT INTO `teachers` (`teacher_id`, `name`, `subject`, `qualification`, `mobile`, `email`, `address`, `joining_date`, `status`, `username`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active', ?, ?)");
            $sample_teachers = [
                ['TCH-101', 'Dr. Rajesh Sharma', 'Mathematics', 'M.Sc., Ph.D.', '9876543210', 'rajesh.sharma@schoolerp.demo', '12 Park Street, Knowledge City', '2020-06-15', 'rajesh', $teach_pwd],
                ['TCH-102', 'Sunita Verma', 'English', 'M.A. English, B.Ed.', '9876543211', 'sunita.verma@schoolerp.demo', '45 Model Town, Knowledge City', '2021-04-10', 'sunita', $teach_pwd],
                ['TCH-103', 'Vikram Singh', 'Science & Physics', 'M.Sc. Physics', '9876543212', 'vikram.singh@schoolerp.demo', '88 Green Park, Knowledge City', '2019-08-01', 'vikram', $teach_pwd],
                ['TCH-104', 'Ananya Roy', 'Computer Science', 'M.Tech CSE', '9876543213', 'ananya.roy@schoolerp.demo', '102 Vasant Enclave, Knowledge City', '2022-01-15', 'ananya', $teach_pwd],
                ['TCH-105', 'Amitabh Kumar', 'Social Studies', 'M.A. History', '9876543214', 'amitabh.kumar@schoolerp.demo', '34 Central Avenue, Knowledge City', '2018-11-20', 'amitabh', $teach_pwd],
            ];
            foreach ($sample_teachers as $st) {
                $t_stmt->execute($st);
            }
        }

        // 5. Ensure Complete Classes (PG to Class 12) Exist
        $required_classes = [
            ['class_name' => 'PG', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room PG-1'],
            ['class_name' => 'Nursery', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room NUR-1'],
            ['class_name' => 'LKG', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room LKG-1'],
            ['class_name' => 'UKG', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room UKG-1'],
            ['class_name' => 'Class 1', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 101'],
            ['class_name' => 'Class 2', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 102'],
            ['class_name' => 'Class 3', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 103'],
            ['class_name' => 'Class 4', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 104'],
            ['class_name' => 'Class 5', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 105'],
            ['class_name' => 'Class 6', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 201'],
            ['class_name' => 'Class 7', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 202'],
            ['class_name' => 'Class 8', 'section' => 'A', 'teacher_id' => 1, 'room_number' => 'Room 203'],
            ['class_name' => 'Class 8', 'section' => 'B', 'teacher_id' => null, 'room_number' => 'Room 204'],
            ['class_name' => 'Class 9', 'section' => 'A', 'teacher_id' => 2, 'room_number' => 'Room 301'],
            ['class_name' => 'Class 9', 'section' => 'B', 'teacher_id' => 3, 'room_number' => 'Room 302'],
            ['class_name' => 'Class 10', 'section' => 'A', 'teacher_id' => 4, 'room_number' => 'Room 303'],
            ['class_name' => 'Class 10', 'section' => 'B', 'teacher_id' => 5, 'room_number' => 'Room 304'],
            ['class_name' => 'Class 11', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 401'],
            ['class_name' => 'Class 11', 'section' => 'B', 'teacher_id' => null, 'room_number' => 'Room 402'],
            ['class_name' => 'Class 12', 'section' => 'A', 'teacher_id' => null, 'room_number' => 'Room 403'],
            ['class_name' => 'Class 12', 'section' => 'B', 'teacher_id' => null, 'room_number' => 'Room 404'],
        ];

        $check_class_stmt = $pdo->prepare("SELECT id FROM classes WHERE class_name = :c AND section = :s LIMIT 1");
        $insert_class_stmt = $pdo->prepare("INSERT INTO classes (class_name, section, teacher_id, room_number, academic_session) VALUES (:c, :s, :t, :r, '2026-2027')");
        $class_id_map = [];
        foreach ($required_classes as $rc) {
            $check_class_stmt->execute(['c' => $rc['class_name'], 's' => $rc['section']]);
            $existing = $check_class_stmt->fetch();
            if (!$existing) {
                $insert_class_stmt->execute([
                    'c' => $rc['class_name'],
                    's' => $rc['section'],
                    't' => $rc['teacher_id'],
                    'r' => $rc['room_number']
                ]);
                $class_id_map[$rc['class_name'] . '|' . $rc['section']] = (int)$pdo->lastInsertId();
            } else {
                $class_id_map[$rc['class_name'] . '|' . $rc['section']] = (int)$existing['id'];
            }
        }

        $c8a  = $class_id_map['Class 8|A']  ?? 1;
        $c9a  = $class_id_map['Class 9|A']  ?? $c8a;
        $c9b  = $class_id_map['Class 9|B']  ?? $c8a;
        $c10a = $class_id_map['Class 10|A'] ?? $c8a;
        $c10b = $class_id_map['Class 10|B'] ?? $c8a;

        // 6. Seed Subjects if empty (mapped accurately to Class 8, 9, 10)
        if ((int)$pdo->query("SELECT COUNT(*) FROM `subjects`")->fetchColumn() === 0) {
            $sub_stmt = $pdo->prepare("INSERT INTO `subjects` (`subject_name`, `subject_code`, `class_id`, `teacher_id`) VALUES (?, ?, ?, ?)");
            $demo_subjects = [
                ['Mathematics', 'MATH-8', $c8a, 1],
                ['English Literature', 'ENG-8', $c8a, 2],
                ['General Science', 'SCI-8', $c8a, 3],
                ['Mathematics', 'MATH-9', $c9a, 1],
                ['English Grammar', 'ENG-9', $c9a, 2],
                ['Physics & Chemistry', 'SCI-9', $c9a, 3],
                ['Computer Applications', 'CS-9', $c9b, 4],
                ['Mathematics', 'MATH-10', $c10a, 1],
                ['Science & Physics', 'SCI-10', $c10a, 3],
                ['Computer Science', 'CS-10', $c10b, 4],
            ];
            foreach ($demo_subjects as $sub) {
                $sub_stmt->execute($sub);
            }
        }

        // Map subject codes to IDs
        $sub_ids = [];
        foreach ($pdo->query("SELECT id, subject_code FROM `subjects`")->fetchAll() as $row) {
            $sub_ids[$row['subject_code']] = (int)$row['id'];
        }
        $s_math8 = $sub_ids['MATH-8'] ?? 1;
        $s_eng8  = $sub_ids['ENG-8']  ?? $s_math8;
        $s_sci8  = $sub_ids['SCI-8']  ?? $s_math8;
        $s_math9 = $sub_ids['MATH-9'] ?? $s_math8;
        $s_eng9  = $sub_ids['ENG-9']  ?? $s_math8;
        $s_sci9  = $sub_ids['SCI-9']  ?? $s_math8;
        $s_cs9   = $sub_ids['CS-9']   ?? $s_math8;
        $s_math10 = $sub_ids['MATH-10'] ?? $s_math8;
        $s_sci10  = $sub_ids['SCI-10']  ?? $s_math8;

        // 7. Seed Demo Academic & Portal Data (fresh install or non-reset empty database)
        $student_count = (int)$pdo->query("SELECT COUNT(*) FROM `students`")->fetchColumn();
        if ($is_fresh_install || ($student_count === 0 && !$is_zero_reset)) {
            $stu_pwd = '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK';
            if ($student_count === 0) {
                $stu_stmt = $pdo->prepare("INSERT INTO `students` (`admission_no`, `password`, `first_name`, `last_name`, `father_name`, `mother_name`, `dob`, `gender`, `class_id`, `section`, `mobile`, `email`, `address`, `admission_date`, `photo`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, 'Active')");
                $demo_students = [
                    ['STU-2026-001', $stu_pwd, 'Aarav', 'Mehta', 'Sanjay Mehta', 'Ritu Mehta', '2012-05-12', 'Male', $c8a, 'A', '9123456780', 'aarav.m@demo.edu', 'Flat 12, Sunrise Apts, Knowledge City', '2026-04-01'],
                    ['STU-2026-002', $stu_pwd, 'Ananya', 'Sharma', 'Rakesh Sharma', 'Pooja Sharma', '2012-08-20', 'Female', $c8a, 'A', '9123456781', 'ananya.s@demo.edu', 'H.No 45, Sector 14, Knowledge City', '2026-04-01'],
                    ['STU-2026-003', $stu_pwd, 'Rohan', 'Gupta', 'Deepak Gupta', 'Sunita Gupta', '2011-02-14', 'Male', $c9a, 'A', '9123456782', 'rohan.g@demo.edu', 'Plot 8, Central Sec 6, Knowledge City', '2026-04-02'],
                    ['STU-2026-004', $stu_pwd, 'Priya', 'Singh', 'Mahesh Singh', 'Sarita Singh', '2011-11-05', 'Female', $c9a, 'A', '9123456783', 'priya.s@demo.edu', '56 Park Avenue, Knowledge City', '2026-04-02'],
                    ['STU-2026-005', $stu_pwd, 'Dev', 'Joshi', 'Alok Joshi', 'Manju Joshi', '2011-09-18', 'Male', $c9b, 'B', '9123456784', 'dev.j@demo.edu', '19 Green Park Phase 1, Knowledge City', '2026-04-03'],
                    ['STU-2026-006', $stu_pwd, 'Isha', 'Patel', 'Ketan Patel', 'Bhavna Patel', '2011-04-30', 'Female', $c9b, 'B', '9123456785', 'isha.p@demo.edu', '77 Maple Enclave, Knowledge City', '2026-04-03'],
                    ['STU-2026-007', $stu_pwd, 'Kabir', 'Khanna', 'Vivek Khanna', 'Neha Khanna', '2010-07-22', 'Male', $c10a, 'A', '9123456786', 'kabir.k@demo.edu', '104 Lake View, Knowledge City', '2026-04-04'],
                    ['STU-2026-008', $stu_pwd, 'Diya', 'Verma', 'Suresh Verma', 'Kavita Verma', '2010-12-10', 'Female', $c10a, 'A', '9123456787', 'diya.v@demo.edu', '88 Preet Enclave, Knowledge City', '2026-04-04'],
                    ['STU-2026-009', $stu_pwd, 'Vihaan', 'Reddy', 'Prakash Reddy', 'Latha Reddy', '2010-03-15', 'Male', $c10b, 'B', '9123456788', 'vihaan.r@demo.edu', '21 Rose Garden, Knowledge City', '2026-04-05'],
                    ['STU-2026-010', $stu_pwd, 'Saniya', 'Malhotra', 'Anil Malhotra', 'Seema Malhotra', '2010-10-08', 'Female', $c10b, 'B', '9123456789', 'saniya.m@demo.edu', '43 Ridge Colony, Knowledge City', '2026-04-05'],
                ];
                foreach ($demo_students as $ds) {
                    $stu_stmt->execute($ds);
                }
            }

            if ((int)$pdo->query("SELECT COUNT(*) FROM `examinations`")->fetchColumn() === 0) {
                $pdo->exec("INSERT INTO `examinations` (`exam_name`, `academic_session`, `start_date`, `end_date`, `status`) VALUES
                    ('Mid-Term Examinations 2026', '2026-2027', '2026-10-05', '2026-10-15', 'Published'),
                    ('Half-Yearly Examination', '2026-2027', '2026-12-01', '2026-12-15', 'Ongoing'),
                    ('Final Annual Examinations 2027', '2026-2027', '2027-03-10', '2027-03-25', 'Scheduled')");
            }

            if ((int)$pdo->query("SELECT COUNT(*) FROM `timetables`")->fetchColumn() === 0) {
                $tt_stmt = $pdo->prepare("INSERT INTO `timetables` (`class_id`, `subject_id`, `teacher_id`, `day_of_week`, `period_name`, `start_time`, `end_time`, `room_number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $demo_tt = [
                    [$c8a, $s_math8, 1, 'Monday', 'Period 1', '08:30:00', '09:15:00', 'Room 203'],
                    [$c8a, $s_eng8,  2, 'Monday', 'Period 2', '09:15:00', '10:00:00', 'Room 203'],
                    [$c8a, $s_sci8,  3, 'Monday', 'Period 3', '10:15:00', '11:00:00', 'Room 203'],
                    [$c8a, $s_math8, 1, 'Tuesday', 'Period 1', '08:30:00', '09:15:00', 'Room 203'],
                    [$c8a, $s_eng8,  2, 'Wednesday', 'Period 2', '09:15:00', '10:00:00', 'Room 203'],
                    [$c9a, $s_math9, 1, 'Monday', 'Period 1', '08:30:00', '09:15:00', 'Room 301'],
                    [$c9b, $s_cs9,   4, 'Monday', 'Period 2', '09:15:00', '10:00:00', 'Computer Lab 1'],
                ];
                foreach ($demo_tt as $tt) {
                    $tt_stmt->execute($tt);
                }
            }

            if ((int)$pdo->query("SELECT COUNT(*) FROM `homework`")->fetchColumn() === 0) {
                $hw_stmt = $pdo->prepare("INSERT INTO `homework` (`class_id`, `subject_id`, `teacher_id`, `title`, `description`, `issue_date`, `due_date`, `status`) VALUES (?, ?, ?, ?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL ? DAY), 'Active')");
                $hw_stmt->execute([$c8a, $s_math8, 1, 'Algebra Practice Worksheet Ex 4.2', 'Solve questions 1 to 15 from Chapter 4 Linear Equations in homework notebook.', 2]);
                $hw_stmt->execute([$c8a, $s_eng8,  2, 'Essay Writing: Digital Learning & Sustainability', 'Write a 300-word essay on sustainable practices in daily school life.', 3]);
                $hw_stmt->execute([$c9a, $s_sci9,  3, 'Physics Lab Report - Pendulum Motion', 'Complete observation table and graph calculations for simple pendulum experiment.', 4]);
            }

            if ((int)$pdo->query("SELECT COUNT(*) FROM `notices`")->fetchColumn() === 0) {
                $pdo->exec("INSERT INTO `notices` (`title`, `content`, `target_role`, `author_name`, `is_important`) VALUES
                    ('Annual Sports Meet 2026 Schedule', 'The Annual School Sports Meet will be held on October 25th. All interested students must register with their class coordinator by Friday.', 'All', 'Principal Office', 1),
                    ('Teacher Academic Review Meeting', 'Quarterly curriculum review meeting is scheduled for Friday at 3:30 PM in the main conference hall.', 'Teacher', 'Academic Coordinator', 0),
                    ('Parent-Teacher Conference (PTM)', 'PTM for Mid-Term academic results will take place this Saturday from 9:00 AM to 1:00 PM.', 'Student', 'School Admin', 1)");
            }

            // Seed Fees, Attendance & Exam Marks matching inserted students
            $stu_rows = $pdo->query("SELECT id, admission_no, class_id FROM `students` ORDER BY id ASC")->fetchAll();
            if (!empty($stu_rows) && (int)$pdo->query("SELECT COUNT(*) FROM `fees`")->fetchColumn() === 0) {
                $fee_stmt = $pdo->prepare("INSERT INTO `fees` (`student_id`, `class_id`, `admission_fee`, `annual_fee`, `tuition_fee`, `computer_fee`, `exam_fee`, `fee_type`, `total_amount`, `paid_amount`, `remaining_amount`, `paid_months`, `payment_date`, `payment_method`, `payment_status`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, 'Admission & Term', ?, ?, ?, ?, ?, ?, ?, ?)");
                $att_stmt = $pdo->prepare("INSERT IGNORE INTO `attendance` (`student_id`, `class_id`, `attendance_date`, `status`, `remarks`) VALUES (?, ?, CURDATE(), ?, ?)");
                $fee_presets = [
                    [2500, 2500, 8000, 1000, 1000, 15000, 15000, 0, 'April, May, June', '2026-04-10', 'UPI', 'Paid', 'First Quarter Fee Paid', 'Present', 'On time'],
                    [2500, 2500, 8000, 1000, 1000, 15000, 10000, 5000, 'April, May', '2026-04-12', 'Cash', 'Partial', 'Remaining due next month', 'Present', 'On time'],
                    [3000, 3000, 10000, 1000, 1000, 18000, 18000, 0, 'April, May, June, July', '2026-04-15', 'Card', 'Paid', 'Full Term Payment', 'Present', 'On time'],
                    [0, 0, 15000, 1500, 1500, 18000, 0, 18000, 'May, June', '2026-04-16', 'Cash', 'Pending', 'Payment Pending Notice Sent', 'Absent', 'Sick Leave'],
                    [0, 0, 15000, 1500, 1500, 18000, 18000, 0, 'April, May, June', '2026-04-18', 'Bank Transfer', 'Paid', 'Full Payment Received', 'Present', 'On time'],
                    [0, 0, 15000, 1500, 1500, 18000, 9000, 9000, 'May', '2026-04-20', 'UPI', 'Partial', '50% Installment Paid', 'Present', 'On time'],
                    [0, 4000, 15000, 1500, 1500, 22000, 22000, 0, 'April, May, June, July, August', '2026-04-22', 'Card', 'Paid', 'Term 1 Fee Paid', 'Present', 'On time'],
                    [0, 4000, 15000, 1500, 1500, 22000, 12000, 10000, 'May, June', '2026-04-25', 'Cash', 'Partial', 'Partial payment', 'Absent', 'Uninformed'],
                    [0, 4000, 15000, 1500, 1500, 22000, 22000, 0, 'April, May, June', '2026-04-26', 'UPI', 'Paid', 'Cleared all dues', 'Present', 'On time'],
                    [0, 4000, 15000, 1500, 1500, 22000, 0, 22000, 'June, July', '2026-04-27', 'Cash', 'Pending', 'Awaiting confirmation', 'Present', 'On time'],
                ];
                foreach ($stu_rows as $idx => $srow) {
                    $p = $fee_presets[$idx % count($fee_presets)];
                    $fee_stmt->execute([$srow['id'], $srow['class_id'], $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8], $p[9], $p[10], $p[11], $p[12]]);
                    $att_stmt->execute([$srow['id'], $srow['class_id'], $p[13], $p[14]]);
                }
            }

            $first_exam_id = (int)$pdo->query("SELECT id FROM `examinations` ORDER BY id ASC LIMIT 1")->fetchColumn();
            if ($first_exam_id > 0 && count($stu_rows) >= 7 && (int)$pdo->query("SELECT COUNT(*) FROM `exam_marks`")->fetchColumn() === 0) {
                $mk_stmt = $pdo->prepare("INSERT IGNORE INTO `exam_marks` (`exam_id`, `student_id`, `subject_id`, `marks_obtained`, `max_marks`, `grade`, `remarks`) VALUES (?, ?, ?, ?, 100.00, ?, ?)");
                $marks_seed = [
                    [$first_exam_id, $stu_rows[0]['id'], $s_math8, 92.50, 'A+', 'Outstanding performance'],
                    [$first_exam_id, $stu_rows[0]['id'], $s_eng8,  88.00, 'A',  'Very good'],
                    [$first_exam_id, $stu_rows[0]['id'], $s_sci8,  95.00, 'A+', 'Top scorer in class'],
                    [$first_exam_id, $stu_rows[1]['id'], $s_math8, 78.50, 'B+', 'Good effort'],
                    [$first_exam_id, $stu_rows[1]['id'], $s_eng8,  85.00, 'A',  'Excellent writing'],
                    [$first_exam_id, $stu_rows[1]['id'], $s_sci8,  82.50, 'A',  'Good conceptual clarity'],
                    [$first_exam_id, $stu_rows[2]['id'], $s_math9, 86.00, 'A',  'Well done'],
                    [$first_exam_id, $stu_rows[2]['id'], $s_eng9,  81.50, 'A',  'Consistent progress'],
                    [$first_exam_id, $stu_rows[2]['id'], $s_sci9,  89.00, 'A',  'Strong lab skills'],
                    [$first_exam_id, $stu_rows[3]['id'], $s_sci9,  74.00, 'B+', 'Needs practice in numericals'],
                    [$first_exam_id, $stu_rows[4]['id'], $s_cs9,   94.00, 'A+', 'Top scorer'],
                    [$first_exam_id, $stu_rows[6]['id'], $s_math10, 91.00, 'A+', 'Strong analytical skills'],
                    [$first_exam_id, $stu_rows[6]['id'], $s_sci10,  87.50, 'A',  'Very good'],
                ];
                foreach ($marks_seed as $ms) {
                    $mk_stmt->execute($ms);
                }
            }
        }

        // 8. Ensure every student missing a password gets default 'student123'
        $default_stu_check = $pdo->query("SELECT COUNT(*) FROM `students` WHERE `password` IS NULL OR `password` = ''")->fetchColumn();
        if ((int)$default_stu_check > 0) {
            $default_student_pwd = '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK';
            $pdo->exec("UPDATE `students` SET `password` = '$default_student_pwd' WHERE `password` IS NULL OR `password` = ''");
        }

        // 9. Demo School Settings (insert defaults if missing; replace only old school branding)
        $default_settings = [
            'school_name'      => 'Demo Public School',
            'school_tagline'   => 'Smart Academic ERP Portal',
            'school_logo'      => '',
            'school_email'     => 'info@schoolerp.demo',
            'school_phone'     => '+91 98765 43210',
            'school_address'   => '123 Education Avenue, Sector 1, Knowledge City - 110001',
            'academic_session' => '2026-2027',
            'currency_symbol'  => '₹',
        ];
        $insert_setting_stmt = $pdo->prepare("INSERT IGNORE INTO `school_settings` (`setting_key`, `setting_value`) VALUES (:k, :v)");
        foreach ($default_settings as $k => $v) {
            $insert_setting_stmt->execute(['k' => $k, 'v' => $v]);
        }

        // One-time safe cleanup if database still holds old school-specific branding
        $legacy_setting_replacements = [
            'school_name'    => [['Next Generation Public School', 'NGPS', 'RDMA Kids • School Management System'], 'Demo Public School'],
            'school_tagline' => [['विद्या ददाति विनयं', 'Next Generation Public School', 'विद्या ददाति विनयं • CBSE AFFILIATED'], 'Smart Academic ERP Portal'],
            'school_logo'    => [['assets/images/logo_circle.png', 'assets/images/logo.png', 'assets/images/favicon.png'], ''],
            'school_email'   => [['contact@nextgenps.edu.in', 'contact@rdmakids.edu'], 'info@schoolerp.demo'],
            'school_address' => [['Next Generation Campus, Knowledge Enclave, New Delhi - 110001'], '123 Education Avenue, Sector 1, Knowledge City - 110001'],
        ];
        $upd_setting_stmt = $pdo->prepare("UPDATE `school_settings` SET `setting_value` = :new_v WHERE `setting_key` = :k AND `setting_value` = :old_v");
        foreach ($legacy_setting_replacements as $k => [$old_vals, $new_val]) {
            foreach ($old_vals as $ov) {
                $upd_setting_stmt->execute(['new_v' => $new_val, 'k' => $k, 'old_v' => $ov]);
            }
        }
    } catch (PDOException $e) {
        error_log("Migration notice: " . $e->getMessage());
    }
}
