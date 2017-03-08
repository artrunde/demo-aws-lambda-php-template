<?php

namespace DemoAWS\Models;

use DemoAWS\Library\ODM;

class Users extends ODM {

	public $user_id;

	public $name;

	public $age;

	public $country;

	protected $_table_name = 'users';

	protected $_hash_key   = 'user_id';

	protected $_schema = array(
		'user_id'    => 'N',  // user_id is number
		'name'       => 'S',  // name is string
		'age'        => 'N',
		'country'    => 'S'
	);

}