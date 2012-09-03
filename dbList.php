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
echo '<table class="table table-hover">';
if($_SESSION['role'] == "superadmin")
{
	while('' != @$db_array[$i]) {
		echo "<tr>";
		echo '<td><a href="javascript:showsd(\'tableList.php?database='.$db_array[$i].'\',\'dbStructure.php?database='.$db_array[$i].'\')" target="left"><i class="icon-zoom-in"></i>'.$db_array[$i].'</a></td>'."\n";
		echo "</tr>";
		$i++;
	}
}
else
{
	$onlydb = explode(",",$_SESSION['onlydb']);
	while('' != @$db_array[$i]) {
		if(in_array($db_array[$i],$onlydb))
		{
			echo "<tr>";
			echo '<td><a href="javascript:showsd(\'tableList.php?database='.$db_array[$i].'\', \'dbStructure.php?database='.$db_array[$i].'\')" target="left"><i class="icon-zoom-in"></i>'.$db_array[$i].'</a></td>'."\n";
			echo "</tr>";
		}
		$i++;
	}
}
echo "</table>";
$transport->close();

?>