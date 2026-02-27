


CREATE TABLE IF NOT EXISTS transports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('flight', 'bus', 'train', 'car') NOT NULL,
    name VARCHAR(100) NOT NULL,
    departure_location VARCHAR(100) NOT NULL,
    arrival_location VARCHAR(100) NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO transports (type, name, departure_location, arrival_location, departure_time, arrival_time, price, capacity, status, description)
VALUES 
('flight', 'New York Express', 'New York (JFK)', 'London (LHR)', '2023-12-15 08:00:00', '2023-12-15 20:00:00', 899.99, 200, 'active', 'Direct flight to London with premium services'),
('bus', 'Coastal Voyager', 'Los Angeles', 'San Francisco', '2023-12-10 07:30:00', '2023-12-10 12:00:00', 49.99, 50, 'active', 'Comfortable bus ride along the coast'),
('train', 'Eurostar', 'Paris (Gare du Nord)', 'London (St Pancras)', '2023-12-20 09:00:00', '2023-12-20 11:30:00', 120.00, 300, 'active', 'High-speed train connecting Paris and London'),
('car', 'Premium Sedan', 'Chicago Downtown', 'Chicago O\'Hare', '2023-12-05 10:00:00', '2023-12-05 10:45:00', 79.99, 4, 'active', 'Luxury sedan with professional driver'),
('flight', 'Tokyo Dream', 'Los Angeles (LAX)', 'Tokyo (HND)', '2023-12-18 22:00:00', '2023-12-19 05:00:00', 1299.99, 250, 'active', 'Overnight flight to Tokyo with full amenities');