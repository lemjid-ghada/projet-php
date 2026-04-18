-- ========================================
-- BASE DE DONNÉES BENNA TOUNSIYA
-- Restaurant Tunisien - Gestion Complète
-- ========================================

CREATE DATABASE IF NOT EXISTS benna_tounsiya;
USE benna_tounsiya;

-- ========================================
-- TABLE: UTILISATEURS CLIENTS
-- ========================================
CREATE TABLE clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  city VARCHAR(100),
  postal_code VARCHAR(10),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABLE: ADMINISTRATEURS
-- ========================================
CREATE TABLE admins (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'manager', 'staff') DEFAULT 'staff',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE
);

-- ========================================
-- TABLE: CATÉGORIES DE PLATS
-- ========================================
CREATE TABLE dish_categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL UNIQUE,
  name_ar VARCHAR(100),
  description TEXT,
  icon LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- TABLE: PLATS/MENU
-- ========================================
CREATE TABLE dishes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  name_ar VARCHAR(150),
  description TEXT,
  description_ar TEXT,
  price DECIMAL(10, 2) NOT NULL,
  currency VARCHAR(5) DEFAULT 'DT',
  image_url LONGTEXT,
  icon VARCHAR(10),
  is_spicy BOOLEAN DEFAULT FALSE,
  rating DECIMAL(3, 1) DEFAULT 0,
  is_available BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES dish_categories(id)
);

-- ========================================
-- TABLE: RÉSERVATIONS
-- ========================================
CREATE TABLE reservations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  reservation_date DATE NOT NULL,
  reservation_time TIME NOT NULL,
  number_of_guests INT NOT NULL,
  special_requests TEXT,
  status ENUM('confirmed', 'pending', 'cancelled', 'completed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- ========================================
-- TABLE: COMMANDES
-- ========================================
CREATE TABLE orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10, 2) NOT NULL,
  delivery_address VARCHAR(255),
  status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
  payment_method ENUM('cash', 'card', 'online') DEFAULT 'cash',
  delivery_date DATE,
  delivery_time TIME,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- ========================================
-- TABLE: DÉTAILS DES COMMANDES
-- ========================================
CREATE TABLE order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  dish_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  subtotal DECIMAL(10, 2) NOT NULL,
  special_instructions TEXT,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (dish_id) REFERENCES dishes(id)
);

-- ========================================
-- TABLE: CONTACT/MESSAGES
-- ========================================
CREATE TABLE contact_messages (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20),
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new', 'read', 'replied') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  replied_at TIMESTAMP NULL,
  response TEXT
);

-- ========================================
-- TABLE: AVIS/TÉMOIGNAGES
-- ========================================
CREATE TABLE reviews (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_id INT NOT NULL,
  dish_id INT,
  rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_approved BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (client_id) REFERENCES clients(id),
  FOREIGN KEY (dish_id) REFERENCES dishes(id)
);

-- ========================================
-- TABLE: HORAIRES D'OUVERTURE
-- ========================================
CREATE TABLE opening_hours (
  id INT PRIMARY KEY AUTO_INCREMENT,
  day_of_week INT NOT NULL UNIQUE CHECK (day_of_week >= 0 AND day_of_week <= 6),
  day_name VARCHAR(20),
  opening_time TIME,
  closing_time TIME,
  is_closed BOOLEAN DEFAULT FALSE
);

-- ========================================
-- TABLE: CONFIGURATIONS
-- ========================================
CREATE TABLE settings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  key_name VARCHAR(100) NOT NULL UNIQUE,
  key_value TEXT,
  description TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- DONNÉES INITIALES
-- ========================================

-- Catégories de plats
INSERT INTO dish_categories (name, name_ar, description, icon) VALUES
('Entrées', 'المقبلات', 'Appetizers - Petits plats pour commencer', '🥟'),
('Plats Principaux', 'الأطباق الرئيسية', 'Plats traditionnels tunisiens', '🍲'),
('Desserts', 'الحلويات', 'Pâtisseries et douceurs tunisiennes', '🍯'),
('Boissons', 'المشروبات', 'Café tunisien et jus frais', '☕');

