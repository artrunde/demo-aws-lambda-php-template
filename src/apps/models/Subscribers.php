<?php

namespace DemoAWS\Models;

use Aws\DynamoDb\DynamoDbClient;
use Phalcon\Mvc\User\Component;

/**
 * Class Products
 * @property DynamoDbClient $db
 */
class Subscribers extends Component
{

    public function getAll()
    {
		$response = $this->db->scan([
			'TableName' => 'subscribers'
		]);

		return $response;
    }
}
