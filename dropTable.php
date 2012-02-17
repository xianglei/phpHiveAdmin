<?php
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);
	
	if(!@$_GET['table'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		$sql = "drop table ".$_GET['table'];
		$client->execute($sql);
		echo "<script>alert('".$lang['dropTableSuccess']."');window.location='dbStructure.php?database=".$_GET['database']."'</script>";
	}
	$transport->close();
}