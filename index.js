'use strict';

console.log('Loading hello world php function');

var spawn   = require('child_process').spawn;
var parser  = require('http-string-parser');

exports.handler = function(event, context) {

    var PHPOutput = '';

    console.log("request: " + JSON.stringify(event));

    // Sets some sane defaults here so that this function doesn't fail when it's not handling a HTTP request from
    // API Gateway.
    var requestMethod = event.httpMethod || 'GET';
    var serverName    = event.headers ? event.headers.Host : '';
    var requestUri    = event.path || '';
    var headers       = {};
    var httpObject    = {};

    // Convert all headers passed by API Gateway into the correct format for PHP CGI. This means converting a header
    // such as "X-Test" into "HTTP_X-TEST".
    if (event.headers) {
        Object.keys(event.headers).map(function (key) {
            headers['HTTP_' + key.toUpperCase()] = event.headers[key];
        });
    }

    // Create HTTP object
    httpObject = Object.assign({
        REDIRECT_STATUS: 200,
        REQUEST_METHOD: requestMethod,
        SCRIPT_FILENAME: 'src/public/index.php',
        SCRIPT_NAME: '/src/public/index.php',
        PATH_INFO: '/',
        SERVER_NAME: serverName,
        SERVER_PROTOCOL: 'HTTP/1.1',
        REQUEST_URI: requestUri,
        AWS_REGION: process.env.AWS_REGION,
        AWS_ACCESS_KEY_ID: process.env.AWS_ACCESS_KEY_ID,
        AWS_SECRET_ACCESS_KEY: process.env.AWS_SECRET_ACCESS_KEY,
        AWS_SESSION_TOKEN: process.env.AWS_SESSION_TOKEN,
        EVENT_PARAMS: JSON.stringify(event)
    }, headers);

    function serialize( obj ) {
        return Object.keys(obj).reduce(function(a,k){a.push(k+'='+encodeURIComponent(obj[k]));return a},[]).join('&')
    }

    if ( event.queryStringParameters && event.queryStringParameters !== null ) {
        httpObject.QUERY_STRING = serialize(event.queryStringParameters);
    }

    // Spawn the PHP CGI process with a bunch of environment variables that describe the request.
    var php = spawn('./bin/php-cgi', ['-dextension=bin/phalcon.so','src/public/index.php'], {
        env: httpObject
    });

    if (event.body !== null && event.body !== undefined) {
        console.log("Body: " + JSON.stringify(event.body) );
        //send the input event json as string via STDIN to php process
        php.stdin.write(event.body);
        php.stdin.end(); // Close the php stream to unblock php process
    } else {
        console.log("Empty body");
        php.stdin.write('');
        php.stdin.end(); // Close the php stream to unblock php process
    }

    php.stdout.on('data', function(data) {
        PHPOutput += data.toString('utf-8');
    });

    //react to potential errors
    php.stderr.on('data', function(data) {
        PHPOutput += data.toString('utf-8');
    });

    php.on('close', function() {

        // Parses a raw HTTP response into an object that we can manipulate into the required format.
        var parsedPHPOutput = parser.parseResponse(PHPOutput);

        var response = {
            statusCode: parsedPHPOutput.statusCode || 200,
            headers: parsedPHPOutput.headers,
            body: parsedPHPOutput.body
        };

        console.log("response: " + JSON.stringify(response));
        context.succeed(response);

    });

};
