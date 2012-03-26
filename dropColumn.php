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
		$client->execute('desc '.$_GET['table']);
		$array_desc_table = $client->fetchAll();
		$i = 0;
		while ('' != @$array_desc_table[$i])
		{
			$array_desc = explode('	',$array_desc_table[$i]);
			$array_desc_desc[$i]['name'] = $array_desc[0];
			$array_desc_desc[$i]['type'] = $array_desc[1];
			$array_desc_desc[$i]['comment'] = $array_desc[2];
			if($array_desc_desc[$i]['name'] == $_GET['column'])
			{
				unset($array_desc_desc[$i]);
			}
			$i++;
		}
		#-----------construct sql---------
		$sql = "ALTER TABLE `".$_GET['database']."`.`".$_GET['table']."` REPLACE COLUMNS ( ";
		foreach($array_desc_desc as $k => $v):
			foreach($v as $kk => $vv):
				if($kk == 'name')
					$name = $vv;
				if($kk == 'type')
					$type = $vv;
				if($kk == 'comment')
					$comment = $vv;
				$tmp .= "`".$type."` ".$type." COMMENT '".$comment."',";
			endforeach;
		endforeach;
		#-----------
		$sql = $sql.substr($tmp,0,-1).")";
		echo $sql;
	}
}

$transport->close();
?>