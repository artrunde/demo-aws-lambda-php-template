var spawn = require('child_process').spawn;

exports.handler = function(event, context) {
	
    var php = spawn('./php-cgi', ['-v']);
    var output = '';
	
    php.stdout.on('data', function(data) {
        output += data.toString('utf-8');
    });

    php.on('close', function() {
        context.succeed(output);
    });
	
};
