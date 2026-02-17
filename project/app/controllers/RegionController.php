<?php
namespace app\controllers;

use flight\Engine;
use Flight;

class RegionController {
    protected Engine $app;

    public function __construct(Engine $app) {
        $this->app = $app;
    }

    // Affiche la liste des régions
    public function index() {
        $regionModel = new \app\models\RegionModel(Flight::db());
        $regions = $regionModel->getAllRegions();
        
        // Ajouter le nombre de villes par région
        foreach ($regions as &$region) {
            $region['nb_villes'] = $regionModel->countVillesByRegion($region['id']);
        }
        
        $this->app->render('regions', ['regions' => $regions]);
    }

    // Ajoute une nouvelle région
    public function store() {
        $nom = trim($_POST['nom'] ?? '');
        
        if (empty($nom)) {
            Flight::redirect('/regions?error=1');
            return;
        }
        
        $regionModel = new \app\models\RegionModel(Flight::db());
        if ($regionModel->insertRegion(['nom' => $nom])) {
            Flight::redirect('/regions?success=1');
        } else {
            Flight::redirect('/regions?error=1');
        }
    }

    // API: Ajoute une région (pour AJAX)
    public function apiStore() {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $nom = trim($data['nom'] ?? '');
        
        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Nom requis']);
            return;
        }
        
        $regionModel = new \app\models\RegionModel(Flight::db());
        if ($regionModel->insertRegion(['nom' => $nom])) {
            $regions = $regionModel->getAllRegions();
            echo json_encode(['success' => true, 'regions' => $regions]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur insertion']);
        }
    }

    // Met à jour une région
    public function update($id) {
        $nom = trim($_POST['nom'] ?? '');
        
        if (empty($nom)) {
            Flight::redirect('/regions?error=1');
            return;
        }
        
        $regionModel = new \app\models\RegionModel(Flight::db());
        if ($regionModel->updateRegion($id, ['nom' => $nom])) {
            Flight::redirect('/regions?success=2');
        } else {
            Flight::redirect('/regions?error=1');
        }
    }

    // Supprime une région
    public function delete($id) {
        $regionModel = new \app\models\RegionModel(Flight::db());
        
        // Vérifier si la région a des villes
        if ($regionModel->countVillesByRegion($id) > 0) {
            Flight::redirect('/regions?error=2');
            return;
        }
        
        if ($regionModel->deleteRegion($id)) {
            Flight::redirect('/regions?success=3');
        } else {
            Flight::redirect('/regions?error=1');
        }
    }
}
