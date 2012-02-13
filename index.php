<?php

//include_once 'config.inc.php';
include_once 'langs/lang_en.php';

if(@$_GET['frame'])
{
	include_once 'templates/'.$_GET['frame'].'.html';
}
else
{
	include_once 'templates/index.html';
}
/*
$transport->open();

$client->execute('add jar /opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar');
$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
while('' != @$db_array[$i]) {
	echo '<a href=sql_query.php?database='.$db_array[$i].'>'.$db_array[$i].'</a><br />';
	$i++;
}

$transport->close();
*/
?>
