<?php

$routes = [
	[
		'GET',
		'/^\/$/',
		'main',
		'main'
	],
	[
		'POST',
		'/\/api/',
		'api',
		'api'
	],
	[
		'GET',
		'/\/([\w+]+)/',
		'letters',
		'letters'
	],
];

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

$router = new app\routing\Router();

foreach ($routes as $routeData) {
	$route = new app\routing\Route(...$routeData);
	$router->addRoute($route);
}
