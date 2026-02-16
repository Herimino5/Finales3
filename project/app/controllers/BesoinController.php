<?php
namespace app\controllers;

use flight\Engine;
use app\models\BesoinModel;
use Flight;

class BesoinController
{
    protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}
    public function saisirBesoin() {
        $this->app->render('dashboard', [ 'message' => 'niova ve You are gonna do great things!' ]);
    }
    public function insertBesoin() {
        $besoinModel = new BesoinModel(Flight::db());
        $besoinModel->insertBesoin();
        $this->app->redirect('/');
    }

}
