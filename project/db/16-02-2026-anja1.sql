CREATE OR REPLACE VIEW v_ville_besoins_dons AS
SELECT
    v.id AS id_ville,
    v.nom AS nom_ville,
    p.id AS id_produit,
    p.nom AS nom_produit,
    b.quantite AS quantite_besoin,
    COALESCE(SUM(d.quantite), 0) AS quantite_don,
    (b.quantite - COALESCE(SUM(d.quantite), 0)) AS reste_a_trouver
FROM
    s3fin_ville v
JOIN
    s3fin_besoin b ON v.id = b.ville_id
JOIN
    s3fin_product p ON b.id_product = p.id
LEFT JOIN
    s3fin_don d ON p.id = d.id_product
GROUP BY
    v.id, v.nom, p.id, p.nom, b.quantite;
