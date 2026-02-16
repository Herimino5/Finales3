<?php
namespace app\controllers;

use flight\Engine;
use app\models\BesoinModel;
use app\models\ProduitModel;
use app\models\VilleModel;
use Flight;

class BesoinController
{
    protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}
    
    public function saisirBesoin() {
        $produitModel = new ProduitModel(Flight::db());
        $villeModel = new VilleModel(Flight::db());
        
        $produits = $produitModel->getAllProduits();
        $villes = $villeModel->getAllVilles();
        $categories = $produitModel->getAllCategories();
        
        $this->app->render('besoins', [
            'produits' => $produits,
            'villes' => $villes,
            'categories' => $categories
        ]);
    }
    
    public function insertBesoin() {
        // Récupérer les données du formulaire
        $data = [
            'ville_id' => Flight::request()->data->ville ?? null,
            'Date_saisie' => date('Y-m-d H:i:s'),
            'id_product' => Flight::request()->data->produit ?? null,
            'descriptions' => Flight::request()->data->description ?? '',
            'quantite' => Flight::request()->data->quantite ?? 0
        ];
        
        // Insérer le besoin
        $besoinModel = new BesoinModel(Flight::db());
        $besoinModel->insertBesoin($data);
        
        $this->app->redirect('/besoinsform');
    }
    
    public function insertProduit() {
        // Récupérer les données du nouveau produit
        $data = [
            'nom' => Flight::request()->data->nom_produit ?? '',
            'prix_unitaire' => Flight::request()->data->prix_unitaire ?? 0,
            'categorie_id' => Flight::request()->data->categorie ?? null
        ];
        
        // Insérer le produit
        $produitModel = new ProduitModel(Flight::db());
        $produitId = $produitModel->insertProduit($data);
        
        Flight::json(['success' => true, 'id' => $produitId]);
    }

}
