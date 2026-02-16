<?php
namespace app\controllers;

use flight\Engine;
use app\models\BesoinModel;
use Flight;

class DashboardController
{
    protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}
    public function index() {
        $besoinModel = new BesoinModel(Flight::db());
        $data = $besoinModel->getBesoinDonsParVille();
        $this->app->render('index', [ 'data' => $data ]);
    }
}
