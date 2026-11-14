-- MySQL Database Schema & Demo Seed Data
-- School Management System (Demo)
-- Select target database (e.g. `u447123054_demo` or `school_management`) in phpMyAdmin before importing.
-- Or open the app directly in browser after setting config/database.php to auto-create & seed tables.

-- CREATE DATABASE IF NOT EXISTS `school_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `school_management`;

-- Drop tables in reverse FK dependency order
DROP TABLE IF EXISTS `exam_marks`;
DROP TABLE IF EXISTS `examinations`;
DROP TABLE IF EXISTS `timetables`;
DROP TABLE IF EXISTS `homework`;
DROP TABLE IF EXISTS `notices`;
DROP TABLE IF EXISTS `school_settings`;
DROP TABLE IF EXISTS `subjects`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `fees`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `classes`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `users`;

-- --------------------------------------------------------
-- Table structure for `users` (Admin & Accountant Auth)
-- --------------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Users (admin / admin123 | accountant / account123)
INSERT INTO `users` (`username`, `password`, `full_name`, `email`, `role`) VALUES
('admin', '$2y$10$ZeIEwZJkUAED/lVGdEPJh..SBznOtWBFkQEh9.xvWUv2QyBj.XBL.', 'System Administrator', 'admin@schoolerp.demo', 'admin'),
('accountant', '$2y$10$UQfNF/STUMq6yNsZpZz/KepTdyZs1K1PGZBCfgAXmZGhHL683.dDq', 'Chief Accountant / Fees Manager', 'accounts@schoolerp.demo', 'accountant');

-- --------------------------------------------------------
-- Table structure for `teachers`
-- --------------------------------------------------------
CREATE TABLE `teachers` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Teachers (Password for all demo teachers: teacher123)
INSERT INTO `teachers` (`teacher_id`, `name`, `subject`, `qualification`, `mobile`, `email`, `address`, `joining_date`, `status`, `username`, `password`) VALUES
('TCH-101', 'Dr. Rajesh Sharma', 'Mathematics', 'M.Sc., Ph.D.', '9876543210', 'rajesh.sharma@schoolerp.demo', '12 Park Street, Knowledge City', '2020-06-15', 'Active', 'rajesh', '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..'),
('TCH-102', 'Sunita Verma', 'English', 'M.A. English, B.Ed.', '9876543211', 'sunita.verma@schoolerp.demo', '45 Model Town, Knowledge City', '2021-04-10', 'Active', 'sunita', '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..'),
('TCH-103', 'Vikram Singh', 'Science & Physics', 'M.Sc. Physics', '9876543212', 'vikram.singh@schoolerp.demo', '88 Green Park, Knowledge City', '2019-08-01', 'Active', 'vikram', '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..'),
('TCH-104', 'Ananya Roy', 'Computer Science', 'M.Tech CSE', '9876543213', 'ananya.roy@schoolerp.demo', '102 Vasant Enclave, Knowledge City', '2022-01-15', 'Active', 'ananya', '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..'),
('TCH-105', 'Amitabh Kumar', 'Social Studies', 'M.A. History', '9876543214', 'amitabh.kumar@schoolerp.demo', '34 Central Avenue, Knowledge City', '2018-11-20', 'Active', 'amitabh', '$2y$10$6ANRmj12wMeHJwvcu2xQKOQAZob5u8RL01SRuNqJC2s9taLbc41..');

-- --------------------------------------------------------
-- Table structure for `classes`
-- --------------------------------------------------------
CREATE TABLE `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_name` VARCHAR(50) NOT NULL,
  `section` VARCHAR(20) NOT NULL,
  `teacher_id` INT DEFAULT NULL,
  `room_number` VARCHAR(30) NOT NULL,
  `academic_session` VARCHAR(30) NOT NULL DEFAULT '2026-2027',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Classes (PG to Class 12)
INSERT INTO `classes` (`class_name`, `section`, `teacher_id`, `room_number`, `academic_session`) VALUES
('PG', 'A', NULL, 'Room PG-1', '2026-2027'),
('Nursery', 'A', NULL, 'Room NUR-1', '2026-2027'),
('LKG', 'A', NULL, 'Room LKG-1', '2026-2027'),
('UKG', 'A', NULL, 'Room UKG-1', '2026-2027'),
('Class 1', 'A', NULL, 'Room 101', '2026-2027'),
('Class 2', 'A', NULL, 'Room 102', '2026-2027'),
('Class 3', 'A', NULL, 'Room 103', '2026-2027'),
('Class 4', 'A', NULL, 'Room 104', '2026-2027'),
('Class 5', 'A', NULL, 'Room 105', '2026-2027'),
('Class 6', 'A', NULL, 'Room 201', '2026-2027'),
('Class 7', 'A', NULL, 'Room 202', '2026-2027'),
('Class 8', 'A', 1, 'Room 203', '2026-2027'),
('Class 8', 'B', NULL, 'Room 204', '2026-2027'),
('Class 9', 'A', 2, 'Room 301', '2026-2027'),
('Class 9', 'B', 3, 'Room 302', '2026-2027'),
('Class 10', 'A', 4, 'Room 303', '2026-2027'),
('Class 10', 'B', 5, 'Room 304', '2026-2027'),
('Class 11', 'A', NULL, 'Room 401', '2026-2027'),
('Class 11', 'B', NULL, 'Room 402', '2026-2027'),
('Class 12', 'A', NULL, 'Room 403', '2026-2027'),
('Class 12', 'B', NULL, 'Room 404', '2026-2027');

-- --------------------------------------------------------
-- Table structure for `students`
-- --------------------------------------------------------
CREATE TABLE `students` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Students (Password for all demo students: student123)
INSERT INTO `students` (`admission_no`, `password`, `first_name`, `last_name`, `father_name`, `mother_name`, `dob`, `gender`, `class_id`, `section`, `mobile`, `email`, `address`, `admission_date`, `photo`, `status`) VALUES
('STU-2026-001', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Aarav', 'Mehta', 'Sanjay Mehta', 'Ritu Mehta', '2012-05-12', 'Male', 12, 'A', '9123456780', 'aarav.m@demo.edu', 'Flat 12, Sunrise Apts, Knowledge City', '2026-04-01', NULL, 'Active'),
('STU-2026-002', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Ananya', 'Sharma', 'Rakesh Sharma', 'Pooja Sharma', '2012-08-20', 'Female', 12, 'A', '9123456781', 'ananya.s@demo.edu', 'H.No 45, Sector 14, Knowledge City', '2026-04-01', NULL, 'Active'),
('STU-2026-003', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Rohan', 'Gupta', 'Deepak Gupta', 'Sunita Gupta', '2011-02-14', 'Male', 14, 'A', '9123456782', 'rohan.g@demo.edu', 'Plot 8, Central Sec 6, Knowledge City', '2026-04-02', NULL, 'Active'),
('STU-2026-004', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Priya', 'Singh', 'Mahesh Singh', 'Sarita Singh', '2011-11-05', 'Female', 14, 'A', '9123456783', 'priya.s@demo.edu', '56 Park Avenue, Knowledge City', '2026-04-02', NULL, 'Active'),
('STU-2026-005', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Dev', 'Joshi', 'Alok Joshi', 'Manju Joshi', '2011-09-18', 'Male', 15, 'B', '9123456784', 'dev.j@demo.edu', '19 Green Park Phase 1, Knowledge City', '2026-04-03', NULL, 'Active'),
('STU-2026-006', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Isha', 'Patel', 'Ketan Patel', 'Bhavna Patel', '2011-04-30', 'Female', 15, 'B', '9123456785', 'isha.p@demo.edu', '77 Maple Enclave, Knowledge City', '2026-04-03', NULL, 'Active'),
('STU-2026-007', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Kabir', 'Khanna', 'Vivek Khanna', 'Neha Khanna', '2010-07-22', 'Male', 16, 'A', '9123456786', 'kabir.k@demo.edu', '104 Lake View, Knowledge City', '2026-04-04', NULL, 'Active'),
('STU-2026-008', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Diya', 'Verma', 'Suresh Verma', 'Kavita Verma', '2010-12-10', 'Female', 16, 'A', '9123456787', 'diya.v@demo.edu', '88 Preet Enclave, Knowledge City', '2026-04-04', NULL, 'Active'),
('STU-2026-009', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Vihaan', 'Reddy', 'Prakash Reddy', 'Latha Reddy', '2010-03-15', 'Male', 17, 'B', '9123456788', 'vihaan.r@demo.edu', '21 Rose Garden, Knowledge City', '2026-04-05', NULL, 'Active'),
('STU-2026-010', '$2y$10$6NOah2ZqFlVMVX1hRgnmRuNcAplTTPgFKcg/HppEWpsdr2feczBeK', 'Saniya', 'Malhotra', 'Anil Malhotra', 'Seema Malhotra', '2010-10-08', 'Female', 17, 'B', '9123456789', 'saniya.m@demo.edu', '43 Ridge Colony, Knowledge City', '2026-04-05', NULL, 'Active');

-- --------------------------------------------------------
-- Table structure for `fees`
-- --------------------------------------------------------
CREATE TABLE `fees` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Fees Records
INSERT INTO `fees` (`student_id`, `class_id`, `admission_fee`, `annual_fee`, `tuition_fee`, `computer_fee`, `exam_fee`, `fee_type`, `total_amount`, `paid_amount`, `remaining_amount`, `paid_months`, `payment_date`, `payment_method`, `payment_status`, `remarks`) VALUES
(1, 12, 2500.00, 2500.00, 8000.00, 1000.00, 1000.00, 'Admission & Term', 15000.00, 15000.00, 0.00, 'April, May, June', '2026-04-10', 'UPI', 'Paid', 'First Quarter Fee Paid'),
(2, 12, 2500.00, 2500.00, 8000.00, 1000.00, 1000.00, 'Admission & Term', 15000.00, 10000.00, 5000.00, 'April, May', '2026-04-12', 'Cash', 'Partial', 'Remaining due next month'),
(3, 14, 3000.00, 3000.00, 10000.00, 1000.00, 1000.00, 'Admission & Term', 18000.00, 18000.00, 0.00, 'April, May, June, July', '2026-04-15', 'Card', 'Paid', 'Full Term Payment'),
(4, 14, 0.00, 0.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 18000.00, 0.00, 18000.00, 'May, June', '2026-04-16', 'Cash', 'Pending', 'Payment Pending Notice Sent'),
(5, 15, 0.00, 0.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 18000.00, 18000.00, 0.00, 'April, May, June', '2026-04-18', 'Bank Transfer', 'Paid', 'Full Payment Received'),
(6, 15, 0.00, 0.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 18000.00, 9000.00, 9000.00, 'May', '2026-04-20', 'UPI', 'Partial', '50% Installment Paid'),
(7, 16, 0.00, 4000.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 22000.00, 22000.00, 0.00, 'April, May, June, July, August', '2026-04-22', 'Card', 'Paid', 'Term 1 Fee Paid'),
(8, 16, 0.00, 4000.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 22000.00, 12000.00, 10000.00, 'May, June', '2026-04-25', 'Cash', 'Partial', 'Partial payment'),
(9, 17, 0.00, 4000.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 22000.00, 22000.00, 0.00, 'April, May, June', '2026-04-26', 'UPI', 'Paid', 'Cleared all dues'),
(10, 17, 0.00, 4000.00, 15000.00, 1500.00, 1500.00, 'Admission & Term', 22000.00, 0.00, 22000.00, 'June, July', '2026-04-27', 'Cash', 'Pending', 'Awaiting confirmation');

-- --------------------------------------------------------
-- Table structure for `attendance`
-- --------------------------------------------------------
CREATE TABLE `attendance` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Attendance Records
INSERT INTO `attendance` (`student_id`, `class_id`, `attendance_date`, `status`, `remarks`) VALUES
(1, 12, CURDATE(), 'Present', 'On time'),
(2, 12, CURDATE(), 'Present', 'On time'),
(3, 14, CURDATE(), 'Present', 'On time'),
(4, 14, CURDATE(), 'Absent', 'Sick Leave'),
(5, 15, CURDATE(), 'Present', 'On time'),
(6, 15, CURDATE(), 'Present', 'On time'),
(7, 16, CURDATE(), 'Present', 'On time'),
(8, 16, CURDATE(), 'Absent', 'Uninformed'),
(9, 17, CURDATE(), 'Present', 'On time'),
(10, 17, CURDATE(), 'Present', 'On time');

-- --------------------------------------------------------
-- Table structure for `subjects`
-- --------------------------------------------------------
CREATE TABLE `subjects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `subject_code` VARCHAR(30) NOT NULL UNIQUE,
  `class_id` INT DEFAULT NULL,
  `teacher_id` INT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Subjects (aligned to Class 8, 9, 10)
