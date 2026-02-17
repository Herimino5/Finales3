-- ============================================
-- SCRIPT DE DONNÉES: donnée.sql
-- Date: 17-02-2026
-- Description: Données initiales pour les tables dons et besoins
-- Note: La colonne 'ordre' dans besoin indique l'ordre de rangement des données initiales
-- ============================================

-- ============================================
-- AJOUT COLONNE ORDRE DANS BESOIN
-- ============================================
ALTER TABLE s3fin_besoin ADD COLUMN ordre INT DEFAULT NULL;

-- ============================================
-- NETTOYAGE DES TABLES
-- ============================================
DELETE FROM s3fin_distribution;
DELETE FROM s3fin_achat;
DELETE FROM s3fin_besoin;
DELETE FROM s3fin_don;
DELETE FROM s3fin_product;
DELETE FROM s3fin_categorie;
DELETE FROM s3fin_ville;
DELETE FROM s3fin_region;

-- Réinitialiser les auto-increments
ALTER TABLE s3fin_distribution AUTO_INCREMENT = 1;
ALTER TABLE s3fin_achat AUTO_INCREMENT = 1;
ALTER TABLE s3fin_besoin AUTO_INCREMENT = 1;
ALTER TABLE s3fin_don AUTO_INCREMENT = 1;
ALTER TABLE s3fin_product AUTO_INCREMENT = 1;
ALTER TABLE s3fin_categorie AUTO_INCREMENT = 1;

-- ============================================
-- CATÉGORIES
-- ============================================
INSERT INTO s3fin_categorie (id, nom) VALUES
(1, 'nature'),
(2, 'materiel'),
(3, 'argent')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- ============================================
-- PRODUITS
-- ============================================
-- Produits catégorie Nature
INSERT INTO s3fin_product (id, nom, prix_unitaire, categorie_id) VALUES
(1, 'Riz (kg)', 3000.00, 1),
(2, 'Eau (L)', 1000.00, 1),
(3, 'Haricots', 4000.00, 1),
(4, 'Huile (L)', 6000.00, 1)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), categorie_id = VALUES(categorie_id);

-- Produits catégorie Matériaux
INSERT INTO s3fin_product (id, nom, prix_unitaire, categorie_id) VALUES
(5, 'Tôle', 25000.00, 2),
(6, 'Bâche', 15000.00, 2),
(7, 'Bois', 10000.00, 2),
(8, 'Clous (kg)', 8000.00, 2),
(9, 'groupe', 6750000.00, 2)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), categorie_id = VALUES(categorie_id);

-- Produit catégorie Argent
INSERT INTO s3fin_product (id, nom, prix_unitaire, categorie_id) VALUES
(10, 'Argent', 1.00, 3)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), categorie_id = VALUES(categorie_id);

-- ============================================
-- RÉGIONS
-- ============================================
INSERT INTO s3fin_region (id, nom) VALUES
(1, 'Atsinanana'),
(2, 'Vatovavy-Fitovinany'),
(3, 'Atsimo-Atsinanana'),
(4, 'Diana'),
(5, 'Menabe'),
(6, 'Analamanga')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- ============================================
-- VILLES
-- ============================================
INSERT INTO s3fin_ville (id, nom, region_id) VALUES
(1, 'Toamasina', 1),
(2, 'Mananjary', 2),
(3, 'Farafangana', 3),
(4, 'Nosy Be', 4),
(5, 'Morondava', 5)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), region_id = VALUES(region_id);

-- ============================================
-- DONS (basé sur les données du tableau)
-- ============================================
-- Dons d'Argent
INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie, initial) VALUES
(10, 'Don Argent', 5000000, '2026-02-16 08:00:00', 'initial'),
(10, 'Don Argent', 3000000, '2026-02-16 09:00:00', 'initial'),
(10, 'Don Argent', 4000000, '2026-02-17 08:00:00', 'initial'),
(10, 'Don Argent', 1500000, '2026-02-17 09:00:00', 'initial'),
(10, 'Don Argent', 6000000, '2026-02-17 10:00:00', 'initial'),
(10, 'Don Argent', 20000000, '2026-02-18 08:00:00', 'initial');

-- Dons Nature
INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie, initial) VALUES
(1, 'Don Riz (kg)', 400, '2026-02-16 10:00:00', 'initial'),
(2, 'Don Eau (L)', 600, '2026-02-16 11:00:00', 'initial'),
(3, 'Don Haricots', 100, '2026-02-17 11:00:00', 'initial'),
(1, 'Don Riz (kg)', 2000, '2026-02-18 09:00:00', 'initial'),
(2, 'Don Eau (L)', 5000, '2026-02-18 10:00:00', 'initial'),
(3, 'Don Haricots', 88, '2026-02-17 14:00:00', 'initial');

