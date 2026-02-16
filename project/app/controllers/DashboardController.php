<?php
namespace app\controllers;

use flight\Engine;
use app\models\BesoinModel;
use app\models\DonsModel;
use Flight;

class DashboardController
{
    protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}
    
    public function index() {
        $besoinModel = new BesoinModel(Flight::db());
        $donsModel = new DonsModel(Flight::db());
        
        $data = $besoinModel->getBesoinDonsParVille();
        
        // Récupérer les totaux directement depuis les tables (données exactes de la base)
        $totalDons = $donsModel->getTotalDons();  // SUM(quantite) - somme des quantités
        $totalBesoins = $besoinModel->getTotalBesoins();
        $villesCount = $besoinModel->countVillesAvecBesoins();
        
        $this->app->render('index', [
            'data' => $data,
            'totalDons' => $totalDons,
            'totalBesoins' => $totalBesoins,
            'villesCount' => $villesCount
        ]);
    }
    
}
