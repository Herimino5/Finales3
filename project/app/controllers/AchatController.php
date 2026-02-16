<?php
namespace app\controllers;

use flight\Engine;
use app\models\AchatModel;
use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;

class AchatController
{
    protected Engine $app;
    protected AchatModel $achatModel;

    public function __construct($app) {
        $this->app = $app;
        $this->achatModel = new AchatModel(Flight::db());
    }

    public function index() {
        $produitModel = new ProduitModel(Flight::db());
        $villeModel = new VilleModel(Flight::db());

        $achats = $this->achatModel->getAllAchats();
        $donsArgent = $this->achatModel->getDonsArgentDisponibles();
        $produits = $produitModel->getAllProduits();
        $villes = $villeModel->getAllVilles();

        $this->app->render('achats', [
            'achats' => $achats,
            'donsArgent' => $donsArgent,
            'produits' => $produits,
            'villes' => $villes,
            'BASE_URL' => BASE_URL
        ]);
    }

    public function simulateAchat($villeId, $productId, $quantite, $frais = 0) {
        try {
            $db = Flight::db();

            $sqlBesoin = "SELECT * FROM v_ville_besoins_dons 
                          WHERE ville_id = :ville_id AND product_id = :product_id";
            $stmtBesoin = $db->prepare($sqlBesoin);
            $stmtBesoin->execute([':ville_id' => $villeId, ':product_id' => $productId]);
            $besoinInfo = $stmtBesoin->fetch(\PDO::FETCH_ASSOC);

            if (!$besoinInfo) {
                return ['success' => false, 'message' => 'Aucun besoin trouvé pour cette ville et ce produit'];
            }

            $sqlProduit = "SELECT id, nom, prix_unitaire FROM s3fin_product WHERE id = :id";
            $stmtProduit = $db->prepare($sqlProduit);
            $stmtProduit->execute([':id' => $productId]);
            $produit = $stmtProduit->fetch(\PDO::FETCH_ASSOC);

            if (!$produit || !$produit['prix_unitaire']) {
                return ['success' => false, 'message' => 'Produit introuvable ou sans prix défini'];
            }

            $donsArgent = $this->achatModel->getDonsArgentDisponibles();
            $totalArgentDisponible = 0;
            foreach ($donsArgent as $don) {
                $totalArgentDisponible += $don['montant_disponible'];
            }

            $coutBase = $quantite * $produit['prix_unitaire'];
            $montantFrais = $coutBase * ($frais / 100);
            $coutTotal = $coutBase + $montantFrais;

            if ($coutTotal > $totalArgentDisponible) {
                return [
                    'success' => false,
                    'message' => 'Fonds insuffisants',
                    'mode' => 'simulation',
                    'produit' => $produit['nom'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit['prix_unitaire'],
                    'frais_pourcentage' => $frais,
                    'cout_base' => $coutBase,
                    'montant_frais' => $montantFrais,
                    'cout_total' => $coutTotal,
                    'montant_disponible' => $totalArgentDisponible,
                    'deficit' => $coutTotal - $totalArgentDisponible,
                    'besoin_restant' => $besoinInfo['besoin_restant'] ?? 0
                ];
            }

            return [
                'success' => true,
                'message' => 'Simulation réussie - Achat possible',
                'mode' => 'simulation',
                'produit' => $produit['nom'],
                'quantite' => $quantite,
                'prix_unitaire' => $produit['prix_unitaire'],
                'frais_pourcentage' => $frais,
                'cout_base' => $coutBase,
                'montant_frais' => $montantFrais,
                'cout_total' => $coutTotal,
                'montant_disponible' => $totalArgentDisponible,
                'reste_apres_achat' => $totalArgentDisponible - $coutTotal,
                'besoin_restant' => $besoinInfo['besoin_restant'] ?? 0,
                'dons_argent' => $donsArgent
            ];

        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()];
        }
    }

    public function validateAchat($donArgentId, $productId, $quantite, $frais = 0, $besoinId = null) {
        try {
            $result = $this->achatModel->createAchat($donArgentId, $productId, $quantite, $frais, $besoinId);
            
            if ($result['success']) {
                $result['mode'] = 'validation';
                $result['message'] = 'Achat validé et enregistré avec succès';
            }

            return $result;

        } catch (\Exception $e) {
            return ['success' => false, 'mode' => 'validation', 'message' => 'Erreur lors de la validation: ' . $e->getMessage()];
        }
    }

    public function getAchatsByVille($villeId) {
        try {
            $achats = $this->achatModel->getAchatsByVille($villeId);
            return ['success' => true, 'achats' => $achats, 'total' => count($achats)];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur lors de la récupération des achats: ' . $e->getMessage()];
        }
    }

    public function apiSimulateAchat() {
        $villeId = Flight::request()->data->ville_id ?? null;
        $productId = Flight::request()->data->product_id ?? null;
        $quantite = Flight::request()->data->quantite ?? 0;
        $frais = Flight::request()->data->frais ?? 0;

        if (!$villeId || !$productId || !$quantite) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants (ville_id, product_id, quantite requis)']);
            return;
        }

        $result = $this->simulateAchat($villeId, $productId, $quantite, $frais);
        Flight::json($result);
    }

    public function apiValidateAchat() {
        $donArgentId = Flight::request()->data->don_argent_id ?? null;
        $productId = Flight::request()->data->product_id ?? null;
        $quantite = Flight::request()->data->quantite ?? 0;
        $frais = Flight::request()->data->frais ?? 0;
        $besoinId = Flight::request()->data->besoin_id ?? null;

        if (!$donArgentId || !$productId || !$quantite) {
            Flight::json(['success' => false, 'message' => 'Paramètres manquants (don_argent_id, product_id, quantite requis)']);
            return;
        }

        $result = $this->validateAchat($donArgentId, $productId, $quantite, $frais, $besoinId);
        Flight::json($result);
    }

    public function apiGetAchatsByVille($villeId) {
        $result = $this->getAchatsByVille($villeId);
        Flight::json($result);
    }

    public function apiGetAllAchats() {
        $achats = $this->achatModel->getAllAchats();
        Flight::json(['success' => true, 'achats' => $achats, 'total' => count($achats)]);
    }

    public function apiGetDonsArgent() {
        $dons = $this->achatModel->getDonsArgentDisponibles();
        Flight::json(['success' => true, 'dons' => $dons, 'total' => count($dons)]);
    }
}
