-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS tourism_db;
USE tourism_db;

-- 2. Create Users Table (For Guides, Tourists, and Admins)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guide', 'tourist') NOT NULL DEFAULT 'tourist',
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Create Tours Table (The plans created by Group 1)
CREATE TABLE IF NOT EXISTS tours (
    tour_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50),
    guide_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guide_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Create Bookings Table (To link Tours, Tourists, and External Services)
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    tourist_id INT NOT NULL,
    booking_date DATE NOT NULL,
    hotel_id_ref VARCHAR(50), -- Reference to Group 2's Hotel ID
    taxi_id_ref VARCHAR(50),  -- Reference to Group 4's Taxi ID
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2),
    FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE,
    FOREIGN KEY (tourist_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Insert Sample Data for Testing
INSERT INTO users (fullname, email, password, role, bio) VALUES 
('John Guide', 'guide@test.com', '123456', 'guide', 'Professional guide with 5 years experience.'),
('Alice Tourist', 'tourist@test.com', '123456', 'tourist', 'I love traveling to tropical places.');

INSERT INTO tours (title, destination, description, price, duration, guide_id) VALUES 
('Island Paradise', 'Maldives', 'A 3-day boat and beach tour.', 450.00, '3 Days', 1);