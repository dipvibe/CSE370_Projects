CREATE DATABASE IF NOT EXISTS house_hold_network;
USE house_hold_network;

-- 1. General User Table
CREATE TABLE IF NOT EXISTS General_User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    address VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    role ENUM('worker', 'employer', 'administrator') NOT NULL,
    verification_token VARCHAR(255) NULL,
    is_verified TINYINT(1) DEFAULT 0
);

-- 2. Role Specific Tables
CREATE TABLE IF NOT EXISTS Worker (
    user_id INT PRIMARY KEY,
    experience INT DEFAULT 0,
    availability VARCHAR(50) DEFAULT 'available',
    FOREIGN KEY (user_id) REFERENCES General_User(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Employer (
    user_id INT PRIMARY KEY,
    FOREIGN KEY (user_id) REFERENCES General_User(user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Administrator (
    user_id INT PRIMARY KEY,
    responsibility VARCHAR(255) DEFAULT '',
    FOREIGN KEY (user_id) REFERENCES General_User(user_id) ON DELETE CASCADE
);

-- 3. Job List Table
CREATE TABLE IF NOT EXISTS Job_List (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    salary_offer DECIMAL(10, 2) NOT NULL,
    work_type VARCHAR(100) NOT NULL,
    schedule VARCHAR(100),
    area VARCHAR(100) NOT NULL,
    house_no VARCHAR(50),
    FOREIGN KEY (employer_id) REFERENCES General_User(user_id) ON DELETE CASCADE
);

-- 4. Job Request Table
CREATE TABLE IF NOT EXISTS Job_Request (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    worker_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (job_id) REFERENCES Job_List(job_id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES General_User(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, worker_id)
);

-- 5. Hires Table
CREATE TABLE IF NOT EXISTS Hires (
    hire_id INT AUTO_INCREMENT PRIMARY KEY,
    worker_id INT NOT NULL,
    job_id INT NOT NULL,
    employer_id INT NOT NULL,
    joining_date DATE,
    FOREIGN KEY (worker_id) REFERENCES General_User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES Job_List(job_id) ON DELETE CASCADE,
    FOREIGN KEY (employer_id) REFERENCES General_User(user_id) ON DELETE CASCADE
);

-- 6. Payment Record Table
CREATE TABLE IF NOT EXISTS Payment_Record (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    hire_id INT NOT NULL,
    payment_month DATE NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    employer_status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    worker_status ENUM('paid', 'unpaid') DEFAULT 'unpaid',
    pay_date DATE,
    FOREIGN KEY (hire_id) REFERENCES Hires(hire_id) ON DELETE CASCADE
);

-- Indexes for performance
CREATE INDEX idx_job_employer ON Job_List(employer_id);
CREATE INDEX idx_request_job ON Job_Request(job_id);
CREATE INDEX idx_request_worker ON Job_Request(worker_id);
CREATE INDEX idx_hire_worker ON Hires(worker_id);

