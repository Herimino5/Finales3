<?php
namespace app\controllers;

use flight\Engine;
use app\models\BesoinModel;
use app\models\DonsModel;

use Flight;

class DonsController
{
    protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}
    public function insertDon() {
        $donsModel = new DonsModel(Flight::db());
        $donsModel->insertDon();
        Flight::redirect('/');
    }
}
