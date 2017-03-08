<?php

namespace DemoAWS\Controllers;

use Phalcon\Mvc\Controller;
use DemoAWS\Models\Subscribers;

class SubscribersController extends Controller
{
    public function indexAction()
    {

		$subscriber = new Subscribers();

		$response = $subscriber->getAll();

		$this->response->setJsonContent($response['Items']);
		$this->response->send();
    }
}
