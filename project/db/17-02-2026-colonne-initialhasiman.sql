-- Ajout de la colonne 'initial' pour tracer les données initiales de démonstration
-- Date: 17-02-2026

-- Table s3fin_besoin
ALTER TABLE s3fin_besoin 
ADD COLUMN initial VARCHAR(10) DEFAULT NULL COMMENT 'Marqueur pour données initiales de démonstration';

-- Table s3fin_don
ALTER TABLE s3fin_don 
ADD COLUMN initial VARCHAR(10) DEFAULT NULL COMMENT 'Marqueur pour données initiales de démonstration';

-- Marquer les données existantes comme initiales
UPDATE s3fin_besoin SET initial = 'initial' WHERE id > 0;
UPDATE s3fin_don SET initial = 'initial' WHERE id > 0;

-- Note : Les nouvelles insertions depuis l'interface auront initial = NULL par défaut
