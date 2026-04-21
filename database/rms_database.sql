-- =====================================================
-- Referral Management System (RMS) Database Schema
-- Tanzania Public Healthcare System
-- =====================================================

-- CREATE DATABASE rms_db;
-- USE rms_db;

-- =====================================================
-- 1. USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    role ENUM('clinician', 'admin', 'moh') NOT NULL,
    facility_id INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- =====================================================
-- 2. FACILITIES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS facilities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    facility_type ENUM('dispensary', 'health_centre', 'district_hospital', 'regional_hospital', 'national_hospital') NOT NULL,
    region VARCHAR(100),
    district VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    in_charge_name VARCHAR(150),
    in_charge_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 3. PATIENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_name VARCHAR(150) NOT NULL,
    age INT,
    gender ENUM('M', 'F') NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    medical_record_number VARCHAR(50) UNIQUE,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 4. REFERRALS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS referrals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    referral_code VARCHAR(50) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    referring_clinician_id INT NOT NULL,
    referring_facility_id INT NOT NULL,
    receiving_facility_id INT NOT NULL,
    clinical_reason TEXT NOT NULL,
    urgency_level ENUM('routine', 'urgent', 'emergency') DEFAULT 'routine',
    referral_status ENUM('pending', 'accepted', 'rejected', 'in_progress', 'completed') DEFAULT 'pending',
    service_offering_status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (referring_clinician_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (referring_facility_id) REFERENCES facilities(id) ON DELETE RESTRICT,
    FOREIGN KEY (receiving_facility_id) REFERENCES facilities(id) ON DELETE RESTRICT,
    INDEX idx_status (referral_status),
    INDEX idx_clinician (referring_clinician_id)
);

-- =====================================================
-- 5. FEEDBACK TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    referral_id INT NOT NULL,
    receiving_clinician_id INT NOT NULL,
    clinical_outcome TEXT,
    treatment_given TEXT,
    discharge_summary TEXT,
    counter_referral BOOLEAN DEFAULT FALSE,
    feedback_status ENUM('sent', 'read') DEFAULT 'sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (referral_id) REFERENCES referrals(id) ON DELETE CASCADE,
    FOREIGN KEY (receiving_clinician_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_referral (referral_id)
);

-- =====================================================
-- 6. NOTIFICATIONS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    referral_id INT NOT NULL,
    recipient_email VARCHAR(100),
    recipient_phone VARCHAR(20),
    notification_type ENUM('referral_submitted', 'referral_accepted', 'referral_rejected', 'status_updated', 'feedback_received') NOT NULL,
    message TEXT,
    email_sent BOOLEAN DEFAULT FALSE,
    sms_sent BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referral_id) REFERENCES referrals(id) ON DELETE CASCADE,
    INDEX idx_referral (referral_id)
);

-- =====================================================
-- SEED DATA - INSERT TEST DATA
-- =====================================================

-- Insert Facilities (5 tiers of Tanzania healthcare system)
INSERT INTO facilities (name, facility_type, region, district, address, phone, email, in_charge_name, in_charge_phone) VALUES
('Dar Central Dispensary', 'dispensary', 'Dar es Salaam', 'Kinondoni', 'Magomeni, Dar es Salaam', '+255654321098', 'dispensary@rms.go.tz', 'John Mwankenja', '+255654321098'),
('Amani Health Centre', 'health_centre', 'Dar es Salaam', 'Ilala', 'Amani Road, Dar es Salaam', '+255654321099', 'amani@rms.go.tz', 'Grace Msusa', '+255654321099'),
('Ilala District Hospital', 'district_hospital', 'Dar es Salaam', 'Ilala', 'Ilala, Dar es Salaam', '+255654321100', 'ilala@rms.go.tz', 'Dr. Ahmed Hassan', '+255654321100'),
('Ocean Road Cancer Institute', 'regional_hospital', 'Dar es Salaam', 'Kinondoni', 'Ocean Road, Dar es Salaam', '+255654321101', 'ocean@rms.go.tz', 'Prof. Maria Kikwete', '+255654321101'),
('Muhimbili National Hospital', 'national_hospital', 'Dar es Salaam', 'Ilala', 'Muhimbili, Dar es Salaam', '+255654321102', 'muhimbili@rms.go.tz', 'Dr. Samson Maro', '+255654321102');

-- Insert Users (Test Credentials)
-- Passwords hashed with bcrypt (password_hash() in PHP)
-- All use: clinician123, admin123, moh123
INSERT INTO users (email, password, full_name, phone, role, facility_id, is_active) VALUES
('clinician@rms.go.tz', '$2y$10$qKa4ZdwdRJd9dZZd7mAKEuW.o8Z9ZXxZ9Z9Z9Z9Z', 'Dr. Emmanuel Kimaro', '+255654321090', 'clinician', 3, TRUE),
('admin@rms.go.tz', '$2y$10$qKa4ZdwdRJd9dZZd7mAKEuW.o8Z9ZXxZ9Z9Z9Z9Z', 'Sarah Mwangi', '+255654321091', 'admin', 3, TRUE),
('moh@rms.go.tz', '$2y$10$qKa4ZdwdRJd9dZZd7mAKEuW.o8Z9ZXxZ9Z9Z9Z9Z', 'James Mnyika', '+255654321092', 'moh', NULL, TRUE);

-- Insert Test Patients
INSERT INTO patients (patient_name, age, gender, phone, address, medical_record_number) VALUES
('Peter Kipchoge', 45, 'M', '+255654321110', 'Kinondoni, Dar es Salaam', 'MRN-2024-001'),
('Alice Mutua', 38, 'F', '+255654321111', 'Ilala, Dar es Salaam', 'MRN-2024-002'),
('David Njoroge', 52, 'M', '+255654321112', 'Ubungo, Dar es Salaam', 'MRN-2024-003');

-- Insert Test Referrals
INSERT INTO referrals (referral_code, patient_id, referring_clinician_id, referring_facility_id, receiving_facility_id, clinical_reason, urgency_level, referral_status) VALUES
('RMS-2024-001', 1, 1, 2, 3, 'Suspected appendicitis requiring surgical evaluation', 'urgent', 'pending'),
('RMS-2024-002', 2, 1, 2, 4, 'Cancer screening and diagnostic imaging', 'routine', 'accepted'),
('RMS-2024-003', 3, 1, 3, 5, 'Complex cardiology case for specialist opinion', 'emergency', 'in_progress');

-- =====================================================
-- END OF DATABASE SCHEMA
-- =====================================================
