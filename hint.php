<?php
include_once 'config.inc.php';

if(!$_GET['database'])
{
	$file = "js/hiveudfs.txt";
	$array = file($file);
}
else
{
	if(!$_GET['table'])
	{
		$file = "js/hiveudfs.txt";
		$array = file($file);
	}
	else
	{
		$transport = new TSocket(HOST, PORT);
		$protocol = new TBinaryProtocol($transport);
		$client = new ThriftHiveClient($protocol);

		$transport->open();
		$client->execute('use '.$_GET['database']);
		$sql = "desc ".$_GET['table'];
		$client->execute($sql);
		$array_desc_table = $client->fetchAll();
		
		$i = 0;
		while ('' != @$array_desc_table[$i])
		{
			$array_desc = explode('	',$array_desc_table[$i]);
			$array_desc_desc[$i] = $array_desc[0];
			$i++;
		}
		
		$array_table = array($_GET['table']);
		$file = "js/hiveudfs.txt";
		$array = file($file);
		$array = array_merge($array,$array_desc_desc);
		$array = array_merge($array,$array_table);
	}
}
foreach ($array as $key => $value)
{
	$str = '"'.$value.'",';
	echo substr($str,0,-1);
}
?>