-- Fresh Market Rwanda Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS fresh_market_rwanda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fresh_market_rwanda;

-- ---------------------------------------------
-- Table: categories
-- ---------------------------------------------
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Table: products
-- ---------------------------------------------
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    unit VARCHAR(30) DEFAULT 'kg',          -- e.g. kg, bunch, piece, litre
    stock_quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Table: customers
-- ---------------------------------------------
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Table: admins
-- ---------------------------------------------
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Table: orders
-- ---------------------------------------------
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    order_number VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    district VARCHAR(100) NOT NULL,
    payment_method ENUM('mobile_money','cash_on_delivery') NOT NULL DEFAULT 'mobile_money',
    momo_number VARCHAR(20) DEFAULT NULL,
    payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
    order_status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------
-- Table: order_items
-- ---------------------------------------------
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    line_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===============================================
-- SEED DATA
-- ===============================================

-- Default admin (username: admin / password: admin123)
INSERT INTO admins (username, password, full_name) VALUES
('admin', '$2y$10$daGOVt2FIQQjm2BEIWGxvOVBlhme51tEz9W1ZYt0HloFCMr/WYX1C', 'Fresh Market Admin');
-- NOTE: password hash above corresponds to "admin123"

-- Categories
INSERT INTO categories (name, slug, description, image) VALUES
('Fruits', 'fruits', 'Fresh seasonal fruits sourced from local farms', 'fruits.jpg'),
('Vegetables', 'vegetables', 'Farm-fresh vegetables harvested daily', 'vegetables.jpg'),
('Dairy & Eggs', 'dairy-eggs', 'Milk, cheese, yogurt and fresh eggs', 'dairy.jpg'),
('Grains & Cereals', 'grains-cereals', 'Rice, maize flour, beans and more', 'grains.jpg'),
('Beverages', 'beverages', 'Juices, water and refreshing drinks', 'beverages.jpg'),
('Meat & Poultry', 'meat-poultry', 'Fresh meat, chicken and fish', 'meat.jpg');

-- Products
INSERT INTO products (category_id, name, slug, description, price, unit, stock_quantity, image, is_featured) VALUES
(1, 'Fresh Bananas', 'fresh-bananas', 'Sweet ripe bananas, perfect for snacking or smoothies.', 1200, 'bunch', 50, 'bananas.jpg', 1),
(1, 'Avocados', 'avocados', 'Creamy Hass avocados grown locally in the highlands.', 500, 'piece', 100, 'avocado.jpg', 1),
(1, 'Pineapple', 'pineapple', 'Juicy sweet pineapples from Eastern Province.', 1500, 'piece', 40, 'pineapple.jpg', 0),
(1, 'Passion Fruit', 'passion-fruit', 'Tangy and aromatic passion fruit, sold by the kg.', 2000, 'kg', 30, 'passion-fruit.jpg', 0),
(1, 'Mangoes', 'mangoes', 'Sweet and juicy mangoes, in season now.', 1800, 'kg', 60, 'mango.jpg', 1),

(2, 'Fresh Tomatoes', 'fresh-tomatoes', 'Vine-ripened tomatoes, great for sauces and salads.', 900, 'kg', 80, 'tomatoes.jpg', 1),
(2, 'Carrots', 'carrots', 'Crisp and sweet carrots, locally grown.', 700, 'kg', 70, 'carrots.jpg', 0),
(2, 'Irish Potatoes', 'irish-potatoes', 'High quality potatoes from Musanze.', 600, 'kg', 150, 'potatoes.jpg', 1),
(2, 'Onions', 'onions', 'Fresh red onions, essential for every kitchen.', 800, 'kg', 90, 'onions.jpg', 0),
(2, 'Spinach (Dodo)', 'spinach-dodo', 'Leafy green dodo, harvested daily.', 500, 'bunch', 60, 'spinach.jpg', 0),

(3, 'Fresh Milk', 'fresh-milk', 'Pasteurized cow milk, 1 litre bottle.', 800, 'litre', 100, 'milk.jpg', 1),
(3, 'Farm Eggs (Tray)', 'farm-eggs-tray', 'Tray of 30 fresh free-range eggs.', 3500, 'tray', 40, 'eggs.jpg', 1),
(3, 'Natural Yogurt', 'natural-yogurt', 'Creamy plain yogurt, 500ml.', 1000, 'piece', 50, 'yogurt.jpg', 0),

(4, 'Rice (5kg)', 'rice-5kg', 'Premium quality rice, 5kg bag.', 6500, 'bag', 35, 'rice.jpg', 1),
(4, 'Maize Flour (5kg)', 'maize-flour-5kg', 'Finely milled maize flour for ugali.', 3000, 'bag', 45, 'maize-flour.jpg', 0),
(4, 'Beans (5kg)', 'beans-5kg', 'Sorted dry beans, 5kg bag.', 7000, 'bag', 30, 'beans.jpg', 0),

(5, 'Mineral Water (1.5L)', 'mineral-water-1-5l', 'Bottled mineral water, 1.5 litres.', 700, 'bottle', 120, 'water.jpg', 0),
(5, 'Fresh Juice - Mixed Fruit', 'fresh-juice-mixed', 'Locally made mixed fruit juice, 1 litre.', 2500, 'bottle', 25, 'juice.jpg', 1),

(6, 'Chicken (Whole)', 'chicken-whole', 'Fresh whole chicken, approx 1.5kg.', 6000, 'piece', 20, 'chicken.jpg', 1),
(6, 'Beef (kg)', 'beef-kg', 'Quality fresh beef cuts.', 5500, 'kg', 25, 'beef.jpg', 0),
(6, 'Tilapia Fish', 'tilapia-fish', 'Fresh tilapia from Lake Kivu.', 4500, 'kg', 18, 'fish.jpg', 0);
