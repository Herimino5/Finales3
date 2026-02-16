<?php
namespace app\controllers;

use flight\Engine;
use app\models\DistributionModel;
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
        
        $this->app->render('distributions', [
            'distributions' => $distributions,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalDistributions' => $totalDistributions
        ]);
    }
    
    /**
     * Lancer la distribution automatique
     */
    public function distribuerAutomatique() {
        $distributionModel = new DistributionModel(Flight::db());
        
        try {
            $resultats = $distributionModel->distribuerDonsAutomatique();
            
            // Recharger les données
            $page = 1;
            $perPage = 10;
            
            $distributions = $distributionModel->getDistributionsPaginated($page, $perPage);
            $totalDistributions = $distributionModel->countDistributions();
            $totalPages = ceil($totalDistributions / $perPage);
            
            $this->app->render('distributions', [
                'distributions' => $distributions,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalDistributions' => $totalDistributions,
                'success' => count($resultats) . ' distribution(s) effectuée(s) avec succès!',
                'resultats' => $resultats
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
