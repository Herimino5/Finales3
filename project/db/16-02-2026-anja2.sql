CREATE OR REPLACE VIEW v_ville_besoins_dons AS
SELECT
    v.id AS id_ville,
    v.nom AS nom_ville,
    p.id AS id_produit,
    p.nom AS nom_produit,
    SUM(b.quantite) AS quantite_besoin,
    COALESCE(SUM(dist.quantite_distribuee), 0) AS quantite_don,
    (SUM(b.quantite) - COALESCE(SUM(dist.quantite_distribuee), 0)) AS reste_a_trouver
FROM
    s3fin_ville v
JOIN
    s3fin_besoin b ON v.id = b.ville_id
JOIN
    s3fin_product p ON b.id_product = p.id
LEFT JOIN
    s3fin_distribution dist ON b.id = dist.besoin_id
GROUP BY
    v.id, v.nom, p.id, p.nom;
