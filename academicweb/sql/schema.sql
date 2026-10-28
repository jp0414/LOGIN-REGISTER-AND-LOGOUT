-- ============================================================
-- BUSINESS TYPE:
-- ============================================================
-- >>> ACADEMIC TUTORING BUSINESS <<<
-- ============================================================

-- ============================================================
-- SERVICE PROVIDED:
--   The Academic Tutoring Business provides personalized academic
--   tutoring and mentoring sessions for students. Qualified tutors
--   offer targeted instruction in various subjects such as Math,
--   Physics, SAT Prep, and Languages.
-- ============================================================

-- ============================================================
-- ONE-TO-MANY RELATIONSHIP:
--   For ONE TUTOR, he/she can have/teach MANY SESSIONS
--   registered in the database.
--
--   tutors (1) ──────>> sessions (Many)
-- ============================================================

-- Create the database
CREATE DATABASE IF NOT EXISTS academic_tutoring_web_business;
USE academic_tutoring_web_business;

-- ============================================================
-- USERS TABLE: stores system users who can login/register
-- Each user has personal information columns
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    address VARCHAR(255),
    age INT,
    phone_number VARCHAR(50),
    date_registered DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- PARENT TABLE: tutors
-- Stores personal and professional information of tutors
-- added_by = user who created the record
-- last_updated = timestamp of last modification
-- ============================================================
CREATE TABLE IF NOT EXISTS tutors (
    tutor_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone_number VARCHAR(50),
    specialization VARCHAR(255) NOT NULL,
    hourly_rate DECIMAL(10, 2) NOT NULL,
    date_joined DATE NOT NULL DEFAULT (CURRENT_DATE),
    added_by INT,
    last_updated_by INT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (added_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (last_updated_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ============================================================
-- CHILD TABLE: sessions
-- Stores individual tutoring lessons conducted by tutors
-- ONE tutor can teach MANY sessions (One-to-Many)
-- added_by = user who created the record
-- last_updated_by = user who last updated the record
-- last_updated = timestamp of last modification
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    tutor_id INT NOT NULL,
    session_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    subject_taught VARCHAR(200) NOT NULL,
    notes TEXT,
    added_by INT,
    last_updated_by INT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tutor_id) REFERENCES tutors(tutor_id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (last_updated_by) REFERENCES users(user_id) ON DELETE SET NULL
);


