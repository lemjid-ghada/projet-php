-- ========================================
-- MIGRATION : Fusion des tables clients et admins en utilisateurs
-- Exécutez ce script sur une base de données existante
-- ========================================

-- Étape 1: Créer la nouvelle table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  role ENUM('client', 'staff', 'manager', 'admin') DEFAULT 'client',
  address VARCHAR(255),
  city VARCHAR(100),
  postal_code VARCHAR(10),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE,
  INDEX idx_email (email),
  INDEX idx_role (role)
);

-- Étape 2: Migrer les données des clients existants
INSERT INTO utilisateurs (id, first_name, last_name, email, phone, password, role, address, city, postal_code, created_at, updated_at, is_active)
SELECT id, first_name, last_name, email, phone, password, 'client', address, city, postal_code, created_at, updated_at, is_active
FROM clients
WHERE NOT EXISTS (SELECT 1 FROM utilisateurs WHERE utilisateurs.email = clients.email);

-- Étape 3: Migrer les données des admins existants
-- Note: Ajustez les ID pour éviter les conflits
INSERT INTO utilisateurs (first_name, last_name, email, phone, password, role, created_at, updated_at, is_active)
SELECT first_name, last_name, email, '', password, role, created_at, updated_at, is_active
FROM admins
WHERE NOT EXISTS (SELECT 1 FROM utilisateurs WHERE utilisateurs.email = admins.email);

-- Étape 4: Mettre à jour les tables de référence
-- Mettre à jour les foreign keys de reservations
ALTER TABLE reservations 
DROP FOREIGN KEY reservations_ibfk_1;

ALTER TABLE reservations 
ADD CONSTRAINT reservations_ibfk_1 
FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE CASCADE;

-- Mettre à jour les foreign keys de orders
ALTER TABLE orders 
DROP FOREIGN KEY orders_ibfk_1;

ALTER TABLE orders 
ADD CONSTRAINT orders_ibfk_1 
FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE CASCADE;

-- Mettre à jour les foreign keys de reviews
ALTER TABLE reviews 
DROP FOREIGN KEY reviews_ibfk_1;

ALTER TABLE reviews 
ADD CONSTRAINT reviews_ibfk_1 
FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE CASCADE;

-- Étape 5: Supprimer les anciennes tables (optionnel - décommenter après vérification)
-- DROP TABLE IF EXISTS clients;
-- DROP TABLE IF EXISTS admins;

-- Vérification
SELECT '✓ Utilisateurs créés:', role, COUNT(*) FROM utilisateurs GROUP BY role;
SELECT '✓ Total utilisateurs:', COUNT(*) FROM utilisateurs;
