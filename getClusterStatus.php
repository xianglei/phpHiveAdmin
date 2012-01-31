<?php

include_once 'config.inc.php';

$transport->open();

$client->execute('add jar /opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar');

$status = $client->getClusterStatus();
var_dump($status);



$transport->close();

?>
