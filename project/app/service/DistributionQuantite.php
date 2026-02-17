<?php
// filepath: /opt/lampp/htdocs/Finales3/project/app/service/DistributionQuantite.php
namespace app\service;

class DistributionQuantite {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Récupérer les besoins triés par quantité restante ASC (les plus petits d'abord)
     */
    private function getBesoinsParQuantite() {
        $sql = "SELECT b.id, b.ville_id, b.id_product, b.quantite,
                       b.Date_saisie,
                       p.nom AS produit_nom,
                       v.nom AS ville_nom,
                       c.nom AS categorie_nom,
                       COALESCE((SELECT SUM(d.quantite_distribuee) 
                                 FROM s3fin_distribution d 
                                 WHERE d.besoin_id = b.id), 0) AS deja_distribue,
                       (b.quantite - COALESCE((SELECT SUM(d.quantite_distribuee) 
                                               FROM s3fin_distribution d 
                                               WHERE d.besoin_id = b.id), 0)) AS quantite_restante
                FROM s3fin_besoin b
                JOIN s3fin_product p ON b.id_product = p.id
                JOIN s3fin_ville v ON b.ville_id = v.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                HAVING quantite_restante > 0
                ORDER BY quantite_restante ASC, b.Date_saisie ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les dons disponibles pour un produit donné
     */
    private function getDonsDisponibles($id_product) {
        $sql = "SELECT d.id, d.id_product, d.quantite, d.date_saisie,
                       d.descriptions,
                       COALESCE((SELECT SUM(dist.quantite_distribuee) 
                                 FROM s3fin_distribution dist 
                                 WHERE dist.don_id = d.id), 0) AS deja_distribue,
                       (d.quantite - COALESCE((SELECT SUM(dist.quantite_distribuee) 
                                               FROM s3fin_distribution dist 
                                               WHERE dist.don_id = d.id), 0)) AS don_disponible
                FROM s3fin_don d
                JOIN s3fin_product p ON d.id_product = p.id
                JOIN s3fin_categorie c ON p.categorie_id = c.id
                WHERE d.id_product = :id_product
                  AND c.nom != 'Argent'
                HAVING don_disponible > 0
                ORDER BY d.date_saisie ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_product' => $id_product]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Simuler la distribution par quantité (priorité aux petits besoins)
     * Ne fait AUCUNE insertion en base - retourne juste le plan de distribution
     */
    public function simuler() {
        $besoins = $this->getBesoinsParQuantite();
        $plan = [];
        
        // Cache des dons disponibles par produit (pour la simulation)
        // On maintient un tableau de dons avec leur disponibilité qui diminue au fur et à mesure
        $donsCache = [];

        foreach ($besoins as $besoin) {
            $productId = $besoin['id_product'];
            $quantiteRestante = (int)$besoin['quantite_restante'];

            // Charger les dons disponibles pour ce produit si pas encore en cache
            if (!isset($donsCache[$productId])) {
                $donsCache[$productId] = $this->getDonsDisponibles($productId);
            }

            foreach ($donsCache[$productId] as &$don) {
                if ($quantiteRestante <= 0) break;

                $donDisponible = (int)$don['don_disponible'];
                if ($donDisponible <= 0) continue;

                // Quantité à distribuer = min(besoin restant, don disponible)
                $quantiteADistribuer = min($quantiteRestante, $donDisponible);

                $plan[] = [
                    'besoin_id'     => $besoin['id'],
                    'ville_id'      => $besoin['ville_id'],
                    'ville_nom'     => $besoin['ville_nom'],
                    'produit_nom'   => $besoin['produit_nom'],
                    'categorie_nom' => $besoin['categorie_nom'],
                    'don_id'        => $don['id'],
                    'don_description' => $don['descriptions'],
                    'quantite_besoin_total'   => $besoin['quantite'],
                    'quantite_besoin_restant' => $besoin['quantite_restante'],
                    'quantite_don_disponible' => $don['don_disponible'],
                    'quantite_distribuee'     => $quantiteADistribuer,
                    'date_besoin'   => $besoin['Date_saisie'],
                    'date_don'      => $don['date_saisie']
                ];

                // Réduire la disponibilité dans le cache (pour la simulation)
                $don['don_disponible'] -= $quantiteADistribuer;
                $quantiteRestante -= $quantiteADistribuer;
            }
            unset($don); // Important : casser la référence
        }

        // Résumé par ville
        $resumeParVille = [];
        foreach ($plan as $item) {
            $villeId = $item['ville_id'];
            if (!isset($resumeParVille[$villeId])) {
                $resumeParVille[$villeId] = [
                    'ville_nom' => $item['ville_nom'],
                    'total_distribue' => 0,
                    'nb_besoins_couverts' => 0,
                    'produits' => []
                ];
            }
            $resumeParVille[$villeId]['total_distribue'] += $item['quantite_distribuee'];
            
            $produit = $item['produit_nom'];
            if (!isset($resumeParVille[$villeId]['produits'][$produit])) {
                $resumeParVille[$villeId]['produits'][$produit] = 0;
            }
            $resumeParVille[$villeId]['produits'][$produit] += $item['quantite_distribuee'];
        }

        return [
            'plan' => $plan,
            'resume_par_ville' => $resumeParVille,
            'total_distributions' => count($plan),
            'total_quantite' => array_sum(array_column($plan, 'quantite_distribuee'))
        ];
    }

    /**
     * Valider et exécuter la distribution (insertion réelle en base)
     */
    public function valider() {
        $simulation = $this->simuler();
        $plan = $simulation['plan'];

        if (empty($plan)) {
            return [
                'success' => false,
                'message' => 'Aucune distribution à effectuer.',
                'distributions' => []
            ];
        }

        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution)
                    VALUES (:besoin_id, :don_id, :quantite, NOW())";
            $stmt = $this->db->prepare($sql);

            $distributions = [];
            foreach ($plan as $item) {
                $stmt->execute([
                    ':besoin_id' => $item['besoin_id'],
                    ':don_id'    => $item['don_id'],
                    ':quantite'  => $item['quantite_distribuee']
                ]);

                $distributions[] = [
                    'id' => $this->db->lastInsertId(),
                    'besoin_id' => $item['besoin_id'],
                    'don_id'    => $item['don_id'],
                    'ville_nom' => $item['ville_nom'],
                    'produit'   => $item['produit_nom'],
                    'quantite'  => $item['quantite_distribuee']
                ];
            }

            $this->db->commit();

            return [
                'success' => true,
                'message' => count($distributions) . ' distribution(s) effectuée(s) avec succès.',
                'distributions' => $distributions,
                'resume_par_ville' => $simulation['resume_par_ville'],
                'total_quantite' => $simulation['total_quantite']
            ];

        } catch (\PDOException $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Erreur lors de la distribution : ' . $e->getMessage(),
                'distributions' => []
            ];
        }
    }
}