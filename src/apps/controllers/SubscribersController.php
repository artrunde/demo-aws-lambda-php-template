<?php

namespace DemoAWS\Controllers;

use DemoAWS\Models\Users;
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

    public function getAction($id)
	{
		$user = Users::findFirst($id);

		if($user) {
			$response = array("name" => $user->name, "age" => $user->age);
		} else {
			$response = array("notfound");
		}

		$this->response->setJsonContent($response);
		$this->response->send();

	}
}
