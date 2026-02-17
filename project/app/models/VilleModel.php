<?php
namespace app\models;

class VilleModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Récupère toutes les villes avec leurs régions
    public function getAllVilles() {
        $sql = "SELECT v.*, r.nom as region_nom 
                FROM s3fin_ville v 
                LEFT JOIN s3fin_region r ON v.region_id = r.id 
                ORDER BY v.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Récupère une ville par son ID
    public function getVilleById($id) {
        $sql = "SELECT v.*, r.nom as region_nom 
                FROM s3fin_ville v 
                LEFT JOIN s3fin_region r ON v.region_id = r.id 
                WHERE v.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Insère une nouvelle ville
    public function insertVille($data) {
        $sql = "INSERT INTO s3fin_ville (nom, region_id) VALUES (:nom, :region_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':region_id' => $data['region_id']
        ]);
    }

    // Met à jour une ville
    public function updateVille($id, $data) {
        $sql = "UPDATE s3fin_ville SET nom = :nom, region_id = :region_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':region_id' => $data['region_id']
        ]);
    }

    // Supprime une ville
    public function deleteVille($id) {
        $sql = "DELETE FROM s3fin_ville WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Compte les stats d'une ville (besoins, dons, distributions)
    public function getVilleStats($villeId) {
        $stats = ['besoins' => 0, 'dons' => 0, 'distributions' => 0];
        
        // Compter les besoins de cette ville
        $sql = "SELECT COUNT(*) as count FROM s3fin_besoin WHERE ville_id = :ville_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ville_id' => $villeId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats['besoins'] = $result['count'] ?? 0;

        // Compter les distributions liées aux besoins de cette ville
        $sql = "SELECT COUNT(*) as count FROM s3fin_distribution d 
                JOIN s3fin_besoin b ON d.besoin_id = b.id 
                WHERE b.ville_id = :ville_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ville_id' => $villeId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats['distributions'] = $result['count'] ?? 0;

        // Compter les dons distribués à cette ville
        $sql = "SELECT COUNT(DISTINCT d.don_id) as count FROM s3fin_distribution d 
                JOIN s3fin_besoin b ON d.besoin_id = b.id 
                WHERE b.ville_id = :ville_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ville_id' => $villeId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats['dons'] = $result['count'] ?? 0;

        return $stats;
    }
}