-- Plats
INSERT INTO dishes (category_id, name, name_ar, description, price, image_url, icon, is_spicy, rating) VALUES
(1, 'Brik Tunisien', 'برك تونسي', 'Pâte croustillante farcie d\'œuf, fromage et persil', 7.99, 'https://images.unsplash.com/photo-1592134004393-8580299fbb51?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxicmlrJTIwdHVuaXNpYW58ZW58MXx8fHwxNzcyNTcxNDUwfDA&ixlib=rb-4.1.0&q=80&w=1080', '🥟', FALSE, 4.9),
(1, 'Fricassée', 'فريكاسيه', 'Pain croustillant garni de thon, tomate et harissa', 8.99, 'https://images.unsplash.com/photo-1555939594-58d7cb561a1a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzYW5kd2ljaCUyMHRvbmF0byB0dW5hfGVufDF8fHx8MTc3MjU3MTQ1MHww&ixlib=rb-4.1.0&q=80&w=1080', '🥪', TRUE, 4.8),
(2, 'Couscous Traditionnel', 'كسكس تقليدي', 'Semoule cuite à la vapeur avec légumes et sauce harissa', 12.99, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb3VzY291cyUyMHdpdGglMjBtZWF0fGVufDF8fHx8MTc3MjU1ODAyN3ww&ixlib=rb-4.1.0&q=80&w=1080', '🍲', FALSE, 5.0),
(2, 'Méchoui Rôti', 'مشوى', 'Viande d\'agneau rôtie aux épices tunisiennes', 14.99, 'https://images.unsplash.com/photo-1618857754843-f81f27e6ac38?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxyb2FzdCUyMGxhbWIlMjB0dW5pc2lhbiUyMHJvYXN0ZWR8ZW58MXx8fHwxNzcyNTcxNDQ5fDA&ixlib=rb-4.1.0&q=80&w=1080', '🍖', TRUE, 4.9),
(2, 'Pâtes à la Tunisienne', 'معكرونة تونسية', 'Pâtes avec sauce tomate, harissa et fruits de mer', 11.99, 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzZWFmb29kJTIwcGFzdGF8ZW58MXx8fHwxNzcyNTcxNDQ5fDA&ixlib=rb-4.1.0&q=80&w=1080', '🍝', FALSE, 4.7),
(2, 'Poisson Grillé', 'سمك مشوي', 'Poisson frais grillé avec citron et herbes méditerranéennes', 13.99, 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxncmlsbGVkJTIwZmlzaCUyMGxlbW9ufGVufDF8fHx8MTc3MjU3MTQ0OXww&ixlib=rb-4.1.0&q=80&w=1080', '🐟', FALSE, 4.8),
(3, 'Makroudh au Miel', 'مقرودة بالعسل', 'Gâteau tunisien croustillant imbibé de sirop et de miel', 4.99, 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYWtyb3VkaCUyMGhvbmV5fGVufDF8fHx8MTc3MjU1ODAyN3ww&ixlib=rb-4.1.0&q=80&w=1080', '🍯', FALSE, 4.9),
(3, 'Baklawa Pistache', 'بقلاوة فستق', 'Feuilles phyllo croustillantes, pistache et miel', 5.99, 'https://images.unsplash.com/photo-1599599810694-b92d5458c8ba?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYWtsYXdhIHBpc3RhY2hlIHBlc3RyeSUyMGhvbmV5fGVuIHwxfHx8fDE3NzI1NzE0NDl8MA&ixlib=rb-4.1.0&q=80&w=1080', '🥜', FALSE, 5.0),
(4, 'Café Tunisien', 'القهوة التونسية', 'Café traditionnel avec épices - Boisson signature', 2.99, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxUdW5pc2lhbiUyMGNvZmZlZSUyMHRyYWRpdGlvbmFsfGVufDF8fHx8MTc3MjU3MTQ0OXww&ixlib=rb-4.1.0&q=80&w=1080', '☕', FALSE, 4.8),
(4, 'Jus d\'Orange Frais', 'عصير برتقال طازة', 'Jus d\'orange pressé frais du matin', 3.99, 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvcmFuZ2UlMjBqdWljZSUyMGZyZXNofGVufDF8fHx8MTc3MjU3MTQ0OXww&ixlib=rb-4.1.0&q=80&w=1080', '🧡', FALSE, 4.9);

-- Horaires d'ouverture
INSERT INTO opening_hours (day_of_week, day_name, opening_time, closing_time, is_closed) VALUES
(0, 'Dimanche', '11:00:00', '23:00:00', FALSE),
(1, 'Lundi', '11:00:00', '23:00:00', FALSE),
(2, 'Mardi', '11:00:00', '23:00:00', FALSE),
(3, 'Mercredi', '11:00:00', '23:00:00', FALSE),
(4, 'Jeudi', '11:00:00', '00:00:00', FALSE),
(5, 'Vendredi', '11:00:00', '23:59:00', FALSE),
(6, 'Samedi', '11:00:00', '23:59:00', FALSE);

-- Configurations
INSERT INTO settings (key_name, key_value, description) VALUES
('restaurant_name', 'Benna Tounsiya', 'Nom du restaurant'),
('restaurant_name_ar', 'بنّة تونسية', 'Nom du restaurant en arabe'),
('restaurant_phone', '+216 71 123 456', 'Numéro de téléphone'),
('restaurant_email', 'contact@bennatounsiya.tn', 'Email du restaurant'),
('restaurant_address', 'Medina, Tunis', 'Adresse complète'),
('restaurant_city', 'Tunis', 'Ville'),
('restaurant_postal_code', '1000', 'Code postal'),
('currency', 'DT', 'Devise'),
('primary_color', '#1a5f3f', 'Couleur principale'),
('secondary_color', '#155537', 'Couleur secondaire'),
('reservation_min_guests', '1', 'Nombre minimal de convives'),
('reservation_max_guests', '20', 'Nombre maximal de convives'),
('delivery_radius_km', '5', 'Rayon de livraison en km'),
('delivery_fee', '3.00', 'Frais de livraison'),
('min_order_amount', '15.00', 'Montant minimum pour commande');

-- Créer des index pour optimiser les requêtes
CREATE INDEX idx_client_email ON clients(email);
CREATE INDEX idx_admin_email ON admins(email);
CREATE INDEX idx_reservation_client ON reservations(client_id);
CREATE INDEX idx_reservation_date ON reservations(reservation_date);
CREATE INDEX idx_order_client ON orders(client_id);
CREATE INDEX idx_order_date ON orders(order_date);
CREATE INDEX idx_dish_category ON dishes(category_id);
CREATE INDEX idx_review_client ON reviews(client_id);
CREATE INDEX idx_review_dish ON reviews(dish_id);

-- ========================================
-- CONFIRMATIONS
-- ========================================
SHOW DATABASES;
SELECT 'Base de données Benna Tounsiya créée avec succès! ✅' as Message;