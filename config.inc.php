<?php

$GLOBALS['THRIFT_ROOT'] = './libs/';
// load the required files for connecting to Hive
require_once $GLOBALS['THRIFT_ROOT'] . 'packages/hive_service/ThriftHive.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'] . 'protocol/TBinaryProtocol.php';
// Set up the transport/protocol/client

define('HOST','192.168.1.49');
define('PORT','10000');

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);
//Create ThriftHive object

?>
