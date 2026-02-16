CREATE DATABASE bngrc;
USE bngrc;
CREATE TABLE s3fin_user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    passwords VARCHAR(255) NOT NULL
);

CREATE TABLE s3fin_region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- Table s3fin_des villes (chaque ville appartient à une région)
CREATE TABLE s3fin_ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES region(id)
);

-- Table s3fin_des catégories (type de besoin ou de don)
CREATE TABLE s3fin_categorie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE s3fin_product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    prix_unitaire DECIMAL(10,2),
    categorie_id  INT, 
    FOREIGN KEY (categorie_id) REFERENCES categorie(id)
);

-- Table s3fin_des besoins
CREATE TABLE s3fin_besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ville_id INT NOT NULL,
    Date_saisie datetime,
    id_product INT,
    descriptions VARCHAR(255),
    quantite INT NOT NULL,
    FOREIGN KEY (ville_id) REFERENCES ville(id),
    FOREIGN KEY (id_product) REFERENCES product(id),

);

-- Table s3fin_des dons
CREATE TABLE s3fin_don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_product INT,
    descriptions VARCHAR(255),
    quantite INT NOT NULL,
    date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id)
    FOREIGN KEY (id_product) REFERENCES product(id),
);


--donnees de test
-- Régions
INSERT INTO region (nom) VALUES
('Analamanga'),
('Atsinanana');

-- Villes
INSERT INTO ville (nom, region_id) VALUES
('Antananarivo', 1),
('Toamasina', 2);

-- Catégories
INSERT INTO categorie (nom) VALUES
('Nature'),
('Matériaux'),
('Argent');

-- Produits
INSERT INTO product (nom, prix_unitaire, categorie_id) VALUES
('Riz 50kg', 2000, 1),
('Tôle ondulée', 15000, 2),
('Aide financière', 1, 3);

-- Besoins
INSERT INTO besoin (ville_id, id_product, descriptions, quantite, Date_saisie) VALUES
(1, 1, 'Riz pour familles sinistrées', 50, NOW()),
(1, 2, 'Tôles pour reconstruction', 20, NOW()),
(2, 3, 'Aide financière urgente', 500000, NOW());

-- Dons
INSERT INTO don (id_product, descriptions, quantite) VALUES
(1, 'Sac de riz offert', 30),
(2, 'Lot de tôles', 10),
(3, 'Don en espèces', 200000);
