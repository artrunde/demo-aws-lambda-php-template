<?php
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Router;

try {

	$app = new Micro();

	$app->get(
		"/say/welcome/{name}",
		function ($name) {
			echo "<h1>Welcome $name!</h1>";
		}
	);

	$app->router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
	$app->handle();

} catch (Exception $e) {
	echo "<pre>";
	print_r($e);
}

/*
 * $app->router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);
	echo "<br/>";
	var_dump($app->request->isGet());
	echo "<br/>";
	print_r($app->request->getMethod());
	echo "<br/>";
	print_r($app->request->getURI());
	echo "<br/>";
	print_r($app->request->getHeaders());
	echo "<br/>";
	print_r(json_decode($_SERVER['EVENT_PARAMS'], true));
 */
