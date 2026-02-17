<?php
namespace app\service;

class Distributionfifo {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Distribuer automatiquement les dons aux besoins selon la date de saisie (FIFO)
     */
    public function distribuer() {
        // Récupérer tous les besoins triés par date de saisie (FIFO)
        $sqlBesoins = "SELECT b.*, p.nom as produit_nom, v.nom as ville_nom
                       FROM s3fin_besoin b
                       LEFT JOIN s3fin_product p ON b.id_product = p.id
                       LEFT JOIN s3fin_ville v ON b.ville_id = v.id
                       ORDER BY b.Date_saisie ASC";
        
        $stmtBesoins = $this->db->prepare($sqlBesoins);
        $stmtBesoins->execute();
        $besoins = $stmtBesoins->fetchAll(\PDO::FETCH_ASSOC);
        
        $distributions = [];
        
        foreach ($besoins as $besoin) {
            // Calculer la quantité déjà distribuée pour ce besoin
            $sqlDistribue = "SELECT COALESCE(SUM(quantite_distribuee), 0) as total_distribue
                            FROM s3fin_distribution
                            WHERE besoin_id = :besoin_id";
            $stmtDist = $this->db->prepare($sqlDistribue);
            $stmtDist->execute([':besoin_id' => $besoin['id']]);
            $distribue = $stmtDist->fetch(\PDO::FETCH_ASSOC);
            
            $quantite_restante = $besoin['quantite'] - $distribue['total_distribue'];
            
            if ($quantite_restante <= 0) {
                continue;
            }
            
            // Chercher des dons disponibles pour ce produit (FIFO)
            $sqlDons = "SELECT d.* FROM s3fin_don d
                       WHERE d.id_product = :id_product
                       AND d.quantite > COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0)
                       ORDER BY d.date_saisie ASC";
            
            $stmtDons = $this->db->prepare($sqlDons);
            $stmtDons->execute([':id_product' => $besoin['id_product']]);
            $dons = $stmtDons->fetchAll(\PDO::FETCH_ASSOC);
            
            foreach ($dons as $don) {
                if ($quantite_restante <= 0) break;
                
                // Calculer la quantité déjà distribuée de ce don
                $sqlDonDist = "SELECT COALESCE(SUM(quantite_distribuee), 0) as total_distribue
                              FROM s3fin_distribution
                              WHERE don_id = :don_id";
                $stmtDonDist = $this->db->prepare($sqlDonDist);
                $stmtDonDist->execute([':don_id' => $don['id']]);
                $donDistribue = $stmtDonDist->fetch(\PDO::FETCH_ASSOC);
                
                $don_disponible = $don['quantite'] - $donDistribue['total_distribue'];
                
                // Quantité à distribuer
                $quantite_a_distribuer = min($quantite_restante, $don_disponible);
                
                // Créer la distribution
                $this->creerDistribution(
                    $besoin['id'],
                    $don['id'],
                    $quantite_a_distribuer
                );
                
                $distributions[] = [
                    'besoin_id' => $besoin['id'],
                    'besoin_ville' => $besoin['ville_nom'],
                    'produit' => $besoin['produit_nom'],
                    'don_id' => $don['id'],
                    'quantite' => $quantite_a_distribuer,
                    'date_besoin' => $besoin['Date_saisie'],
                    'date_don' => $don['date_saisie']
                ];
                
                $quantite_restante -= $quantite_a_distribuer;
            }
        }
        
        return $distributions;
    }

    /**
     * Créer une distribution
     */
    private function creerDistribution($besoin_id, $don_id, $quantite) {
        $sql = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution)
                VALUES (:besoin_id, :don_id, :quantite, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':besoin_id' => $besoin_id,
            ':don_id' => $don_id,
            ':quantite' => $quantite
        ]);
    }
}
