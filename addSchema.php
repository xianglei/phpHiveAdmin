<?php
include_once 'config.inc.php';
include_once 'templates/style.css';
if(!@$_POST['newdbname'] || @$_POST['newdbname'] == "")
{
	die($lang['invalidEntry']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$sql = "CREATE SCHEMA IF NOT EXISTS ".$_POST['newschemaname']." COMMENT '".$_POST['newschemacomment']."'";
	$client->execute($sql);
	echo $sql;
	//$db_array = $client->fetchOne();
	echo '<script>alert(\''.$lang['createSchemaSuccess'].'\');window.location=\'index.php?frame=right\';</script>';
	$transport->close();
}
?>