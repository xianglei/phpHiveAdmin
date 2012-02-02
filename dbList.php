<?
include_once 'config.inc.php';

$transport->open();

$client->execute('add jar /opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar');
$client->execute('show databases');

$db_array = $client->fetchAll();

$i = 0;
while('' != @$db_array[$i]) {
	echo '<a href=sql_query.php?database='.$db_array[$i].' target="right">'.$db_array[$i].'</a><br />';
	$i++;
}

$transport->close();
?>