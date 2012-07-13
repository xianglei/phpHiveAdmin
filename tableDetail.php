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
		var_dump($array_desc_table);
		
		###############################################################################################################
		
		$array_desc_table_1 = $etc->GetTableDetail($array_desc_table, "1");
		
		#var_dump($array_desc_table);
		
		$i = 0;
		while ('' != @$array_desc_table_1[$i])
		{
			$array_desc = explode('	',$array_desc_table_1[$i]);
			$array_desc_desc['name'][$i] = $array_desc[0];
			$array_desc_desc['type'][$i] = $array_desc[1];
			$array_desc_desc['comment'][$i] = $array_desc[2];
			$i++;
		}
		echo "<table border=1 cellspacing=1 cellpadding=3>";
		echo "<tr bgcolor=#FFFF99><td> Column Name </td><td> Column Type </td><td> Comment </td></tr>";
		$i = 0;
		foreach ($array_desc_table_1 as $k => $v)
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
			echo "<td>".$array_desc_desc['name'][$i]."</td>";
			echo "<td>".$array_desc_desc['type'][$i]."</td>";
			echo "<td>".$array_desc_desc['comment'][$i]."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		
		echo "<br>";
		
		#####################################################################################################
		
		$array_desc_table_2 = $etc->GetTableDetail($array_desc_table, "2");
		
		$i = 0;
		while ('' != @$array_desc_table_2[$i])
		{
			$array_desc = explode("	",$array_desc_table_2[$i]);
			$array_desc_desc['name'][$i] = trim($array_desc[0]);
			$array_desc_desc['type'][$i] = trim($array_desc[1]);
			$i++;
		}
		
		echo "<table border=1 cellspacing=1 cellpadding=3>";
		echo "<tr bgcolor=#FFFF99><td> Detailed Name </td><td> Detailed Value </td></tr>";
		$i = 0;
		foreach ($array_desc_table_2 as $k => $v)
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
			echo "<td>".$array_desc_desc['name'][$i]."</td>";
			echo "<td>".$array_desc_desc['type'][$i]."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		echo "<br>"; 
		
		#####################################################################################################
		
		$array_desc_table_3 = $etc->GetTableDetail($array_desc_table, "3");
		
		$i = 0;
		while ('' != @$array_desc_table_3[$i])
		{
			$array_desc = explode("	",$array_desc_table_3[$i]);
			$array_desc_desc['name'][$i] = trim($array_desc[0]);
			$array_desc_desc['type'][$i] = trim($array_desc[1]);
			$i++;
		}
		
		echo "<table border=1 cellspacing=1 cellpadding=3>";
		echo "<tr bgcolor=#FFFF99><td> Storage Name </td><td> Storage Type </td></tr>";
		$i = 0;
		foreach ($array_desc_table_3 as $k => $v)
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
			echo "<td>".$array_desc_desc['name'][$i]."</td>";
			echo "<td>".$array_desc_desc['type'][$i]."</td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>";
		
	}
}
?>