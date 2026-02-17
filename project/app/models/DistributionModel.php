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
                       b.quantite as besoin_quantite, b.Date_saisie as besoin_date, b.ville_id,
                       don.quantite as don_quantite, don.date_saisie as don_date, don.descriptions as don_description,
                       v.nom as ville_nom, v.id as ville_id_full,
                       p.nom as produit_nom, p.id as produit_id,
                       c.nom as categorie_nom, c.id as categorie_id
                FROM s3fin_distribution d
                INNER JOIN s3fin_besoin b ON d.besoin_id = b.id
                INNER JOIN s3fin_don don ON d.don_id = don.id
                INNER JOIN s3fin_ville v ON b.ville_id = v.id
                INNER JOIN s3fin_product p ON b.id_product = p.id
                INNER JOIN s3fin_categorie c ON p.categorie_id = c.id
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

    /**
     * Récupérer les statistiques de distribution par ville
     * Montre pour chaque ville et produit :
     * - total_besoins : quantité totale demandée
     * - total_dons : quantité totale de dons reçus pour ce produit (GLOBAL, pas par ville)
     * - total_distribue : quantité effectivement distribuée à cette ville
     */
    public function getDistributionsParVille() {
        $sql = "SELECT 
                    v.id as ville_id,
                    v.nom as ville_nom,
                    p.nom as produit_nom,
                    c.nom as categorie_nom,
                    COALESCE(SUM(b.quantite), 0) as total_besoins,
                    COALESCE((
                        SELECT SUM(don.quantite) 
                        FROM s3fin_don don 
                        WHERE don.id_product = p.id
                    ), 0) as total_dons,
                    COALESCE(SUM(dist.quantite_distribuee), 0) as total_distribue,
                    COUNT(DISTINCT b.id) as nb_besoins,
                    COUNT(DISTINCT dist.id) as nb_distributions
                FROM s3fin_ville v
                LEFT JOIN s3fin_besoin b ON v.id = b.ville_id
                LEFT JOIN s3fin_product p ON b.id_product = p.id
                LEFT JOIN s3fin_categorie c ON p.categorie_id = c.id
                LEFT JOIN s3fin_distribution dist ON b.id = dist.besoin_id
                WHERE b.id IS NOT NULL AND p.id IS NOT NULL
                GROUP BY v.id, v.nom, p.id, p.nom, c.nom
                HAVING total_distribue > 0
                ORDER BY v.nom, p.nom";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
