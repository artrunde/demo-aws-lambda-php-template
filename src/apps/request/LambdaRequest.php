<?php

namespace DemoAWS\Request;

use Phalcon\Http\Request;

class LambdaRequest extends Request {

    public function __construct()
    {
        $this->_rawBody = file_get_contents('php://stdin');
    }

    public function getRawBody()
    {
        return $this->_rawBody;
    }

}