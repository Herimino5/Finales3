<?php
namespace app\controllers;

use flight\Engine;
use app\models\DistributionModel;
use app\service\Distributionfifo;
use app\service\DistributionProportionnel;
use app\service\DistributionQuantite;
use Flight;

class DistributionController
{
    protected Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }
    
    /**
     * Afficher la page des distributions
     */
    public function index() {
        $distributionModel = new DistributionModel(Flight::db());
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $distributions = $distributionModel->getDistributionsPaginated($page, $perPage);
        $totalDistributions = $distributionModel->countDistributions();
        $totalPages = ceil($totalDistributions / $perPage);
        
        // Récupérer les détails par ville pour le résumé
        $detailsParVille = $distributionModel->getDistributionsParVille();
        
        $this->app->render('distributions', [
            'distributions' => $distributions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalDistributions' => $totalDistributions,
            'detailsParVille' => $detailsParVille
        ]);
    }
    
    /**
     * Lancer la distribution automatique
     */
    public function distribuerAutomatique() {
        $distributionModel = new DistributionModel(Flight::db());
        
        // Récupérer le mode de distribution
        $mode = isset($_POST['mode']) ? $_POST['mode'] : 'fifo';
        
        try {
            $resultats = [];
            
            // Instancier le service approprié et exécuter la distribution
            switch($mode) {
                case 'fifo':
                    $service = new Distributionfifo(Flight::db());
                    $resultats = $service->distribuer();
                    break;
                    
                case 'proportionnel':
                    $service = new DistributionProportionnel(Flight::db());
                    $resultats = $service->distribuer();
                    break;
                    
                case 'quantite':
                    $service = new DistributionQuantite(Flight::db());
                    // Ce service a une méthode valider() qui fait l'insertion en base
                    $result = $service->valider();
                    if ($result['success']) {
                        $resultats = $result['distributions'];
                    } else {
                        throw new \Exception($result['message']);
                    }
                    break;
                    
                default:
                    throw new \Exception('Mode de distribution invalide');
            }
            
            // Recharger les données
            $page = 1;
            $perPage = 10;
            
            $distributions = $distributionModel->getDistributionsPaginated($page, $perPage);
            $totalDistributions = $distributionModel->countDistributions();
            $totalPages = ceil($totalDistributions / $perPage);
            
            // Récupérer les détails par ville pour le résumé
            $detailsParVille = $distributionModel->getDistributionsParVille();
            
            $this->app->render('distributions', [
                'distributions' => $distributions,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalDistributions' => $totalDistributions,
                'detailsParVille' => $detailsParVille,
                'success' => count($resultats) . ' distribution(s) effectuée(s) avec succès en mode ' . strtoupper($mode) . '!',
                'resultats' => $resultats,
                'mode' => $mode
            ]);
        } catch (\Exception $e) {
            $this->app->render('distributions', [
                'distributions' => [],
                'currentPage' => 1,
                'totalPages' => 0,
                'totalDistributions' => 0,
                'error' => 'Erreur lors de la distribution: ' . $e->getMessage()
            ]);
        }
    }
}
