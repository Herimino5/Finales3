<?php
namespace app\service;

class DistributionProportionnel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Distribuer automatiquement les dons aux besoins proportionnellement par ville
     */
    public function distribuer() {
        // Récupérer tous les besoins non satisfaits groupés par ville et produit
        $sqlBesoins = "SELECT b.id, b.ville_id, b.id_product, b.quantite, b.Date_saisie,
                              v.nom as ville_nom, p.nom as produit_nom,
                              COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0) as deja_distribue,
                              (b.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0)) as quantite_restante
                       FROM s3fin_besoin b
                       LEFT JOIN s3fin_product p ON b.id_product = p.id
                       LEFT JOIN s3fin_ville v ON b.ville_id = v.id
                       HAVING quantite_restante > 0
                       ORDER BY b.Date_saisie ASC";
        
        $stmtBesoins = $this->db->prepare($sqlBesoins);
        $stmtBesoins->execute();
        $besoins = $stmtBesoins->fetchAll(\PDO::FETCH_ASSOC);
        
        if (empty($besoins)) {
            return [];
        }
        
        // Grouper les besoins par produit
        $besoinsParProduit = [];
        foreach ($besoins as $besoin) {
            $produitId = $besoin['id_product'];
            if (!isset($besoinsParProduit[$produitId])) {
                $besoinsParProduit[$produitId] = [
                    'produit_nom' => $besoin['produit_nom'],
                    'besoins' => [],
                    'total_besoins' => 0
                ];
            }
            $besoinsParProduit[$produitId]['besoins'][] = $besoin;
            $besoinsParProduit[$produitId]['total_besoins'] += $besoin['quantite_restante'];
        }
        
        $distributions = [];
        
        // Pour chaque produit, distribuer les dons proportionnellement
        foreach ($besoinsParProduit as $produitId => $data) {
            // Récupérer les dons disponibles pour ce produit
            $sqlDons = "SELECT d.* FROM s3fin_don d
                       WHERE d.id_product = :id_product
                       AND d.quantite > COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0)
                       ORDER BY d.date_saisie ASC";
            
            $stmtDons = $this->db->prepare($sqlDons);
            $stmtDons->execute([':id_product' => $produitId]);
            $dons = $stmtDons->fetchAll(\PDO::FETCH_ASSOC);
            
            if (empty($dons)) {
                continue;
            }
            
            // Calculer le total des dons disponibles
            $totalDonsDisponible = 0;
            foreach ($dons as $don) {
                $sqlDonDist = "SELECT COALESCE(SUM(quantite_distribuee), 0) as total_distribue
                              FROM s3fin_distribution WHERE don_id = :don_id";
                $stmtDonDist = $this->db->prepare($sqlDonDist);
                $stmtDonDist->execute([':don_id' => $don['id']]);
                $donDistribue = $stmtDonDist->fetch(\PDO::FETCH_ASSOC);
                $totalDonsDisponible += ($don['quantite'] - $donDistribue['total_distribue']);
            }
            
            if ($totalDonsDisponible <= 0) {
                continue;
            }
            
            // Calculer les proportions pour chaque besoin
            $totalBesoins = $data['total_besoins'];
            $proportions = [];
            $totalDistribueParFloor = 0;
            
            foreach ($data['besoins'] as $besoin) {
                $proportion = $besoin['quantite_restante'] / $totalBesoins;
                $valeurExacte = $totalDonsDisponible * $proportion;
                $quantiteProportionnelle = floor($valeurExacte);
                $decimale = $valeurExacte - $quantiteProportionnelle;
                
                $proportions[] = [
                    'besoin' => $besoin,
                    'quantite_a_distribuer' => min($quantiteProportionnelle, $besoin['quantite_restante']),
                    'decimale' => $decimale
                ];
                $totalDistribueParFloor += min($quantiteProportionnelle, $besoin['quantite_restante']);
            }
            
            // Distribuer le reste aux villes avec les plus grandes décimales
            $reste = min($totalDonsDisponible, $totalBesoins) - $totalDistribueParFloor;
            
            if ($reste > 0) {
                // Trier par décimale décroissante (les plus grandes décimales en premier)
                usort($proportions, function($a, $b) {
                    return $b['decimale'] <=> $a['decimale'];
                });
                
                foreach ($proportions as &$prop) {
                    if ($reste <= 0) break;
                    $besoinRestant = $prop['besoin']['quantite_restante'] - $prop['quantite_a_distribuer'];
                    if ($besoinRestant > 0) {
                        $prop['quantite_a_distribuer'] += 1;
                        $reste--;
                    }
                }
                unset($prop);
            }
            
            // Retirer les distributions à 0
            $proportions = array_filter($proportions, function($p) {
                return $p['quantite_a_distribuer'] > 0;
            });
            
            // Distribuer les dons selon les proportions
            foreach ($proportions as $prop) {
                $besoin = $prop['besoin'];
                $quantiteADistribuer = $prop['quantite_a_distribuer'];
                
                // Parcourir les dons et distribuer
                foreach ($dons as &$don) {
                    if ($quantiteADistribuer <= 0) break;
                    
                    $sqlDonDist = "SELECT COALESCE(SUM(quantite_distribuee), 0) as total_distribue
                                  FROM s3fin_distribution WHERE don_id = :don_id";
                    $stmtDonDist = $this->db->prepare($sqlDonDist);
                    $stmtDonDist->execute([':don_id' => $don['id']]);
                    $donDistribue = $stmtDonDist->fetch(\PDO::FETCH_ASSOC);
                    
                    $donDisponible = $don['quantite'] - $donDistribue['total_distribue'];
                    
                    if ($donDisponible <= 0) continue;
                    
                    $quantite = min($quantiteADistribuer, $donDisponible);
                    
                    // Créer la distribution
                    $this->creerDistribution($besoin['id'], $don['id'], $quantite);
                    
                    $distributions[] = [
                        'besoin_id' => $besoin['id'],
                        'besoin_ville' => $besoin['ville_nom'],
                        'produit' => $besoin['produit_nom'],
                        'don_id' => $don['id'],
                        'quantite' => $quantite,
                        'date_besoin' => $besoin['Date_saisie'],
                        'date_don' => $don['date_saisie']
                    ];
                    
                    $quantiteADistribuer -= $quantite;
                }
                unset($don);
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