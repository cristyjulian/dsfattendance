CREATE TABLE users (
    users_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    user_email VARCHAR(255) NOT NULL UNIQUE,    
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);