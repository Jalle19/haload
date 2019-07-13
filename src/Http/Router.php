<?php

namespace Jalle19\Haload\Http;

use Jalle19\Haload\Http\Controller\HaproxyController;
use Jalle19\Haload\Http\Controller\IndexController;
use Jalle19\Haload\Http\Controller\LoadBalancerController;
use Jalle19\Haload\Http\Controller\StaticAssetController;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\RouteDataArray;

/**
 * Class Router
 * @package Jalle19\Haload\Http
 */
class Router
{

	/**
	 * @var RouteDataArray
	 */
	private $data = [];


	/**
	 * @return RouteDataArray
	 */
	public function getData()
	{
		if (empty($this->data)) {
			// Define the routes
			$router = new RouteCollector();
			$router->get('/', [IndexController::class, 'indexAction']);
			$router->get('/styles.css', [StaticAssetController::class, 'stylesAction']);
			$router->get('/scripts.js', [StaticAssetController::class, 'scriptsAction']);
			$router->get('/haproxy/dump-configuration/{type}', [HaproxyController::class, 'dumpConfigurationAction']);
			$router->get('/haproxy/start', [HaproxyController::class, 'startProcessAction']);
			$router->get('/haproxy/restart', [HaproxyController::class, 'restartProcessAction']);
			$router->get('/haproxy/stop', [HaproxyController::class, 'stopProcessAction']);
			$router->get('/loadbalancer/create', [LoadBalancerController::class, 'createAction']);
			$router->post('/loadbalancer/create', [LoadBalancerController::class, 'createAction']);

			$this->data = $router->getData();
		}

		return $this->data;
	}

}
