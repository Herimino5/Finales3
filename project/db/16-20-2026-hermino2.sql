CREATE TABLE IF NOT EXISTS s3fin_achat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    don_id INT NOT NULL,
    product_id INT NOT NULL,
    besoin_id INT NULL,
    ville_id INT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(15,2) NOT NULL,
    frais_pourcentage DECIMAL(5,2) DEFAULT 0,
    montant_ht DECIMAL(15,2) NOT NULL,
    montant_frais DECIMAL(15,2) DEFAULT 0,
    montant_total DECIMAL(15,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (don_id) REFERENCES s3fin_don(id),
    FOREIGN KEY (product_id) REFERENCES s3fin_product(id),
    FOREIGN KEY (besoin_id) REFERENCES s3fin_besoin(id),
    FOREIGN KEY (ville_id) REFERENCES s3fin_ville(id)
);

ALTER TABLE s3fin_distribution 
ADD COLUMN achat_id INT NULL,
ADD FOREIGN KEY (achat_id) REFERENCES s3fin_achat(id);

CREATE OR REPLACE VIEW v_ville_besoins_dons AS
SELECT 
    b.id AS besoin_id,
    b.ville_id,
    v.nom AS ville_nom,
    b.id_product AS product_id,
    p.nom AS produit_nom,
    p.prix_unitaire,
    c.nom AS categorie_nom,
    b.quantite AS quantite_demandee,
    COALESCE(SUM(d.quantite_distribuee), 0) AS quantite_distribuee,
    (b.quantite - COALESCE(SUM(d.quantite_distribuee), 0)) AS besoin_restant,
    (SELECT COALESCE(SUM(don.quantite - COALESCE((SELECT SUM(dist.quantite_distribuee) FROM s3fin_distribution dist WHERE dist.don_id = don.id), 0)), 0)
     FROM s3fin_don don 
     JOIN s3fin_product prod ON don.id_product = prod.id
     JOIN s3fin_categorie cat ON prod.categorie_id = cat.id
     WHERE don.id_product = b.id_product AND cat.nom != 'Argent') AS dons_nature_disponibles
FROM s3fin_besoin b
JOIN s3fin_ville v ON b.ville_id = v.id
JOIN s3fin_product p ON b.id_product = p.id
JOIN s3fin_categorie c ON p.categorie_id = c.id
LEFT JOIN s3fin_distribution d ON b.id = d.besoin_id
GROUP BY b.id, b.ville_id, v.nom, b.id_product, p.nom, p.prix_unitaire, c.nom, b.quantite
HAVING besoin_restant > 0
ORDER BY v.nom, b.Date_saisie ASC;

CREATE OR REPLACE VIEW v_dons_argent_disponibles AS
SELECT 
    d.id AS don_id,
    d.descriptions,
    d.quantite AS montant_total,
    d.date_saisie,
    COALESCE(SUM(a.montant_total), 0) AS montant_utilise,
    (d.quantite - COALESCE(SUM(a.montant_total), 0)) AS montant_disponible
FROM s3fin_don d
JOIN s3fin_product p ON d.id_product = p.id
JOIN s3fin_categorie c ON p.categorie_id = c.id
LEFT JOIN s3fin_achat a ON d.id = a.don_id
WHERE c.nom = 'Argent'
GROUP BY d.id, d.descriptions, d.quantite, d.date_saisie
HAVING montant_disponible > 0
ORDER BY d.date_saisie ASC;

CREATE OR REPLACE VIEW v_achats_par_ville AS
SELECT 
    a.id AS achat_id,
    a.date_achat,
    v.id AS ville_id,
    v.nom AS ville_nom,
    p.nom AS produit_nom,
    a.quantite,
    a.prix_unitaire,
    a.frais_pourcentage,
    a.montant_ht,
    a.montant_frais,
    a.montant_total,
    d.descriptions AS don_description,
    b.descriptions AS besoin_description
FROM s3fin_achat a
JOIN s3fin_product p ON a.product_id = p.id
JOIN s3fin_don d ON a.don_id = d.id
LEFT JOIN s3fin_ville v ON a.ville_id = v.id
LEFT JOIN s3fin_besoin b ON a.besoin_id = b.id
ORDER BY a.date_achat DESC;
