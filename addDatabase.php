<?php
include_once 'config.inc.php';
include_once 'templates/style.css';
if(!@$_POST['newdbname'])
{
	die('Invalid entrance');
}
else
{
	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('create database '.$_POST['newdbname']);

	//$db_array = $client->fetchOne();
	echo $lang['createDbSuccess'].' - <a href=index.php?frame=right>'.$lang['back'].'</a>';

	$transport->close();
}
?>