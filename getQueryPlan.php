<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$hql = $_GET['sql'];
$hql = "EXPLAIN EXTENDED ".$hql;

echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";
$res = $client->execute($hql);
echo $res;
echo "<center><input type=button value=\"Close Window\" onclick='window.close()'></center>";

//echo 'Field Schemas: '.$status->fieldSchemas.'<br />';
//echo 'Properties: '.$status->properties.'<br />';

$transport->close();

?>
