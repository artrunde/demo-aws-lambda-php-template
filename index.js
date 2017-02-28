'use strict';

console.log('Loading hello world php function');

var spawn = require('child_process').spawn;

exports.handler = function(event, context) {

  var name = "World";
  var responseCode = 200;
  var PHPOutput = '';

  console.log("request: " + JSON.stringify(event));

  // Sets some sane defaults here so that this function doesn't fail when it's not handling a HTTP request from
  // API Gateway.
  var requestMethod = event.httpMethod || 'GET';
  var serverName = event.headers ? event.headers.Host : '';
  var requestUri = event.path || '';

  // Spawn the PHP CGI process with a bunch of environment variables that describe the request.
  var php = spawn('./bin/php-cgi', ['index.php'], {
    env: Object.assign({
      REDIRECT_STATUS: 200,
      REQUEST_METHOD: requestMethod,
      SCRIPT_FILENAME: 'index.php',
      SCRIPT_NAME: '/index.php',
      PATH_INFO: '/',
      SERVER_NAME: serverName,
      SERVER_PROTOCOL: 'HTTP/1.1',
      REQUEST_URI: requestUri
    })
  });

  //send the input event json as string via STDIN to php process
  php.stdin.write(event.body);
  php.stdin.end(); // Close the php stream to unblock php process

  php.stdout.on('data', function(data) {
    PHPOutput += data.toString('utf-8');
  });

  php.on('close', function() {

    var responseBody = {
      message: "Hello " + name + "!",
      php: PHPOutput,
      input: event
    };

    var response = {
      statusCode: responseCode,
      headers: {
        "x-custom-header" : "my custom header value"
      },
      body: JSON.stringify(responseBody)
    };

    console.log("response: " + JSON.stringify(response))
    context.succeed(response);

  });



};
