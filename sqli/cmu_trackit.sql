CREATE DATABASE IF NOT EXISTS cmu_trackit;
USE cmu_trackit;

CREATE TABLE IF NOT EXISTS items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_name VARCHAR(100),
  category VARCHAR(50),
  status ENUM('lost','found'),
  description TEXT,
  date_reported TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
