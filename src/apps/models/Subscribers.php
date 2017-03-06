<?php

namespace DemoAWS\Models;

use Aws\DynamoDb\DynamoDbClient;
use Phalcon\Mvc\User\Component;

/**
 * Class Products
 * @property DynamoDbClient $dynamoDBClient
 */
class Subscribers extends Component
{

    public function getAll()
    {
		$response = $this->dynamoDBClient->scan([
			'TableName' => 'subscribers'
		]);

		return $response;
    }
}
