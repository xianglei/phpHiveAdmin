<?php
include_once 'config.inc.php';
include_once 'templates/style.css';


if(!@$_POST['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_POST['database']);
	
	if(!@$_POST['table_name'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		if(is_array($_POST['table_name']))
		{
			foreach($_POST['table_name'] as $k => $v):
				$sql = "drop table ".$v;
				$client->execute($sql);
			endforeach;
			echo "<script>alert('".$lang['dropTableSuccess']."');showsd('tableList.php?database=".$_POST['database']."','dbStructure.php?database=".$_POST['database']."');</script>";
		}
		else
		{
			die('Not valid table names');
		}
	}
	$transport->close();
}