-- Dons Matériel
INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie, initial) VALUES
(5, 'Don Tôle', 50, '2026-02-17 12:00:00', 'initial'),
(6, 'Don Bâche', 70, '2026-02-17 13:00:00', 'initial'),
(5, 'Don Tôle', 300, '2026-02-18 11:00:00', 'initial'),
(6, 'Don Bâche', 500, '2026-02-19 08:00:00', 'initial');

-- ============================================
-- BESOINS PAR VILLE (avec ordre de rangement initial)
-- La colonne 'ordre' indique la manière dont les données sont rangées
-- ============================================

-- Besoins pour Toamasina (ville_id = 1)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(1, '2026-02-15 08:00:00', 6, 'Besoin Bâche', 200, 1, 'initial'),
(1, '2026-02-15 08:30:00', 2, 'Besoin Eau (L)', 1500, 4, 'initial'),
(1, '2026-02-16 09:00:00', 10, 'Besoin Argent', 12000000, 12, 'initial'),
(1, '2026-02-16 09:30:00', 1, 'Besoin Riz (kg)', 800, 17, 'initial'),
(1, '2026-02-15 10:00:00', 5, 'Besoin Tôle', 120, 23, 'initial');

-- Besoins pour Mananjary (ville_id = 2)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(2, '2026-02-15 08:00:00', 10, 'Besoin Argent', 6000000, 3, 'initial'),
(2, '2026-02-15 09:00:00', 5, 'Besoin Tôle', 80, 6, 'initial'),
(2, '2026-02-15 10:00:00', 1, 'Besoin Riz (kg)', 500, 9, 'initial'),
(2, '2026-02-16 08:00:00', 8, 'Besoin Clous (kg)', 60, 19, 'initial'),
(2, '2026-02-16 09:00:00', 4, 'Besoin Huile (L)', 120, 25, 'initial');

-- Besoins pour Farafangana (ville_id = 3)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(3, '2026-02-15 08:00:00', 6, 'Besoin Bâche', 150, 8, 'initial'),
(3, '2026-02-16 08:00:00', 10, 'Besoin Argent', 8000000, 10, 'initial'),
(3, '2026-02-16 09:00:00', 2, 'Besoin Eau (L)', 1000, 14, 'initial'),
(3, '2026-02-16 10:00:00', 1, 'Besoin Riz (kg)', 600, 21, 'initial'),
(3, '2026-02-15 11:00:00', 7, 'Besoin Bois', 100, 26, 'initial');

-- Besoins pour Nosy Be (ville_id = 4)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(4, '2026-02-15 08:00:00', 5, 'Besoin Tôle', 40, 2, 'initial'),
(4, '2026-02-15 09:00:00', 1, 'Besoin Riz (kg)', 300, 5, 'initial'),
(4, '2026-02-15 10:00:00', 10, 'Besoin Argent', 4000000, 7, 'initial'),
(4, '2026-02-16 08:00:00', 3, 'Besoin Haricots', 200, 18, 'initial'),
(4, '2026-02-16 09:00:00', 8, 'Besoin Clous (kg)', 30, 24, 'initial');

-- Besoins pour Morondava (ville_id = 5)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(5, '2026-02-16 08:00:00', 1, 'Besoin Riz (kg)', 700, 11, 'initial'),
(5, '2026-02-16 09:00:00', 10, 'Besoin Argent', 10000000, 13, 'initial'),
(5, '2026-02-16 10:00:00', 6, 'Besoin Bâche', 180, 15, 'initial'),
(5, '2026-02-15 08:00:00', 2, 'Besoin Eau (L)', 1200, 20, 'initial'),
(5, '2026-02-15 09:00:00', 7, 'Besoin Bois', 150, 22, 'initial');

-- Besoin supplémentaire pour Toamasina
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, ordre, initial) VALUES
(1, '2026-02-15 11:00:00', 9, 'Besoin groupe electrogene', 3, 16, 'initial');

-- ============================================
-- STATISTIQUES DES DONNÉES
-- ============================================
-- Dons Argent: 39,500,000 Ar
-- Dons Nature: Riz (2400kg), Eau (5600L), Haricots (188)
-- Dons Matériel: Tôle (350), Bâche (570)
--
-- Besoins par ville:
--   Toamasina: 6 besoins
--   Mananjary: 5 besoins
--   Farafangana: 5 besoins
--   Nosy Be: 5 besoins
--   Morondava: 5 besoins
--
-- Total: 26 besoins
-- ============================================
