<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['schema'])
{
	die($lang['dieSchemaChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_GET['schema']);
	$sql = "DROP SCHEMA IF EXISTS ".$_GET['schema'];
	$client->execute($sql);
	echo "<script>alert('".$lang['dropSchemaSuccess']."');window.location='index.php?frame=right'</script>";
	$transport->close();
}