process.env['PATH'] = process.env['PATH'] + ':' + process.env['LAMBDA_TASK_ROOT'];

var spawn = require('child_process').spawn;

exports.handler = function(event, context) {

  var php = spawn('./bin/php-cgi', ['-v']);

  var output = '';

  php.stdout.on('data', function(data) {
    output += data.toString('utf-8');
  });

  php.on('close', function() {
    context.succeed(output);
  });

};