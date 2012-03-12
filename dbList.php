<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport->open();

$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo '<br />';
$a = array("mobile" => array("ip" => "1","only" => "2"));
echo array_search("2",$a);
while('' != @$db_array[$i]) {
	echo '<a href="javascript:showsd(\'dbStructure.php?database='.$db_array[$i].'\', \'tableList.php?database='.$db_array[$i].'\')" target="left"><img src=images/database.png>'.$db_array[$i].'</a><br />'."\n";
	$i++;
}
$transport->close();
?>