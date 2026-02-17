<?php
namespace app\controllers;

use flight\Engine;
use Flight;

class VilleController {
    protected Engine $app;

    public function __construct(Engine $app) {
        $this->app = $app;
    }

    // Affiche la liste des villes
    public function index() {
        $villeModel = new \app\models\VilleModel(Flight::db());
        $regionModel = new \app\models\RegionModel(Flight::db());
        
        $villes = $villeModel->getAllVilles();
        $regions = $regionModel->getAllRegions();
        
        // Ajouter les stats pour chaque ville
        foreach ($villes as &$ville) {
            $ville['stats'] = $villeModel->getVilleStats($ville['id']);
        }
        
        $this->app->render('villes', [
            'villes' => $villes,
            'regions' => $regions
        ]);
    }

    // Ajoute une nouvelle ville
    public function store() {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'region_id' => $_POST['region_id'] ?? null
        ];
        
        if (empty($data['nom']) || empty($data['region_id'])) {
            Flight::redirect('/villes?error=1');
            return;
        }
        
        $villeModel = new \app\models\VilleModel(Flight::db());
        if ($villeModel->insertVille($data)) {
            Flight::redirect('/villes?success=1');
        } else {
            Flight::redirect('/villes?error=1');
        }
    }

    // Met à jour une ville
    public function update($id) {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'region_id' => $_POST['region_id'] ?? null
        ];
        
        if (empty($data['nom']) || empty($data['region_id'])) {
            Flight::redirect('/villes?error=1');
            return;
        }
        
        $villeModel = new \app\models\VilleModel(Flight::db());
        if ($villeModel->updateVille($id, $data)) {
            Flight::redirect('/villes?success=2');
        } else {
            Flight::redirect('/villes?error=1');
        }
    }

    // Supprime une ville
    public function delete($id) {
        $villeModel = new \app\models\VilleModel(Flight::db());
        if ($villeModel->deleteVille($id)) {
            Flight::redirect('/villes?success=3');
        } else {
            Flight::redirect('/villes?error=1');
        }
    }
}