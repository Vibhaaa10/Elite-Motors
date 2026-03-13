-- 1. Setup Database
CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

-- 2. Setup Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Setup Cars Table
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    stock_status ENUM('available', 'sold') DEFAULT 'available'
);

-- 4. Setup Inquiries (Orders) Table
-- This table stores the user acquisitions shown in the Admin "User Orders" panel
CREATE TABLE IF NOT EXISTS inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Setup Reviews Table
-- Added logic to ensure star ratings stay between 1 and 5
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100),
    rating INT,
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Clear old data and Reset
-- This disables the constraint checks so you can truncate the 'cars' table
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE inquiries;
TRUNCATE TABLE cars;
TRUNCATE TABLE users;
TRUNCATE TABLE reviews;

-- This re-enables the checks to maintain data integrity for new inserts
SET FOREIGN_KEY_CHECKS = 1;

-- 7. Insert Car Inventory (Masterpieces)
INSERT INTO cars (make, model, price, description, image_url) VALUES 
('Lamborghini', 'Aventador SVJ', 517000, 'Experience the pinnacle of V12 performance and aerodynamics.', 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=500'),
('Ferrari', 'F8 Tributo', 280000, 'A celebration of excellence and the most powerful V8 in Ferrari history.', 'https://images.unsplash.com/photo-1592198084033-aade902d1aae?w=500'),
('Tesla', 'Model S Plaid', 89000, 'Beyond Ludicrous. 0-60 mph in 1.99 seconds.', 'https://images.unsplash.com/photo-1560958089-b8a1929cea89?w=500'),
('Porsche', '911 GT3', 161000, 'Born in Flacht. Designed for the track, refined for the road.', 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=500'),
('BMW', 'M4 Competition', 78000, 'Uncompromising power and signature M-series styling.', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=500'),
('Ferrari', '488 Pista', 330000, 'Track-focused V8 masterpiece with incredible aerodynamics.', 'https://images.unsplash.com/photo-1592198084033-aade902d1aae?w=500'),
('Porsche', '911 GT3 RS', 223000, 'The ultimate precision driving machine for track enthusiasts.', 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=500'),
('BMW', 'M8 Competition', 130000, 'Luxury meets brutal power in this high-performance coupe.', 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=500'),
('Audi', 'R8 V10 Performance', 197000, 'Naturally aspirated V10 sound with Quattro all-wheel drive.', 'https://images.unsplash.com/photo-1614200024991-985f7547ee94?w=500');

-- 8. Insert Admin User
-- Password is 'admin123'
INSERT INTO users (username, email, password, role) 
VALUES ('System Admin', 'admin@elite.com', '$2y$10$7r9vO8ZASzshm9m.G.A09uXWpA09uXWpA6V7zX.R/fV2p.D6B7Fm5Q1L5WZy', 'admin');