<?php

namespace DemoAWS\Controllers;

use DemoAWS\Models\Users;
use Phalcon\Mvc\Controller;

class UsersController extends Controller
{

    public function findOneAction($id)
	{
		/**
		 * @var Users $user
		 */
		$user = Users::factory('DemoAWS\Models\Users')->findOne($id);

		$this->response->setJsonContent($user);
		$this->response->send();

	}

    public function deleteAction($user_id)
    {
        /**
         * @var Users $user
         */
        $user = Users::factory('DemoAWS\Models\Users')->findOne($user_id);
        $user->delete();

        $this->response->send();

    }

	public function findAllAction()
	{
		/**
		 * @var Users $user
		 */
		$users = Users::factory('DemoAWS\Models\Users')->findAll();

		$this->response->setJsonContent($users);
		$this->response->send();

	}

	public function searchAction($age)
	{
	    $limit = $this->request->getQuery('limit');

	    if($limit) {

            /**
             * @var Users $user
             */
            $users = Users::factory('DemoAWS\Models\Users')
                ->where('age', '=', $age)
                ->limit(10)
                ->index('age-index')
                ->findMany();

        } else {

            /**
             * @var Users $user
             */
            $users = Users::factory('DemoAWS\Models\Users')
                ->where('age', '=', $age)
                ->index('age-index')
                ->findMany();

        }

		$this->response->setJsonContent($users);
		$this->response->send();
	}

	public function createAction()
	{
		/**
		 * @var Users $user
		 */
		$user = Users::factory('DemoAWS\Models\Users')->create(array('name' => 'newName','age' => 66.6, 'country' => '', 'user_id' =>  rand(1000,9000)));

		$result = $user->save();

		$this->response->setJsonContent($result);
		$this->response->send();
	}

	public function updateAction($user_id)
	{

		$paramArray = $this->request->getJsonRawBody(true);

		/**
		 * @var Users $user
		 */
		$user = Users::factory('DemoAWS\Models\Users')->findOne($user_id);
		$user->name = $paramArray['name'];
        $user->save();

		$this->response->setJsonContent($user);
		$this->response->send();
	}
}
