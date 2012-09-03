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
	echo '<br/>';
	echo "<table class=\"table table-border table-hover\">";
	echo "<thead><tr>";
	echo '<a href="javascript:showsd(\'dbList.php\',\'index.php?frame=right\')" target=left><i class="icon-backward"></i> '.$lang['back'].'</a>';
	echo "</tr></thead>";
	while('' != @$db_array[$i])
	{
		echo "<tbody><tr><td>";
		echo '<a href=sqlQuery.php?table='.$db_array[$i].'&database='.$_GET['database'].' target="right"><i class="icon-th-list"></i>'.$db_array[$i].'</a>';
		echo "</td></tr></tbody>";
		$i++;
	}
	echo "<tfoot><tr>";
	echo '<a href="javascript:showsd(\'dbList.php\',\'index.php?frame=right\')" target=left><i class="icon-backward"></i> '.$lang['back'].'</a>';
	echo "</tr></tfoot>";
	echo "</table>";
	$transport->close();
}
?>