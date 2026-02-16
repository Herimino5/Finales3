<?php
namespace app\models;

class BesoinModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère la liste des besoins et des dons pour chaque ville à partir de la vue.
     */
    public function getBesoinDonsParVille() {
        $sql = "SELECT * FROM v_ville_besoins_dons";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function insertBesoin($data) {
        $sql = "INSERT INTO s3fin_besoin 
                (ville_id, Date_saisie, id_product, descriptions, quantite) 
                VALUES 
                (:ville_id, :Date_saisie, :id_product, :descriptions, :quantite)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute([
            ':ville_id'    => $data['ville_id'],
            ':Date_saisie' => $data['Date_saisie'],
            ':id_product'  => $data['id_product'],
            ':descriptions'=> $data['descriptions'],
            ':quantite'    => $data['quantite']
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Récupère le total des besoins depuis la table s3fin_besoin
     */
    public function getTotalBesoins() {
        $sql = "SELECT COALESCE(SUM(quantite), 0) AS total FROM s3fin_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Récupère le nombre de villes ayant des besoins
     */
    public function countVillesAvecBesoins() {
        $sql = "SELECT COUNT(DISTINCT ville_id) AS total FROM s3fin_besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
    }

}
