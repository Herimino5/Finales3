-- Données de test pour tester les distributions
-- Date: 17-02-2026
-- Ces données seront marquées comme 'initial' pour la réinitialisation

-- ============================================
-- NETTOYAGE DES TABLES
-- ============================================
-- Supprimer dans l'ordre pour respecter les contraintes de clés étrangères

-- Supprimer les distributions (dépend de besoins et dons)
DELETE FROM s3fin_distribution;

-- Supprimer les achats
DELETE FROM s3fin_achat;

-- Supprimer les besoins (dépend de villes et produits)
DELETE FROM s3fin_besoin;

-- Supprimer les dons (dépend de produits)
DELETE FROM s3fin_don;

-- Supprimer les produits (dépend de catégories)
DELETE FROM s3fin_product;

-- Supprimer les catégories
DELETE FROM s3fin_categorie;

-- NE PAS SUPPRIMER: les villes et régions (données de base du système)
-- Les villes et régions sont des données permanentes

-- Réinitialiser les auto-increments
ALTER TABLE s3fin_distribution AUTO_INCREMENT = 1;
ALTER TABLE s3fin_achat AUTO_INCREMENT = 1;
ALTER TABLE s3fin_besoin AUTO_INCREMENT = 1;
ALTER TABLE s3fin_don AUTO_INCREMENT = 1;
ALTER TABLE s3fin_product AUTO_INCREMENT = 1;
ALTER TABLE s3fin_categorie AUTO_INCREMENT = 1;

-- NE PAS SUPPRIMER: les villes et régions (données de base du système)
-- Les villes et régions sont des données permanentes

-- ============================================
-- CATÉGORIES
-- ============================================
INSERT INTO s3fin_categorie (id, nom) VALUES
(1, 'Nature'),
(2, 'Matériaux'),
(3, 'Argent')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- ============================================
-- PRODUITS
-- ============================================
-- Produits catégorie Nature
INSERT INTO s3fin_product (id, nom, prix_unitaire, categorie_id) VALUES
(1, 'Riz', 2000.00, 1),
(2, 'Maïs', 1500.00, 1),
(3, 'Haricots', 3000.00, 1),
(4, 'Eau', 500.00, 1)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), categorie_id = VALUES(categorie_id);

-- Produits catégorie Matériaux
INSERT INTO s3fin_product (id, nom, prix_unitaire, categorie_id) VALUES
(5, 'Couvertures', 15000.00, 2),
(6, 'Tentes', 80000.00, 2),
(7, 'Vêtements', 10000.00, 2),
(8, 'Médicaments', 25000.00, 2)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), categorie_id = VALUES(categorie_id);

-- ============================================
-- DONS DE TEST
-- ============================================

-- Dons de produits Nature
INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie, initial) VALUES
(1, 'Don de riz du comité local', 500, '2026-02-10 08:00:00', 'initial'),
(1, 'Don de riz d''une ONG internationale', 800, '2026-02-11 09:30:00', 'initial'),
(2, 'Don de maïs des agriculteurs', 300, '2026-02-10 10:00:00', 'initial'),
(3, 'Don de haricots de la coopérative', 200, '2026-02-12 11:00:00', 'initial'),
(4, 'Don d''eau potable embouteillée', 1000, '2026-02-09 07:00:00', 'initial'),
(4, 'Don d''eau en bouteilles', 600, '2026-02-13 14:00:00', 'initial');

-- Dons de produits Matériaux
INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie, initial) VALUES
(5, 'Don de couvertures neuves', 150, '2026-02-10 12:00:00', 'initial'),
(5, 'Don de couvertures d''urgence', 200, '2026-02-14 10:00:00', 'initial'),
(6, 'Don de tentes de camping', 50, '2026-02-11 13:00:00', 'initial'),
(7, 'Don de vêtements pour enfants', 300, '2026-02-12 09:00:00', 'initial'),
(7, 'Don de vêtements adultes', 250, '2026-02-13 11:00:00', 'initial'),
(8, 'Don de médicaments premiers soins', 100, '2026-02-10 15:00:00', 'initial');

-- ============================================
-- BESOINS DE TEST PAR VILLE
-- ============================================

