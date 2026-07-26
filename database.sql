-- =========================================================
-- Civil Engineering Consultancy Website - Database Schema
-- Import this file in phpMyAdmin / mysql CLI before use.
-- =========================================================

CREATE DATABASE IF NOT EXISTS civil_consultancy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE civil_consultancy;

-- ---------------------------------------------------------
-- Admin users (for the admin panel that manages dynamic content)
-- ---------------------------------------------------------
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- bcrypt hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default login -> username: admin | password: Admin@123
INSERT INTO admin_users (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ---------------------------------------------------------
-- Home page banner slides (dynamic, editable from admin)
-- ---------------------------------------------------------
CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    button_text VARCHAR(50) DEFAULT NULL,
    button_link VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO banners (title, subtitle, image, button_text, button_link, sort_order) VALUES
('Building The Infrastructure Of Tomorrow', 'Structural design, site supervision and project consultancy you can rely on.', 'assets/uploads/banners/default-1.jpg', 'Our Services', 'services.php', 1),
('25+ Years Of Engineering Excellence', 'From feasibility studies to final handover, we manage every phase.', 'assets/uploads/banners/default-2.jpg', 'View Our Work', 'our-work.php', 2),
('Precision. Safety. On-Time Delivery.', 'Trusted by government and private clients across the region.', 'assets/uploads/banners/default-3.jpg', 'Contact Us', 'contact.php', 3);

-- ---------------------------------------------------------
-- Our Work / Project Logs (dynamic)
-- ---------------------------------------------------------
CREATE TABLE work_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO work_categories (name) VALUES
('Residential'), ('Commercial'), ('Infrastructure'), ('Industrial');

CREATE TABLE work_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category_id INT DEFAULT NULL,
    client_name VARCHAR(150) DEFAULT NULL,
    location VARCHAR(150) DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    status ENUM('ongoing','completed') DEFAULT 'completed',
    cover_image VARCHAR(255) NOT NULL,
    short_desc VARCHAR(300) DEFAULT NULL,
    full_desc TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES work_categories(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------
-- Gallery (dynamic, simple image library)
-- ---------------------------------------------------------
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    work_project_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_project_id) REFERENCES work_projects(id) ON DELETE SET NULL
);

-- ---------------------------------------------------------
-- Contact form submissions
-- ---------------------------------------------------------
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    subject VARCHAR(200) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
