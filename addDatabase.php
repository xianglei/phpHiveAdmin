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

	$sql = "CREATE DATABASE IF NOT EXISTS ".$_POST['newdbname']." COMMENT '".$_POST['dbcomment']."'";
	$client->execute($sql);
	echo $sql;
	//$db_array = $client->fetchOne();
	echo '<script>alert(\''.$lang['createDbSuccess'].'\');window.location=\'index.php?frame=right\';</script>';
	$transport->close();
}
?>