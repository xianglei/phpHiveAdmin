<?php

include_once 'config.inc.php';

$transport->open();

$client->execute('add jar '.$env['hive_jar']);

$status = $client->getClusterStatus();
var_dump($status);

$transport->close();

?>
