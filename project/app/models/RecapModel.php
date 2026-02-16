<?php
namespace app\models;

class RecapModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère le récapitulatif global des besoins en montant
     */
    public function getRecapGlobal() {
        try {
            $sql = "SELECT 
                        COUNT(b.id) AS nombre_besoins,
                        COALESCE(SUM(b.quantite * p.prix_unitaire), 0) AS montant_total_besoins,
                        COALESCE(SUM(
                            COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0) 
                            * p.prix_unitaire
                        ), 0) AS montant_satisfait,
                        COALESCE(SUM(
                            (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0))
                            * p.prix_unitaire
                        ), 0) AS montant_restant,
                        COALESCE(SUM(b.quantite), 0) AS quantite_totale_besoins,
                        COALESCE(SUM(
                            COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0)
                        ), 0) AS quantite_satisfaite,
                        COALESCE(SUM(
                            b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0)
                        ), 0) AS quantite_restante
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [
                'nombre_besoins' => 0,
                'montant_total_besoins' => 0,
                'montant_satisfait' => 0,
                'montant_restant' => 0,
                'quantite_totale_besoins' => 0,
                'quantite_satisfaite' => 0,
                'quantite_restante' => 0
            ];
        }
    }

    /**
     * Récapitulatif par ville
     */
    public function getRecapParVille() {
        try {
            $sql = "SELECT 
                        v.id AS ville_id,
                        v.nom AS ville_nom,
                        COUNT(b.id) AS nombre_besoins,
                        COALESCE(SUM(b.quantite * p.prix_unitaire), 0) AS montant_total_besoins,
                        COALESCE(SUM(
                            COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0) 
                            * p.prix_unitaire
                        ), 0) AS montant_satisfait,
                        COALESCE(SUM(
                            (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0))
                            * p.prix_unitaire
                        ), 0) AS montant_restant
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id
                    JOIN s3fin_ville v ON b.ville_id = v.id
                    GROUP BY v.id, v.nom
                    ORDER BY montant_restant DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Récapitulatif par produit
     */
    public function getRecapParProduit() {
        try {
            $sql = "SELECT 
                        p.id AS produit_id,
                        p.nom AS produit_nom,
                        c.nom AS categorie_nom,
                        p.prix_unitaire,
                        COALESCE(SUM(b.quantite), 0) AS quantite_totale,
                        COALESCE(SUM(b.quantite * p.prix_unitaire), 0) AS montant_total_besoins,
                        COALESCE(SUM(
                            COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0)
                        ), 0) AS quantite_satisfaite,
                        COALESCE(SUM(
                            COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0) 
                            * p.prix_unitaire
                        ), 0) AS montant_satisfait,
                        COALESCE(SUM(
                            (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0))
                        ), 0) AS quantite_restante,
                        COALESCE(SUM(
                            (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0))
                            * p.prix_unitaire
                        ), 0) AS montant_restant
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id
                    JOIN s3fin_categorie c ON p.categorie_id = c.id
                    GROUP BY p.id, p.nom, c.nom, p.prix_unitaire
                    ORDER BY montant_restant DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Récapitulatif détaillé par besoin
     */
    public function getRecapDetaille() {
        try {
            $sql = "SELECT 
                        b.id AS besoin_id,
                        v.nom AS ville_nom,
                        p.nom AS produit_nom,
                        c.nom AS categorie_nom,
                        b.descriptions,
                        b.quantite AS quantite_demandee,
                        p.prix_unitaire,
                        (b.quantite * p.prix_unitaire) AS montant_besoin,
                        COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0) AS quantite_distribuee,
                        COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0) * p.prix_unitaire AS montant_satisfait,
                        (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0)) AS quantite_restante,
                        (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) FROM s3fin_distribution d WHERE d.besoin_id = b.id), 0)) * p.prix_unitaire AS montant_restant,
                        b.Date_saisie
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id
                    JOIN s3fin_categorie c ON p.categorie_id = c.id
                    JOIN s3fin_ville v ON b.ville_id = v.id
                    ORDER BY v.nom, b.Date_saisie ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }
}