<?php
namespace app\controllers;

use flight\Engine;
use app\models\DonsModel;

class DonsController
{
    protected Engine $app;

    public function __construct($app) {
        $this->app = $app;
    }

    public function index() {
        $donsModel = new DonsModel($this->app->db());
        $products = $donsModel->getAllProducts();
        $categories = $donsModel->getAllCategories();
        $dons = $donsModel->getAllDons();
        $this->app->render('dons', [
            'products' => $products,
            'categories' => $categories,
            'dons' => $dons,
            'BASE_URL' => BASE_URL
        ]);
    }

    public function insertDon() {
        $id_product = $this->app->request()->data->produit;
        $descriptions = $this->app->request()->data->description ?? '';
        $quantite = $this->app->request()->data->quantite;

        $donsModel = new DonsModel($this->app->db());
        $donsModel->insertDon($id_product, $descriptions, $quantite);
        
        $products = $donsModel->getAllProducts();
        $categories = $donsModel->getAllCategories();
        $dons = $donsModel->getAllDons();
        $this->app->render('dons', [
            'products' => $products,
            'categories' => $categories,
            'dons' => $dons,
            'success' => 'Don enregistré avec succès!',
            'BASE_URL' => BASE_URL
        ]);
    }

    public function insertProduct() {
        $nom = $this->app->request()->data->nom_produit ?? '';
        $prix_unitaire = $this->app->request()->data->prix_unitaire ?? 0;
        $categorie_id = $this->app->request()->data->categorie ?? null;

        $donsModel = new DonsModel($this->app->db());
        $productId = $donsModel->insertProduct($nom, $prix_unitaire, $categorie_id);

        $this->app->json(['success' => true, 'id' => $productId]);
    }
}
