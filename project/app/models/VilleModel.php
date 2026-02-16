<?php
namespace app\models;

class VilleModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupère toutes les villes avec leurs régions
     */
    public function getAllVilles() {
        $sql = "SELECT v.*, r.nom as region_nom 
                FROM s3fin_ville v 
                LEFT JOIN s3fin_region r ON v.region_id = r.id 
                ORDER BY v.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
