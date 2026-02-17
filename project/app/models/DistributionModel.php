<?php
namespace app\models;

class DistributionModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }



    /**
     * Récupérer toutes les distributions avec pagination
     */
    public function getDistributionsPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT d.id, d.besoin_id, d.don_id, d.quantite_distribuee, d.date_distribution,
                       b.quantite as besoin_quantite, b.Date_saisie as besoin_date,
                       don.quantite as don_quantite, don.date_saisie as don_date,
                       v.nom as ville_nom, p.nom as produit_nom, c.nom as categorie_nom
                FROM s3fin_distribution d
                LEFT JOIN s3fin_besoin b ON d.besoin_id = b.id
                LEFT JOIN s3fin_don don ON d.don_id = don.id
                LEFT JOIN s3fin_ville v ON b.ville_id = v.id
                LEFT JOIN s3fin_product p ON b.id_product = p.id
                LEFT JOIN s3fin_categorie c ON p.categorie_id = c.id
                ORDER BY d.date_distribution DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Compter le nombre total de distributions
     */
    public function countDistributions() {
        $sql = "SELECT COUNT(*) as total FROM s3fin_distribution";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
