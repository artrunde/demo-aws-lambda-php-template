<?php

namespace DemoAWS\Controllers;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
	public function indexAction()
	{
		$this->response->setJsonContent(array('Hello' => 'World'));
		$this->response->send();
	}
}
