'use strict';

console.log('Loading hello world php function');

var spawn = require('child_process').spawn;

exports.handler = function(event, context) {

  var name = "World";
  var responseCode = 200;
  var PHPOutput = '';

  console.log("request: " + JSON.stringify(event));

  var php = spawn('./bin/php-cgi', ['-v']);

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
