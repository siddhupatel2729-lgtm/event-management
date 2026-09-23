-- ===================================================
-- College Event Management System - Database Setup
-- ===================================================

CREATE DATABASE IF NOT EXISTS college_events;
USE college_events;

-- ---------------------------------------------------
-- Table: admins
-- Stores login details of admin(s) who manage events
-- ---------------------------------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,   -- stored as a hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin login -> username: admin | password: admin123
-- (password below is the bcrypt hash of "admin123")
INSERT INTO admins (username, password)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.YeIVvV3W3fW8xnGeXbjO8YU6UOXi4LkVe');

-- ---------------------------------------------------
-- Table: events
-- Stores every college event's details
-- ---------------------------------------------------
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    venue VARCHAR(150) NOT NULL,
    ticket_price DECIMAL(8,2) NOT NULL DEFAULT 0,
    total_seats INT NOT NULL DEFAULT 0,
    available_seats INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------
-- Table: tickets
-- Stores every ticket booked by a student for an event
-- ---------------------------------------------------
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    student_email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    quantity INT NOT NULL DEFAULT 1,
    total_amount DECIMAL(8,2) NOT NULL DEFAULT 0,
    status ENUM('confirmed','cancelled') DEFAULT 'confirmed',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
