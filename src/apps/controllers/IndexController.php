<?php

namespace DemoAWS\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
	public function indexAction()
	{
		$this->response->setJsonContent(array('Hello World'));
		$this->response->send();
	}

	public function debugAction()
	{
		$this->response->setHeader('Content-Type', 'application/json');
		$this->response->setContent(getenv('EVENT_PARAMS'));
		$this->response->send();
	}
}
