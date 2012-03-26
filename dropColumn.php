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
			if($array_desc[0] == $_GET['column'])
			{
				unset($array_desc[0]);
			}
			$array_desc_desc[$i] = $array_desc[0];
			$i++;
		}var_dump($array_desc_desc);
	}
}

$transport->close();
?>