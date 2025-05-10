-- Create database if not exists
CREATE DATABASE IF NOT EXISTS csm_sederhana;
USE csm_sederhana;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_token_expiry DATETIME DEFAULT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    remember_token_expiry DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT,
    excerpt TEXT,
    featured_image VARCHAR(255),
    status ENUM('published', 'private', 'draft') NOT NULL DEFAULT 'draft',
    author_id INT NOT NULL,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tags table
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Post tags relationship table
CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_title', 'Inspira'),
('site_description', 'A Simple Content Management System'),
('site_logo', 'images/Logo.png'),
('site_favicon', 'images/favicon.ico'),
('posts_per_page', '10'),
('allow_comments', '1'),
('allow_registration', '1');

-- Insert default admin account
INSERT INTO users (username, email, password, role, status) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Insert default category
INSERT INTO categories (name, slug, description) VALUES
('Uncategorized', 'uncategorized', 'Default category for posts'),
('Technology', 'technology', 'Technology related posts'),
('Business', 'business', 'Business and finance posts'),
('Lifestyle', 'lifestyle', 'Lifestyle and wellness posts'),
('Travel', 'travel', 'Travel and adventure posts'),
('Food', 'food', 'Food and cooking posts'),
('Health', 'health', 'Health and fitness posts'),
('Education', 'education', 'Education and learning posts'),
('Entertainment', 'entertainment', 'Entertainment and media posts'),
('Sports', 'sports', 'Sports and athletics posts'),
('Science', 'science', 'Science and research posts'),
('Art', 'art', 'Art and creativity posts'),
('Music', 'music', 'Music and audio posts'),
('Fashion', 'fashion', 'Fashion and style posts'),
('Gaming', 'gaming', 'Gaming and esports posts'),
('News', 'news', 'Current events and news'),
('Politics', 'politics', 'Political news and analysis'),
('Environment', 'environment', 'Environmental issues and sustainability'),
('Automotive', 'automotive', 'Cars and vehicles'),
('Real Estate', 'real-estate', 'Property and housing'),
('Finance', 'finance', 'Financial news and advice'),
('Marketing', 'marketing', 'Marketing and advertising'),
('Design', 'design', 'Graphic and web design'),
('Photography', 'photography', 'Photography and images'),
('Books', 'books', 'Book reviews and literature'),
('Movies', 'movies', 'Film reviews and cinema'),
('TV Shows', 'tv-shows', 'Television series and shows'),
('Fitness', 'fitness', 'Exercise and workout'),
('Parenting', 'parenting', 'Child care and family'),
('Pets', 'pets', 'Pet care and animals'),
('Gardening', 'gardening', 'Plants and gardening');

-- Create index for faster lookups
CREATE INDEX idx_email ON users(email);
CREATE INDEX idx_username ON users(username); 