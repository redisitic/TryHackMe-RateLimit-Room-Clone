CREATE DATABASE IF NOT EXISTS hammer;
USE hammer;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert a sample user (user@hmr.thm / password123)
INSERT INTO users (email, password) VALUES (
    'user@hmr.thm',
    '$2y$10$G9TRLoZ0bwQGLIZWf9tIKuVxzKML46tU2nNU08OpbFSxD.bHrk93a'
);