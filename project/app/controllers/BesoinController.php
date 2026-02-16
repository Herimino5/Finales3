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
        $besoinModel = new BesoinModel(Flight::db());
        
        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        $produits = $produitModel->getAllProduits();
        $villes = $villeModel->getAllVilles();
        $categories = $produitModel->getAllCategories();
        $besoins = $besoinModel->getBesoinsPaginated($page, $perPage);
        $totalBesoins = $besoinModel->countBesoins();
        $totalPages = ceil($totalBesoins / $perPage);
        
        $this->app->render('besoins', [
            'produits' => $produits,
            'villes' => $villes,
            'categories' => $categories,
            'besoins' => $besoins,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBesoins' => $totalBesoins,
            'BASE_URL' => BASE_URL
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
        
        // Recharger les données et afficher la page
        $produitModel = new ProduitModel(Flight::db());
        $villeModel = new VilleModel(Flight::db());
        
        // Pagination
        $page = 1; // Retour à la première page après insertion
        $perPage = 10;
        
        $produits = $produitModel->getAllProduits();
        $villes = $villeModel->getAllVilles();
        $categories = $produitModel->getAllCategories();
        $besoins = $besoinModel->getBesoinsPaginated($page, $perPage);
        $totalBesoins = $besoinModel->countBesoins();
        $totalPages = ceil($totalBesoins / $perPage);
        
        $this->app->render('besoins', [
            'produits' => $produits,
            'villes' => $villes,
            'categories' => $categories,
            'besoins' => $besoins,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBesoins' => $totalBesoins,
            'success' => 'Besoin enregistré avec succès!',
           
        ]);
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
