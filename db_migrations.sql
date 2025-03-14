-- database/db_migrations.sql - Updated Database Schema with CRUD Operations

CREATE DATABASE IF NOT EXISTS food_waste_management;
USE food_waste_management;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'receiver') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donations Table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    food_item VARCHAR(255) NOT NULL,
    quantity VARCHAR(100) NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('available', 'claimed', 'delivered', 'expired') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Requests Table
CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL DEFAULT 'Food Waste Management',
    contact_email VARCHAR(100) NOT NULL DEFAULT 'admin@example.com'
);

-- Insert Default Admin User
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@example.com', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE username='admin';

-- CRUD Operations

-- Insert a new donation
INSERT INTO donations (user_id, food_item, quantity, pickup_location, expiry_date) 
VALUES (1, 'Rice', '10kg', 'City Center', '2025-12-31');

-- Insert a new food request
INSERT INTO requests (donation_id, user_id, status) 
VALUES (1, 2, 'pending');

-- Update a donation status
UPDATE donations SET status='claimed' WHERE id=1;

-- Update a request status
UPDATE requests SET status='approved' WHERE id=1;

-- Delete a request
DELETE FROM requests WHERE id=1;