INSERT INTO `subjects` (`subject_name`, `subject_code`, `class_id`, `teacher_id`) VALUES
('Mathematics', 'MATH-8', 12, 1),
('English Literature', 'ENG-8', 12, 2),
('General Science', 'SCI-8', 12, 3),
('Mathematics', 'MATH-9', 14, 1),
('English Grammar', 'ENG-9', 14, 2),
('Physics & Chemistry', 'SCI-9', 14, 3),
('Computer Applications', 'CS-9', 15, 4),
('Mathematics', 'MATH-10', 16, 1),
('Science & Physics', 'SCI-10', 16, 3),
('Computer Science', 'CS-10', 17, 4);

-- --------------------------------------------------------
-- Table structure for `examinations`
-- --------------------------------------------------------
CREATE TABLE `examinations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `exam_name` VARCHAR(100) NOT NULL,
  `academic_session` VARCHAR(30) NOT NULL DEFAULT '2026-2027',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `status` ENUM('Scheduled', 'Ongoing', 'Completed', 'Published') DEFAULT 'Scheduled',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Examinations
INSERT INTO `examinations` (`exam_name`, `academic_session`, `start_date`, `end_date`, `status`) VALUES
('Mid-Term Examinations 2026', '2026-2027', '2026-10-05', '2026-10-15', 'Published'),
('Half-Yearly Examination', '2026-2027', '2026-12-01', '2026-12-15', 'Ongoing'),
('Final Annual Examinations 2027', '2026-2027', '2027-03-10', '2027-03-25', 'Scheduled');

-- --------------------------------------------------------
-- Table structure for `exam_marks`
-- --------------------------------------------------------
CREATE TABLE `exam_marks` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Exam Marks
INSERT INTO `exam_marks` (`exam_id`, `student_id`, `subject_id`, `marks_obtained`, `max_marks`, `grade`, `remarks`) VALUES
(1, 1, 1, 92.50, 100.00, 'A+', 'Outstanding performance'),
(1, 1, 2, 88.00, 100.00, 'A', 'Very good'),
(1, 1, 3, 95.00, 100.00, 'A+', 'Top scorer in class'),
(1, 2, 1, 78.50, 100.00, 'B+', 'Good effort'),
(1, 2, 2, 85.00, 100.00, 'A', 'Excellent writing'),
(1, 2, 3, 82.50, 100.00, 'A', 'Good conceptual clarity'),
(1, 3, 4, 86.00, 100.00, 'A', 'Well done'),
(1, 3, 5, 81.50, 100.00, 'A', 'Consistent progress'),
(1, 3, 6, 89.00, 100.00, 'A', 'Strong lab skills'),
(1, 4, 6, 74.00, 100.00, 'B+', 'Needs practice in numericals'),
(1, 5, 7, 94.00, 100.00, 'A+', 'Top scorer'),
(1, 7, 8, 91.00, 100.00, 'A+', 'Strong analytical skills'),
(1, 7, 9, 87.50, 100.00, 'A', 'Very good');

-- --------------------------------------------------------
-- Table structure for `timetables`
-- --------------------------------------------------------
CREATE TABLE `timetables` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Timetable Slots
INSERT INTO `timetables` (`class_id`, `subject_id`, `teacher_id`, `day_of_week`, `period_name`, `start_time`, `end_time`, `room_number`) VALUES
(12, 1, 1, 'Monday', 'Period 1', '08:30:00', '09:15:00', 'Room 203'),
(12, 2, 2, 'Monday', 'Period 2', '09:15:00', '10:00:00', 'Room 203'),
(12, 3, 3, 'Monday', 'Period 3', '10:15:00', '11:00:00', 'Room 203'),
(12, 1, 1, 'Tuesday', 'Period 1', '08:30:00', '09:15:00', 'Room 203'),
(12, 2, 2, 'Wednesday', 'Period 2', '09:15:00', '10:00:00', 'Room 203'),
(14, 4, 1, 'Monday', 'Period 1', '08:30:00', '09:15:00', 'Room 301'),
(15, 7, 4, 'Monday', 'Period 2', '09:15:00', '10:00:00', 'Computer Lab 1');

-- --------------------------------------------------------
-- Table structure for `homework`
-- --------------------------------------------------------
CREATE TABLE `homework` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Homework Tasks
INSERT INTO `homework` (`class_id`, `subject_id`, `teacher_id`, `title`, `description`, `issue_date`, `due_date`, `status`) VALUES
(12, 1, 1, 'Algebra Practice Worksheet Ex 4.2', 'Solve questions 1 to 15 from Chapter 4 Linear Equations in homework notebook.', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Active'),
(12, 2, 2, 'Essay Writing: Digital Learning & Sustainability', 'Write a 300-word essay on sustainable practices in daily school life.', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Active'),
(14, 6, 3, 'Physics Lab Report - Pendulum Motion', 'Complete observation table and graph calculations for simple pendulum experiment.', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 4 DAY), 'Active');

-- --------------------------------------------------------
-- Table structure for `notices`
-- --------------------------------------------------------
CREATE TABLE `notices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `target_role` ENUM('All', 'Admin', 'Teacher', 'Student') DEFAULT 'All',
  `author_name` VARCHAR(100) DEFAULT 'School Management',
  `is_important` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo Notices
