<?php
include_once 'config.inc.php';
include_once 'templates/style.css';
if(!@$_POST['newdbname'] || @$_POST['newdbname'] == "")
{
	die($lang['invalidEntry']);
}
else
{
	$hive = new Hive();

	$sql = "CREATE DATABASE IF NOT EXISTS ".$_POST['newdbname']." COMMENT '".$_POST['dbcomment']."'";
	$hive->Execute($sql);
	echo $sql;
	//$db_array = $client->fetchOne();
	echo '<script>alert(\''.$lang['createDbSuccess'].'\');window.location=\'index.php?frame=right\';</script>';
	$hive->__destruct();
}
?>