<?php
namespace app\models;

class RegionModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Récupère toutes les régions
    public function getAllRegions() {
        $sql = "SELECT * FROM s3fin_region ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Récupère une région par son ID
    public function getRegionById($id) {
        $sql = "SELECT * FROM s3fin_region WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Insère une nouvelle région
    public function insertRegion($data) {
        $sql = "INSERT INTO s3fin_region (nom) VALUES (:nom)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':nom' => $data['nom']]);
    }

    // Met à jour une région
    public function updateRegion($id, $data) {
        $sql = "UPDATE s3fin_region SET nom = :nom WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom']
        ]);
    }

    // Supprime une région
    public function deleteRegion($id) {
        $sql = "DELETE FROM s3fin_region WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compte les villes dans une région
    public function countVillesByRegion($regionId) {
        $sql = "SELECT COUNT(*) as count FROM s3fin_ville WHERE region_id = :region_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':region_id' => $regionId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
