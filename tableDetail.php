<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

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

	//$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);

	if(!@$_GET['table'])
	{
		die ($lang['dieTableChoose']);
	}
	else
	{
		$sql = "desc formatted ".$_GET['table'];
		$etc = new Etc;
		
		$client->execute($sql);
		$array_desc_table = $client->fetchAll();
		$array_desc_table = $etc->GetTableDetail($array_desc_table, "1");
		
		var_dump($array_desc_table);
		echo "<table border=1 cellspacing=1 cellpadding=3>";
		echo "<tr bgcolor=#FFFF99><td> Column Name </td><td></td></tr>";
		$i = 0;
		foreach ($array_desc_table as $k => $v)
		{
			if(($i % 2) == 0)
			{
				$color = "bgcolor=\"".$env['trColor1']."\"";
			}
			else
			{
				$color = "bgcolor=\"".$env['trColor2']."\"";
			}
			echo "<tr ".$color.">\n";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
	}
}
?>