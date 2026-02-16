<?php
namespace app\controllers;

use flight\Engine;
use app\models\DispatchModel;
use Flight;

class DispatchController
{
    protected Engine $app;
    protected $model;

    public function __construct($app) {
        $this->app = $app;
        $this->model = new DispatchModel(Flight::db());
    }

    /**
     * Vérifie si un besoin est déjà couvert par des dons (nature/matériaux)
     * 
     * @param int $besoinId ID du besoin à vérifier
     * @return array Résultat de la vérification
     */
    public function verifierBesoinCouvert($besoinId) {
        try {
            $besoin = $this->model->getBesoinById($besoinId);

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
            $donsDisponibles = $this->model->getDonsDisponiblesNatureMateriaux($productId);
            $totalDonsDisponibles = $this->model->getTotalDonsDisponibles($productId);

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
            $this->model->beginTransaction();

            // Vérifier le besoin
            $verificationBesoin = $this->verifierBesoinCouvert($besoinId);
            if (!$verificationBesoin['success']) {
                $this->model->rollBack();
                return $verificationBesoin;
            }

            if ($verificationBesoin['est_couvert']) {
                $this->model->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce besoin est déjà entièrement couvert'
                ];
            }

            // Vérifier le don disponible
            $don = $this->model->getDonById($donId);

            if (!$don) {
                $this->model->rollBack();
                return [
                    'success' => false,
                    'message' => 'Don introuvable'
                ];
            }

            $donDisponible = $don['quantite'] - $don['distribue'];
            if ($donDisponible <= 0) {
                $this->model->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce don est déjà entièrement distribué'
                ];
            }

            // Calculer la quantité à distribuer
            $quantiteADistribuer = min($quantite, $donDisponible, $verificationBesoin['quantite_restante']);

            // Insérer la distribution
            $this->model->creerDistribution($besoinId, $donId, $quantiteADistribuer);

            $this->model->commit();

            return [
                'success' => true,
                'message' => 'Don attribué avec succès',
                'besoin_id' => $besoinId,
                'don_id' => $donId,
                'quantite_distribuee' => $quantiteADistribuer,
                'besoin_restant' => $verificationBesoin['quantite_restante'] - $quantiteADistribuer
            ];

        } catch (\PDOException $e) {
            $this->model->rollBack();
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
            $this->model->beginTransaction();

            // Vérifier si l'achat est autorisé
            $achat = $this->model->getAchatById($achatId);

            if (!$achat) {
                $this->model->rollBack();
                return [
                    'success' => false,
                    'message' => 'Achat introuvable'
                ];
            }

            // Vérifier l'autorisation d'achat
            $verification = $this->verifierAchatAutorise($besoinId, $achat['product_id'], $quantite);
            
            if (!$verification['success'] || !$verification['autorise']) {
                $this->model->rollBack();
                return $verification;
            }

            // Vérifier le besoin
            $verificationBesoin = $this->verifierBesoinCouvert($besoinId);
            if ($verificationBesoin['est_couvert']) {
                $this->model->rollBack();
                return [
                    'success' => false,
                    'message' => 'Ce besoin est déjà entièrement couvert'
                ];
            }

            $quantiteADistribuer = min($quantite, $verification['quantite_autorisee'], $verificationBesoin['quantite_restante']);

            // Insérer la distribution avec référence à l'achat
            $this->model->creerDistributionAvecAchat($besoinId, $achat['don_id'], $achatId, $quantiteADistribuer);

            $this->model->commit();

            return [
                'success' => true,
                'message' => 'Achat attribué avec succès au besoin',
                'besoin_id' => $besoinId,
                'achat_id' => $achatId,
                'quantite_distribuee' => $quantiteADistribuer
            ];

        } catch (\PDOException $e) {
            $this->model->rollBack();
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
            $besoins = $this->model->getBesoinsNonCouverts($villeId);
            
            return [
                'success' => true,
                'besoins' => $besoins
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
