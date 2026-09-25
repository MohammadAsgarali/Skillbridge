-- ============================================
-- SkillBridge Database Schema
-- Member 1 (Junaid) - Database Design
-- ============================================

CREATE DATABASE IF NOT EXISTS skillbridge;
USE skillbridge;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    college VARCHAR(150) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(100) DEFAULT 'General'
);

CREATE TABLE student_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_skill (student_id, skill_id)
);

CREATE TABLE opportunities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    company VARCHAR(150) NOT NULL,
    type ENUM('Internship','Project') DEFAULT 'Internship',
    description TEXT,
    location VARCHAR(100) DEFAULT 'Remote',
    duration VARCHAR(50) DEFAULT NULL,
    stipend VARCHAR(50) DEFAULT NULL,
    posted_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES admins(id) ON DELETE SET NULL
);

CREATE TABLE opportunity_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opportunity_id INT NOT NULL,
    skill_id INT NOT NULL,
    required_level ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
    FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    UNIQUE KEY unique_opp_skill (opportunity_id, skill_id)
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    opportunity_id INT NOT NULL,
    status ENUM('Pending','Shortlisted','Rejected','Selected') DEFAULT 'Pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (student_id, opportunity_id)
);

CREATE TABLE bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    opportunity_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (opportunity_id) REFERENCES opportunities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bookmark (student_id, opportunity_id)
);

-- Profile extension fields
ALTER TABLE students
    ADD COLUMN phone VARCHAR(30) DEFAULT NULL,
    ADD COLUMN course VARCHAR(120) DEFAULT NULL,
    ADD COLUMN graduation_year SMALLINT DEFAULT NULL,
    ADD COLUMN college_location VARCHAR(120) DEFAULT NULL,
    ADD COLUMN linkedin_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN github_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN instagram_url VARCHAR(255) DEFAULT NULL,
    ADD COLUMN portfolio_url VARCHAR(255) DEFAULT NULL;
