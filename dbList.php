<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport->open();

$client->execute('add jar '.$env['hive_jar']);
$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo "<body bgcolor=\"#EFEFEF\">";
echo '<br />';
while('' != @$db_array[$i]) {
	echo '<a href=tableList.php?database='.$db_array[$i].' target="left">'.$db_array[$i].'</a><br />';
	$i++;
}
$transport->close();
?>