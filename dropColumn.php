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

	$client->execute('use '.$_GET['database']);
	
	if(!@$_GET['table'])
	{
		echo "<script>window.location=dbStructure.php?database=".$_GET['database']."</script>";
	}
	else
	{
		echo "<a href=dbStructure.php?database=".$_GET['database'].">".$lang['back']."</a><br><br>";
		$client->execute('desc formatted '.$_GET['table']);
		$array_desc_table = $client->fetchAll();
		
		$etc = new Etc;
			
		$array_desc_table_1 = $etc->GetTableDetail($array_desc_table, "1");
		
		$i = 0;
		while ('' != @$array_desc_table_1[$i])
		{
			$array_desc_1 = explode('	',$array_desc_table_1[$i]);
			$array_desc_desc_1[$i]['name'] = trim($array_desc_1[0]);
			$array_desc_desc_1[$i]['type'] = trim($array_desc_1[1]);
			$array_desc_desc_1[$i]['comment'] = trim($array_desc_1[2]);
			if($array_desc_desc_1[$i]['name'] == $_GET['column'])
			{
				unset($array_desc_desc_1[$i]);
			}
			$i++;
		}
		#-----------construct sql---------
		$sql = "ALTER TABLE `".$_GET['table']."` REPLACE COLUMNS ( ";
		foreach($array_desc_desc_1 as $k => $v):
			$tmp .= "`".$v['name']."` ".$v['type']." COMMENT '".$v['comment']."',";
		endforeach;
		#-----------
		$sql = $sql.substr($tmp,0,-1).")";
		echo $sql;
		$client->execute($sql);
		echo "<script>alert('".$lang['success']."');window.location='sqlQuery.php?database=".$_GET['database']."&table=".$_GET['table']."';</script>";
	}
}

$transport->close();
?>