-- Besoins pour Antananarivo (ville_id = 1)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(1, '2026-02-08 08:00:00', 1, 'Besoin urgent de riz pour 100 familles', 600, 'initial'),
(1, '2026-02-09 10:00:00', 4, 'Besoin d''eau potable suite inondation', 800, 'initial'),
(1, '2026-02-10 11:00:00', 5, 'Besoin de couvertures pour abris', 120, 'initial'),
(1, '2026-02-11 14:00:00', 7, 'Besoin de vêtements pour sinistrés', 200, 'initial');

-- Besoins pour Toamasina (ville_id = 2)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(2, '2026-02-07 09:00:00', 1, 'Besoin de riz après cyclone', 700, 'initial'),
(2, '2026-02-08 10:30:00', 2, 'Besoin de maïs pour alimentation', 250, 'initial'),
(2, '2026-02-09 11:00:00', 4, 'Besoin eau potable urgent', 900, 'initial'),
(2, '2026-02-10 13:00:00', 6, 'Besoin de tentes pour hébergement', 40, 'initial'),
(2, '2026-02-11 15:00:00', 8, 'Besoin de médicaments urgents', 80, 'initial');

-- Besoins pour Fianarantsoa (ville_id = 3)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(3, '2026-02-09 08:30:00', 1, 'Besoin de riz pour centres d''accueil', 400, 'initial'),
(3, '2026-02-10 09:00:00', 3, 'Besoin de haricots pour repas', 150, 'initial'),
(3, '2026-02-11 10:00:00', 5, 'Besoin de couvertures zones froides', 180, 'initial'),
(3, '2026-02-12 11:30:00', 7, 'Besoin de vêtements chauds', 150, 'initial');

-- Besoins pour Mahajanga (ville_id = 4)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(4, '2026-02-08 07:00:00', 1, 'Besoin de riz après sécheresse', 300, 'initial'),
(4, '2026-02-09 08:00:00', 2, 'Besoin de maïs pour distribution', 200, 'initial'),
(4, '2026-02-10 12:00:00', 4, 'Besoin eau potable crise', 500, 'initial'),
(4, '2026-02-11 14:00:00', 6, 'Besoin de tentes temporaires', 30, 'initial');

-- Besoins pour Toliara (ville_id = 5)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(5, '2026-02-07 10:00:00', 1, 'Besoin de riz urgence alimentaire', 500, 'initial'),
(5, '2026-02-08 11:00:00', 3, 'Besoin de haricots protéines', 180, 'initial'),
(5, '2026-02-09 13:00:00', 4, 'Besoin eau potable sécheresse', 700, 'initial'),
(5, '2026-02-10 15:00:00', 7, 'Besoin de vêtements familles', 200, 'initial'),
(5, '2026-02-11 16:00:00', 8, 'Besoin de médicaments base', 60, 'initial');

-- Besoins pour Antsiranana (ville_id = 6)
INSERT INTO s3fin_besoin (ville_id, Date_saisie, id_product, descriptions, quantite, initial) VALUES
(6, '2026-02-08 09:00:00', 1, 'Besoin de riz distribution mensuelle', 350, 'initial'),
(6, '2026-02-09 10:00:00', 4, 'Besoin eau potable contamination', 400, 'initial'),
(6, '2026-02-10 11:00:00', 5, 'Besoin de couvertures saison fraîche', 100, 'initial'),
(6, '2026-02-11 13:00:00', 6, 'Besoin de tentes évacuation', 25, 'initial');

-- ============================================
-- STATISTIQUES DES DONNÉES DE TEST
-- ============================================
-- Total dons: 12 enregistrements
-- Total besoins: 29 enregistrements
-- 
-- Catégorie Nature:
--   Dons: 3850 unités (Riz: 1300, Maïs: 300, Haricots: 200, Eau: 1600)
--   Besoins: 5500 unités
-- 
-- Catégorie Matériaux:
--   Dons: 1050 unités (Couvertures: 350, Tentes: 50, Vêtements: 550, Médicaments: 100)
--   Besoins: 1365 unités
--
-- Scénarios de test:
-- 1. Besoins > Dons (pour tester distribution partielle)
-- 2. Plusieurs dons pour un même produit (pour tester FIFO)
-- 3. Plusieurs villes (pour tester distribution proportionnelle)
-- 4. Différentes dates (pour tester ordre chronologique)
