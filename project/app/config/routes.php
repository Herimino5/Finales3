<?php

use app\controllers\ApiExampleController;
use app\controllers\DashboardController;
use app\middlewares\SecurityHeadersMiddleware;
use app\controllers\BesoinController;
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
	$router->get('/donsform', [ DonsController::class, 'index' ]);
	$router->post('/donsInsert', [ DonsController::class, 'insertDon' ]);
	$router->post('/donsProductInsert', [ DonsController::class, 'insertProduct' ]);
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