<?php

include_once 'config.inc.php';
include_once 'templates/style.css';

$transport->open();
echo "<body bgcolor=\"".$env['bodyColor']."\">";

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);
	if(!@$_GET['table'])
	{
		die ($lang['dieTableChoose']);
	}
	else
	{
		$date = explode("_",$_GET['table']);
		$date = $date[1];
		$dbDir = $env['hdfsToHiveDir']."/".$_GET['database']."/".$date;
		echo $sql = "dfs -ls ".$dbDir;
		$client->execute($sql);
		$flist = $client->fetchAll();
		foreach($flist as $k => $v)
		{
			echo $v."<br>";
		}
	}
}

$transport->close();

?>
