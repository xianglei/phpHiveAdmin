<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport->open();

$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo '<br />';
echo array_keys($_SERVER['REMOTE_ADDR'],$fb);
while('' != @$db_array[$i]) {
	echo '<a href="javascript:showsd(\'dbStructure.php?database='.$db_array[$i].'\', \'tableList.php?database='.$db_array[$i].'\')" target="left"><img src=images/database.png>'.$db_array[$i].'</a><br />'."\n";
	$i++;
}
$transport->close();
?>