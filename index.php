<?php
$inputJSON = file_get_contents('php://stdin');

header('Content-Type: text/html; charset=UTF-8');

$inputJSON = print_r(json_decode($inputJSON), true);

echo "<html><head><title>Lambda webserver</title></head><body><div>Hello World. <br/> You have initiated this with {$_SERVER['REQUEST_URI']} and this is your input: <pre>{$inputJSON}</pre> </div></body></html>";