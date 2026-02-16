-- Ajouter une table pour les distributions
CREATE TABLE IF NOT EXISTS s3fin_distribution (
    id INT PRIMARY KEY AUTO_INCREMENT,
    besoin_id INT NOT NULL,
    don_id INT NOT NULL,
    quantite_distribuee INT NOT NULL,
    date_distribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 
    FOREIGN KEY (besoin_id) REFERENCES s3fin_besoin(id),
    FOREIGN KEY (don_id) REFERENCES s3fin_don(id)
    );

