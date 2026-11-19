-- Recipe Catalog: 11-апта. Құрылым 8-аптадағы ER-диаграммаға (database.md) сәйкес
CREATE DATABASE IF NOT EXISTS recipe_catalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE recipe_catalog;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,            -- password_hash() нәтижесі
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    cooking_time INT,                          -- минутпен
    image VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

INSERT IGNORE INTO categories (name, slug) VALUES
('Таңғы ас', 'tangy-as'),
('Сорпалар', 'sorpalar'),
('Ыстық тағамдар', 'ystyq-tagamdar'),
('Салаттар', 'salattar'),
('Тәттілер', 'tattiler'),
('Сусындар', 'susyndar');
