<?php
namespace app\controllers;

use flight\Engine;
use app\models\RecapModel;
use Flight;

class RecapController
{
    protected Engine $app;
    protected RecapModel $recapModel;

    public function __construct($app) {
        $this->app = $app;
        $this->recapModel = new RecapModel(Flight::db());
    }

    /**
     * Afficher la page de récapitulation
     */
    public function index() {
        $recapGlobal = $this->recapModel->getRecapGlobal();
        $recapParVille = $this->recapModel->getRecapParVille();
        $recapParProduit = $this->recapModel->getRecapParProduit();
        $recapDetaille = $this->recapModel->getRecapDetaille();

        $this->app->render('recap', [
            'recapGlobal' => $recapGlobal,
            'recapParVille' => $recapParVille,
            'recapParProduit' => $recapParProduit,
            'recapDetaille' => $recapDetaille,
            'BASE_URL' => BASE_URL
        ]);
    }

    /**
     * API : Récupérer les données de récapitulation en JSON (pour Ajax)
     */
    public function apiGetRecap() {
        $recapGlobal = $this->recapModel->getRecapGlobal();
        $recapParVille = $this->recapModel->getRecapParVille();
        $recapParProduit = $this->recapModel->getRecapParProduit();
        $recapDetaille = $this->recapModel->getRecapDetaille();

        Flight::json([
            'success' => true,
            'timestamp' => date('d/m/Y H:i:s'),
            'global' => $recapGlobal,
            'par_ville' => $recapParVille,
            'par_produit' => $recapParProduit,
            'detaille' => $recapDetaille
        ]);
    }
}