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

	$client->execute('use '.$_GET['database']);
	$sql = "drop database ".$_GET['database'];
	$client->execute($sql);
	echo "<script>alert('".$lang['dropDbSuccess']."');window.location='index.php?frame=right'</script>";
	$transport->close();
}