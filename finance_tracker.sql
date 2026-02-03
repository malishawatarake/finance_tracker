CREATE DATABASE IF NOT EXISTS finance_tracker;
USE finance_tracker;

-- Users table for login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL  -- Hashed
);

-- Insert sample user (password: 'password123' hashed)
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Transactions table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    category VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    type ENUM('Income', 'Expense') NOT NULL,
    description TEXT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);