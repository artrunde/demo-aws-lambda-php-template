<?php

namespace DemoAWS;

use Aws\DynamoDb\DynamoDbClient;
use Phalcon\DI;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Mvc\Model\Metadata\Memory as MemoryMetaData;

class Application extends BaseApplication
{
    protected function registerAutoloaders()
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                'DemoAWS\Controllers' => '../apps/controllers/',
                'DemoAWS\Models'      => '../apps/models/',
				'DemoAWS\Library'     => '../apps/library/'
            ]
        );

		$loader->registerFiles(
			[
				'../../vendor/autoload.php',
			]
		);

        $loader->register();
    }

    /**
     * This methods registers the services to be used by the application
     */
    protected function registerServices()
    {
        $di = new DI();

        // Registering a router
        $di->set('router', function () {

            $router = new Router(false);

			// Define a route
			$router->addGet(
				"/subscribers/",
				[
					"controller" => "subscribers",
					"action"     => "index",
				]
			);

			// Define a route
			$router->addGet(
				"/debug/",
				[
					"controller" => "index",
					"action"     => "debug",
				]
			);

			// Define a route
			$router->addGet(
				"/users/{id}",
				[
					"controller" => "subscribers",
					"action"     => "get",
				]
			);

			$router->setUriSource(Router::URI_SOURCE_SERVER_REQUEST_URI);

			return $router;

        });

        // Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('DemoAWS\Controllers\\');
            return $dispatcher;
        });

        // Registering a Http\Response
        $di->set('response', function () {
            return new Response();
        });

        // Registering a Http\Request
        $di->set('request', function () {
            return new Request();
        });

        // Registering the view component
        $di->set('view', function () {
            $view = new View();
            $view->disable();
            return $view;
        });

		/**
		 * Services can be registered as "shared" services this means that they always will act as singletons. Once the service is resolved for the first time the same instance of it is returned every time a consumer retrieve the service from the container:
		 */
		$di->setShared(
			"dynamoDBClient",
			function () {
				return new DynamoDbClient([
					'version'  => 'latest',
					'region'   => getenv('AWS_REGION')
				]);
			}
		);

        // Registering the Models-Metadata
        $di->set('modelsMetadata', function () {
            return new MemoryMetaData();
        });

        // Registering the Models Manager
        $di->set('modelsManager', function () {
            return new ModelsManager();
        });

        $this->setDI($di);
    }

    public function registerRoutes() {

	}

    public function main()
    {

        $this->registerServices();
		$this->registerAutoloaders();
		$this->registerRoutes();

        echo $this->handle()->getContent();
    }
}

try {

	/**
	 * Create MVC application
	 */
    $application = new Application();

	/**
	 * Handle
	 */
    $application->main();

} catch (\Exception $e) {
    echo $e->getMessage();
}