INSERT INTO `notices` (`title`, `content`, `target_role`, `author_name`, `is_important`) VALUES
('Annual Sports Meet 2026 Schedule', 'The Annual School Sports Meet will be held on October 25th. All interested students must register with their class coordinator by Friday.', 'All', 'Principal Office', 1),
('Teacher Academic Review Meeting', 'Quarterly curriculum review meeting is scheduled for Friday at 3:30 PM in the main conference hall.', 'Teacher', 'Academic Coordinator', 0),
('Parent-Teacher Conference (PTM)', 'PTM for Mid-Term academic results will take place this Saturday from 9:00 AM to 1:00 PM.', 'Student', 'School Admin', 1);

-- --------------------------------------------------------
-- Table structure for `school_settings`
-- --------------------------------------------------------
CREATE TABLE `school_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Demo Settings
INSERT INTO `school_settings` (`setting_key`, `setting_value`) VALUES
('school_name', 'Demo Public School'),
('school_tagline', 'Smart Academic ERP Portal'),
('school_logo', ''),
('school_address', '123 Education Avenue, Sector 1, Knowledge City - 110001'),
('school_phone', '+91 98765 43210'),
('school_email', 'info@schoolerp.demo'),
('academic_session', '2026-2027'),
('currency_symbol', '₹');
