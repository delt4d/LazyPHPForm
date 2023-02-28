CREATE DATABASE
    db_at_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE db_at_php;

CREATE TABLE
    users (
        id CHAR(36) PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(254) NOT NULL UNIQUE,
        password VARCHAR(300) NOT NULL
    );

INSERT INTO
    users (id, name, email, password)
VALUES (
        uuid(),
        'admin',
        'admin@gmail.com',
        'admin'
    );
