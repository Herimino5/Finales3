<?php

use app\controllers\ApiExampleController;
use app\controllers\DashboardController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\BesoinController;
use app\controllers\DistributionController;
use app\controllers\AchatController;
use app\controllers\DispatchController;
use flight\Engine;
use flight\net\Router;

use app\controllers\DonsController;
/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/',[DashboardController::class,'index'] );
	$router->get('/besoinsform', [ BesoinController::class, 'saisirBesoin' ]);
	$router->post('/besoinsInsert', [ BesoinController::class, 'insertBesoin' ]);
	$router->post('/produitsInsert', [ BesoinController::class, 'insertProduit' ]);
	// $router->get('/donsform', function() use ($app) {
	// 	$app->render('dons', [ 'message' => 'niova ve You are gonna do great things!' ]);
	// });
	$router->post('/donsInsert', [ DonsController::class, 'insertDon' ]);
	$router->get('/distributions', [ DistributionController::class, 'index' ]);
	$router->post('/distribuerAutomatique', [ DistributionController::class, 'distribuerAutomatique' ]);
	$router->get('/donsform', [ DonsController::class, 'index' ]);
	$router->post('/donsInsert', [ DonsController::class, 'insertDon' ]);
	$router->post('/donsProductInsert', [ DonsController::class, 'insertProduct' ]);

	// Routes Achats
	$router->get('/achats', [ AchatController::class, 'index' ]);
	
	// API Achats
	$router->post('/api/achat/simulate', [ AchatController::class, 'apiSimulateAchat' ]);
	$router->post('/api/achat/validate', [ AchatController::class, 'apiValidateAchat' ]);
	$router->get('/api/achat/list', [ AchatController::class, 'apiGetAllAchats' ]);
	$router->get('/api/achat/ville/@villeId', [ AchatController::class, 'apiGetAchatsByVille' ]);
	$router->get('/api/achat/dons-argent', [ AchatController::class, 'apiGetDonsArgent' ]);

	// API Dispatch
	$router->get('/api/dispatch/besoins', [ DispatchController::class, 'apiGetBesoinsNonCouverts' ]);
	$router->post('/api/dispatch/verifier-achat', [ DispatchController::class, 'apiVerifierAchat' ]);
	$router->post('/api/dispatch/attribuer-don', [ DispatchController::class, 'apiAttribuerDon' ]);
	$router->get('/api/dispatch/besoin/@besoinId', [ DispatchController::class, 'apiVerifierBesoin' ]);

	// $router->get('/hello-world/@name', function($name) {
	// 	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
	// });

	// $router->get('/test-route',function() use ($app) {
	// 	echo '<h1>route iray afa</h1>';
	// });

	// $router->group('/api', function() use ($router) {
	// 	$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
	// 	$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
	// 	$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
	// });
	
}, [ SecurityHeadersMiddleware::class ]);