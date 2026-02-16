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

}
