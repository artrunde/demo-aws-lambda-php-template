<?php

namespace DemoAWS\Library;

use Aws\DynamoDb\DynamoDbClient;
use Phalcon\Mvc\User\Component;

/**
 * Class Products
 * @property DynamoDbClient $dynamoDBClient
 */
class DynamoDBORM extends Component
{

	/**
	 * data schema
	 *
	 * @var array
	 *
	 *        ex)
	 *        $_schema = array(
	 *               'field_name_1' => 'S',
	 *               'field_name_2' => 'N',
	 *               );
	 */
	protected $_schema = array();

	// DynamoDB TableName
	protected $_table_name;

	// HashKey
	protected $_hash_key;

	// RangeKey
	protected $_range_key;

	// ConsistentRead (QueryParameter)
	protected $_consistent_read = false;

	/**
	 * DynamoDB record data is stored here as an associative array
	 *
	 * @var array
	 */
	protected $_data = array();

	protected $_data_original = array();

	/**
	 * @param string $class_name
	 *
	 * @return $this instance of the ORM sub class
	 */
	public static function factory($class_name)
	{
		/** @var self $object */
		return new $class_name();
	}

	/**
	 * _formatAttributes
	 *
	 * @param array $array
	 *
	 * $array = array(
	 *     'name' => 'John',
	 *     'age'  => 20,
	 * );
	 *
	 * @return array $result
	 *
	 * $result = array(
	 *     'name' => array('S' => 'John'),
	 *     'age'  => array('N' => 20),
	 * );
	 */
	protected function _formatAttributes($array)
	{
		$result = array();

		foreach ($array as $key => $value)
		{
			$type = $this->_getDataType($key);

			if ($type == 'S' || $type == 'N')
			{
				$value = strval($value);
			}

			$result[$key] = array($type => $value);
		}

		return $result;
	}

	/**
	 * Convert result array to simple associative array
	 *
	 * @param array $item
	 *
	 * $item = array(
	 *     'name'   => array('S' => 'John'),
	 *     'age'    => array('N' =>  30)
	 * );
	 *
	 * @return array $hash
	 *
	 * $hash = array(
	 *     'name'   => 'John',
	 *     'age'    => 30,
	 *  );
	 */
	protected function _formatResult(array $item)
	{
		$hash = array();

		foreach ($item as $key => $value)
		{
			$values     = array_values($value);
			$hash[$key] = $values[0];
		}

		return $hash;
	}

	/**
	 * Return data type using $_schema
	 *
	 * @param  string $key
	 *
	 * @return string $type
	 *
	 *          S:  String
	 *          N:  Number
	 *          B:  Binary
	 *          SS: A set of strings
	 *          NS: A set of numbers
	 *          BS: A set of binary
	 */
	protected function _getDataType($key)
	{
		$type = 'S';

		if (isset($this->_schema[$key]))
		{
			$type = $this->_schema[$key];
		}

		return $type;
	}

	public static function findFirst($hash_key_value, $range_key_value = null, array $options = array())
	{
		$class_name = get_called_class();

		$instance = self::factory($class_name);

		return $instance->findOne($hash_key_value, $range_key_value, $options);
	}

	/**
	 * @param $hash_key_value
	 * @param null $range_key_value
	 * @param array $options
	 * @return null
	 * @throws \Exception
	 */
	public function findOne($hash_key_value, $range_key_value = null, array $options = array())
	{
		$conditions = array(
			$this->_hash_key => $hash_key_value,
		);

		if ($range_key_value)
		{
			if (!$this->_range_key)
			{
				throw new \Exception("Range key is not defined.");
			}

			$conditions[$this->_range_key] = $range_key_value;
		}

		$key  = $this->_formatAttributes($conditions);

		$args = array(
			'TableName'              => $this->_table_name,
			'Key'                    => $key,
			'ConsistentRead'         => $this->_consistent_read,
			'ReturnConsumedCapacity' => 'TOTAL'
		);

		// Merge $options to $args
		$option_names = array('AttributesToGet', 'ReturnConsumedCapacity');

		foreach ($option_names as $option_name)
		{
			if (isset($options[$option_name]))
			{
				$args[$option_name] = $options[$option_name];
			}
		}

		$item = $this->dynamoDBClient->getItem($args);

		if (!is_array($item['Item']))
		{
			return false;
		}

		$result = $this->_formatResult($item['Item']);

		$this->hydrate($result);

		return $this;
	}

	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function hydrate(array $data = array())
	{
		foreach ($data as $key => $value)
		{
			$this->set($key, $value);
		}

		$this->_data_original = $this;
	}

	/**
	 * Set a property to a particular value on this object.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set($key, $value)
	{
		if (array_key_exists($key, $this->_schema))
		{
			$type = $this->_getDataType($key);

			if ($type == 'S')
			{
				$value = strval($value);
			}
			else if( $type == 'N' )
			{
				$value = (int) $value;
			}

			if(property_exists(get_called_class(), $key))
			{
				$this->$key = $value;
			}

		}

	}

}