<?php
include_once("config.inc.php");
include_once("templates/style.css");
if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_GET['database']);

	$client->execute($env['showTables']);
	$db_array = $client->fetchAll();
	$db_array = array_reverse($db_array);

	$i = 0;
	echo '<a href="javascript:showsd(\'dbList.php\',\'index.php?frame=right\')" target=left><<< '.$lang['back'].'</a><br /><br />';
	while('' != @$db_array[$i])
	{
		echo '<a href=sqlQuery.php?table='.$db_array[$i].'&database='.$_GET['database'].' target="right"><img src=images/b_sbrowse.png>'.$db_array[$i].'</a><br />';
		$i++;
	}
	echo "<br>\n";
	echo '<a href="javascript:showsd(\'dbList.php\',\'index.php?frame=right\')" target=left><<< '.$lang['back'].'</a>';
	$transport->close();
}
?>