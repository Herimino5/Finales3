<?php
// filepath: /opt/lampp/htdocs/Finales3/project/app/controllers/ReinitialiserController.php
namespace app\controllers;

use flight\Engine;
use app\service\Reinitialiser;
use Flight;

class ReinitialiserController {
    protected Engine $app;
    protected Reinitialiser $service;

    public function __construct($app) {
        $this->app = $app;
        $this->service = new Reinitialiser(Flight::db());
    }

    /**
     * API : Récupérer l'état actuel (avant réinitialisation)
     */
    public function etat() {
        $result = $this->service->getEtatActuel();
        Flight::json($result);
    }

    /**
     * API : Exécuter la réinitialisation
     */
    public function reinitialiser() {
        $result = $this->service->reinitialiser();
        Flight::json($result);
    }
}