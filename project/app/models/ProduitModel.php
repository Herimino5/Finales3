<?php
namespace app\models;

class ProduitModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère tous les produits
     */
    public function getAllProduits() {
        $sql = "SELECT p.*, c.nom as categorie_nom 
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
        $sql = "SELECT * FROM s3fin_categorie ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouveau produit
     */
    public function insertProduit($data) {
        $sql = "INSERT INTO s3fin_product (nom, prix_unitaire, categorie_id) 
                VALUES (:nom, :prix_unitaire, :categorie_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom' => $data['nom'],
            ':prix_unitaire' => $data['prix_unitaire'],
            ':categorie_id' => $data['categorie_id']
        ]);
        
        return $this->db->lastInsertId();
    }
}
