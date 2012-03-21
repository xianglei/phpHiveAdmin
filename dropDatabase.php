<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_GET['database']);
	$sql = "drop database ".$_GET['database'];
	$client->execute($sql);
	echo "<script>alert('".$lang['dropDbSuccess']."');window.location='index.php?frame=right'</script>";
	$transport->close();
}