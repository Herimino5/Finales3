<?php
namespace app\models;

class DonsModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllDons() {
        $sql = "SELECT d.id, p.nom AS nom_produit, d.descriptions, d.quantite, d.date_saisie 
                FROM s3fin_don d
                JOIN s3fin_product p ON d.id_product = p.id
                ORDER BY d.date_saisie DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertDon($id_product, $descriptions, $quantite) {
        $sql = "INSERT INTO s3fin_don (id_product, descriptions, quantite, date_saisie) 
                VALUES (:id_product, :descriptions, :quantite, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_product' => $id_product,
            'descriptions' => $descriptions,
            'quantite' => $quantite
        ]);
    }
    public function countDons() {
        $sql = "SELECT COUNT(*) AS total_dons FROM s3fin_don";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total_dons'];
    }

    /**
     * Récupère le total des quantités de dons depuis la table s3fin_don
     */
    public function getTotalDons() {
        $sql = "SELECT COALESCE(SUM(quantite), 0) AS total FROM s3fin_don";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Récupère tous les produits
     */
    public function getAllProducts() {
        $sql = "SELECT p.id, p.nom, p.prix_unitaire, c.nom AS categorie_nom 
                FROM s3fin_product p
                LEFT JOIN s3fin_categorie c ON p.categorie_id = c.id
                ORDER BY p.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les catégories
     */
    public function getAllCategories() {
        $sql = "SELECT id, nom FROM s3fin_categorie ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouveau produit
     */
    public function insertProduct($nom, $prix_unitaire, $categorie_id) {
        $sql = "INSERT INTO s3fin_product (nom, prix_unitaire, categorie_id) 
                VALUES (:nom, :prix_unitaire, :categorie_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'prix_unitaire' => $prix_unitaire,
            'categorie_id' => $categorie_id
        ]);
        return $this->db->lastInsertId();
    }
}
