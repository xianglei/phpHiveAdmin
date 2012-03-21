<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

$transport = new TSocket(HOST, PORT);
$protocol = new TBinaryProtocol($transport);
$client = new ThriftHiveClient($protocol);

$transport->open();

$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
echo '<br />';
if($_SESSION['onlydb'] == "all")
{
	while('' != @$db_array[$i]) {
		echo '<a href="javascript:showsd(\'dbStructure.php?database='.$db_array[$i].'\', \'tableList.php?database='.$db_array[$i].'\')" target="left"><img src=images/database.png>'.$db_array[$i].'</a><br />'."\n";
		$i++;
	}
}
else
{
	$onlydb = explode(",",$_SESSION['onlydb']);
	while('' != @$db_array[$i]) {
		if(in_array($db_array[$i],$onlydb))
		{
			echo '<a href="javascript:showsd(\'dbStructure.php?database='.$db_array[$i].'\', \'tableList.php?database='.$db_array[$i].'\')" target="left"><img src=images/database.png>'.$db_array[$i].'</a><br />'."\n";
		}
		$i++;
	}
}
$transport->close();
?>