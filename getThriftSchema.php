<?php


include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$client->execute('add jar '.$env['hive_jar']);

$status = $client->getThriftSchema();
//var_dump($status);
echo "<<< <a href=index.php?frame=right>".$lang['back']."</a><br><br>";
echo 'Field Schemas: '.$status->fieldSchemas.'<br />';
echo 'Properties: '.$status->properties.'<br />';

$transport->close();

?>
