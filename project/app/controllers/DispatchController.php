<?php
namespace app\controllers;

use flight\Engine;
use app\models\DistributionModel;
use app\models\AchatModel;
use app\models\BesoinModel;
use app\models\DonsModel;
use Flight;

class DispatchController
{
    protected Engine $app;
    protected $db;

    public function __construct($app) {
        $this->app = $app;
        $this->db = Flight::db();
    }

    /**
     * Vérifie si un besoin est déjà couvert par des dons (nature/matériaux)
     * 
     * @param int $besoinId ID du besoin à vérifier
     * @return array Résultat de la vérification
     */
    public function verifierBesoinCouvert($besoinId) {
        try {
            // Récupérer le besoin avec sa quantité totale et distribuée
            $sql = "SELECT b.id, b.quantite as quantite_demandee, b.id_product, b.ville_id,
                           p.nom as produit_nom, p.prix_unitaire,
                           c.nom as categorie_nom,
                           v.nom as ville_nom,
                           COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0) as quantite_distribuee
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id
                    JOIN s3fin_categorie c ON p.categorie_id = c.id
                    JOIN s3fin_ville v ON b.ville_id = v.id
                    WHERE b.id = :besoin_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':besoin_id' => $besoinId]);
            $besoin = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$besoin) {
                return [
                    'success' => false,
                    'message' => 'Besoin introuvable'
                ];
            }

            $quantiteRestante = $besoin['quantite_demandee'] - $besoin['quantite_distribuee'];
            $estCouvert = $quantiteRestante <= 0;

            return [
                'success' => true,
                'besoin_id' => $besoinId,
                'produit' => $besoin['produit_nom'],
                'categorie' => $besoin['categorie_nom'],
                'ville' => $besoin['ville_nom'],
                'quantite_demandee' => $besoin['quantite_demandee'],
                'quantite_distribuee' => $besoin['quantite_distribuee'],
                'quantite_restante' => $quantiteRestante,
                'est_couvert' => $estCouvert,
                'message' => $estCouvert ? 'Ce besoin est déjà entièrement couvert' : 'Besoin partiellement couvert ou non couvert'
            ];

        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Vérifie si un achat en argent peut être effectué (pas de doublon avec dons nature/matériaux)
     * 
     * @param int $besoinId ID du besoin
     * @param int $productId ID du produit
     * @param int $quantite Quantité à acheter
     * @return array Résultat de la vérification
     */
    public function verifierAchatAutorise($besoinId, $productId, $quantite) {
        try {
            // Vérifier d'abord si le besoin existe et correspond au produit
            $verificationBesoin = $this->verifierBesoinCouvert($besoinId);
            
            if (!$verificationBesoin['success']) {
                return $verificationBesoin;
            }

            // Si le besoin est déjà couvert, bloquer l'achat
            if ($verificationBesoin['est_couvert']) {
                return [
                    'success' => false,
                    'autorise' => false,
                    'message' => 'Achat refusé : Ce besoin est déjà entièrement couvert par des dons existants',
                    'besoin' => $verificationBesoin
                ];
            }

            // Vérifier si des dons en nature/matériaux sont disponibles pour ce produit
            $sqlDonsDisponibles = "SELECT d.id, d.quantite, 
                                          COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0) as distribue,
                                          (d.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0)) as disponible
                                   FROM s3fin_don d
                                   JOIN s3fin_product p ON d.id_product = p.id
                                   JOIN s3fin_categorie c ON p.categorie_id = c.id
                                   WHERE d.id_product = :product_id 
                                   AND c.nom != 'Argent'
                                   HAVING disponible > 0";
            
            $stmtDons = $this->db->prepare($sqlDonsDisponibles);
            $stmtDons->execute([':product_id' => $productId]);
            $donsDisponibles = $stmtDons->fetchAll(\PDO::FETCH_ASSOC);

            $totalDonsDisponibles = 0;
            foreach ($donsDisponibles as $don) {
                $totalDonsDisponibles += $don['disponible'];
            }

            // Si des dons en nature sont disponibles et suffisants, bloquer l'achat
            if ($totalDonsDisponibles >= $verificationBesoin['quantite_restante']) {
                return [
                    'success' => false,
                    'autorise' => false,
                    'message' => 'Achat refusé : Des dons en nature/matériaux suffisants sont disponibles pour couvrir ce besoin',
                    'dons_disponibles' => $totalDonsDisponibles,
                    'besoin_restant' => $verificationBesoin['quantite_restante']
                ];
            }

            // Calculer la quantité à acheter (seulement ce qui n'est pas couvert par les dons)
            $quantiteAchatAutorisee = $verificationBesoin['quantite_restante'] - $totalDonsDisponibles;

            return [
                'success' => true,
                'autorise' => true,
                'message' => 'Achat autorisé',
                'quantite_demandee' => $quantite,
                'quantite_autorisee' => min($quantite, $quantiteAchatAutorisee),
                'dons_disponibles' => $totalDonsDisponibles,
                'besoin_restant' => $verificationBesoin['quantite_restante']
            ];

        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Attribue un don (nature/matériaux) à un besoin
     * 
     * @param int $besoinId ID du besoin
     * @param int $donId ID du don
     * @param int $quantite Quantité à distribuer
     * @return array Résultat de l'attribution
     */
    public function attribuerDon($besoinId, $donId, $quantite) {
        try {
            $this->db->beginTransaction();

            // Vérifier le besoin
            $verificationBesoin = $this->verifierBesoinCouvert($besoinId);
            if (!$verificationBesoin['success']) {
                $this->db->rollBack();
                return $verificationBesoin;
            }

            if ($verificationBesoin['est_couvert']) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce besoin est déjà entièrement couvert'
                ];
            }

            // Vérifier le don disponible
            $sqlDon = "SELECT d.id, d.quantite, d.id_product,
                              COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE don_id = d.id), 0) as distribue
                       FROM s3fin_don d
                       WHERE d.id = :don_id";
            $stmtDon = $this->db->prepare($sqlDon);
            $stmtDon->execute([':don_id' => $donId]);
            $don = $stmtDon->fetch(\PDO::FETCH_ASSOC);

            if (!$don) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Don introuvable'
                ];
            }

            $donDisponible = $don['quantite'] - $don['distribue'];
            if ($donDisponible <= 0) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce don est déjà entièrement distribué'
                ];
            }

            // Calculer la quantité à distribuer
            $quantiteADistribuer = min($quantite, $donDisponible, $verificationBesoin['quantite_restante']);

            // Insérer la distribution
            $sqlDistrib = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution)
                           VALUES (:besoin_id, :don_id, :quantite, NOW())";
            $stmtDistrib = $this->db->prepare($sqlDistrib);
            $stmtDistrib->execute([
                ':besoin_id' => $besoinId,
                ':don_id' => $donId,
                ':quantite' => $quantiteADistribuer
            ]);

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Don attribué avec succès',
                'besoin_id' => $besoinId,
                'don_id' => $donId,
                'quantite_distribuee' => $quantiteADistribuer,
                'besoin_restant' => $verificationBesoin['quantite_restante'] - $quantiteADistribuer
            ];

        } catch (\PDOException $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Attribue un achat (via argent) à un besoin
     * 
     * @param int $besoinId ID du besoin
     * @param int $achatId ID de l'achat
     * @param int $quantite Quantité à distribuer
     * @return array Résultat de l'attribution
     */
    public function attribuerAchat($besoinId, $achatId, $quantite) {
        try {
            $this->db->beginTransaction();

            // Vérifier si l'achat est autorisé
            $sqlAchat = "SELECT a.id, a.product_id, a.quantite, a.don_id
                         FROM s3fin_achat a
                         WHERE a.id = :achat_id";
            $stmtAchat = $this->db->prepare($sqlAchat);
            $stmtAchat->execute([':achat_id' => $achatId]);
            $achat = $stmtAchat->fetch(\PDO::FETCH_ASSOC);

            if (!$achat) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Achat introuvable'
                ];
            }

            // Vérifier l'autorisation d'achat
            $verification = $this->verifierAchatAutorise($besoinId, $achat['product_id'], $quantite);
            
            if (!$verification['success'] || !$verification['autorise']) {
                $this->db->rollBack();
                return $verification;
            }

            // Vérifier le besoin
            $verificationBesoin = $this->verifierBesoinCouvert($besoinId);
            if ($verificationBesoin['est_couvert']) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce besoin est déjà entièrement couvert'
                ];
            }

            $quantiteADistribuer = min($quantite, $verification['quantite_autorisee'], $verificationBesoin['quantite_restante']);

            // Insérer la distribution avec référence à l'achat
            $sqlDistrib = "INSERT INTO s3fin_distribution (besoin_id, don_id, quantite_distribuee, date_distribution, achat_id)
                           VALUES (:besoin_id, :don_id, :quantite, NOW(), :achat_id)";
            $stmtDistrib = $this->db->prepare($sqlDistrib);
            $stmtDistrib->execute([
                ':besoin_id' => $besoinId,
                ':don_id' => $achat['don_id'],
                ':quantite' => $quantiteADistribuer,
                ':achat_id' => $achatId
            ]);

            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Achat attribué avec succès au besoin',
                'besoin_id' => $besoinId,
                'achat_id' => $achatId,
                'quantite_distribuee' => $quantiteADistribuer
            ];

        } catch (\PDOException $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupère les besoins non couverts par ville
     * 
     * @param int|null $villeId ID de la ville (null pour toutes)
     * @return array Liste des besoins non couverts
     */
    public function getBesoinsNonCouverts($villeId = null) {
        try {
            $sql = "SELECT b.id, b.quantite as quantite_demandee, b.descriptions,
                           b.Date_saisie, b.id_product, b.ville_id,
                           p.nom as produit_nom, p.prix_unitaire,
                           c.nom as categorie_nom,
                           v.nom as ville_nom,
                           COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0) as quantite_distribuee,
                           (b.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0)) as quantite_restante
                    FROM s3fin_besoin b
                    JOIN s3fin_product p ON b.id_product = p.id
                    JOIN s3fin_categorie c ON p.categorie_id = c.id
                    JOIN s3fin_ville v ON b.ville_id = v.id
                    WHERE (b.quantite - COALESCE((SELECT SUM(quantite_distribuee) FROM s3fin_distribution WHERE besoin_id = b.id), 0)) > 0";
            
            $params = [];
            if ($villeId) {
                $sql .= " AND b.ville_id = :ville_id";
                $params[':ville_id'] = $villeId;
            }
            
            $sql .= " ORDER BY b.Date_saisie ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return [
                'success' => true,
                'besoins' => $stmt->fetchAll(\PDO::FETCH_ASSOC)
            ];

        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    /**
     * API : Vérifier si un besoin est couvert
     */
    public function apiVerifierBesoin($besoinId) {
        $result = $this->verifierBesoinCouvert($besoinId);
        Flight::json($result);
    }

    /**
     * API : Vérifier si un achat est autorisé
     */
    public function apiVerifierAchat() {
        $besoinId = Flight::request()->data->besoin_id ?? null;
        $productId = Flight::request()->data->product_id ?? null;
        $quantite = Flight::request()->data->quantite ?? 0;

        if (!$besoinId || !$productId || !$quantite) {
            Flight::json([
                'success' => false,
                'message' => 'Paramètres manquants'
            ]);
            return;
        }

        $result = $this->verifierAchatAutorise($besoinId, $productId, $quantite);
        Flight::json($result);
    }

    /**
     * API : Attribuer un don à un besoin
     */
    public function apiAttribuerDon() {
        $besoinId = Flight::request()->data->besoin_id ?? null;
        $donId = Flight::request()->data->don_id ?? null;
        $quantite = Flight::request()->data->quantite ?? 0;

        if (!$besoinId || !$donId || !$quantite) {
            Flight::json([
                'success' => false,
                'message' => 'Paramètres manquants'
            ]);
            return;
        }

        $result = $this->attribuerDon($besoinId, $donId, $quantite);
        Flight::json($result);
    }

    /**
     * API : Récupérer les besoins non couverts
     */
    public function apiGetBesoinsNonCouverts() {
        $villeId = Flight::request()->query->ville_id ?? null;
        $result = $this->getBesoinsNonCouverts($villeId);
        Flight::json($result);
    }
}
