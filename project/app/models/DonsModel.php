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
}
