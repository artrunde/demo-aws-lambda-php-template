<?php

$data = stream_get_contents(STDIN);

$json = json_decode($data, true);

$result = json_encode($json, JSON_PRETTY_PRINT);

echo $result."\n";