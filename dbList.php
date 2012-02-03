<?php
include_once 'config.inc.php';

$transport->open();

$client->execute('add jar '.$env['hive_jar']);
$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo "<br /><br />"
while('' != @$db_array[$i]) {
	echo '<a href=tableList.php?database='.$db_array[$i].' target="right">'.$db_array[$i].'</a><br />';
	$i++;
}
$transport->close();
?>