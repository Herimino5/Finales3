-- Ajouter une table pour les distributions
CREATE TABLE IF NOT EXISTS s3fin_distribution (
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT NOT NULL,
    don_id INT NOT NULL,
    quantite_distribuee INT NOT NULL,
    date_distribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En cours',
    FOREIGN KEY (besoin_id) REFERENCES s3fin_besoin(id),
    FOREIGN KEY (don_id) REFERENCES s3fin_don(id)
);

-- Ajouter une colonne pour le statut dans la table besoin si elle n'existe pas
ALTER TABLE s3fin_besoin ADD COLUMN IF NOT EXISTS statut VARCHAR(50) DEFAULT 'En attente';

-- Ajouter une colonne pour le statut dans la table don si elle n'existe pas  
ALTER TABLE s3fin_don ADD COLUMN IF NOT EXISTS statut VARCHAR(50) DEFAULT 'Disponible';
