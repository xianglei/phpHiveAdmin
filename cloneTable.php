<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	if(!@$_GET['table'])
	{
		echo "<script>window.location=dbStructure.php?database=".$_GET['database']."</script>";
	}
	else
	{
		$transport = new TSocket(HOST, PORT);
		$protocol = new TBinaryProtocol($transport);
		$client = new ThriftHiveClient($protocol);

		$transport->open();

		$client->execute('use '.$_GET['database']);
	
	 	if(!@$_POST['newtablename'])
	 	{
			include_once "templates/clone_table.html";
	 	}
	 	else
	 	{
	 		$sql = 'CREATE TABLE '.$_POST['newtablename'].' LIKE '.$_POST['table'];
			$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
	 	}
	}
}