<?php
include_once "config.inc.php";
include "templates/style.css";

if(!@$_GET['str'])
{
	die("Invalid Entry");
}
else
{
	$str = $_GET['str'];
	if(file_exists($env['output_path']."/hive_run.".$str.".out"))
	{
		echo "<meta http-equiv=\"refresh\" content=\"2\">";
		$array = @file($env['output_path']."/hive_run.".$str.".out");
		$array = array_reverse($array);
		foreach($array as $k=>$v)
		{
			echo nl2br($v);
		}
	}
	else
	{
		echo "<meta http-equiv=\"refresh\" content=\"2\">";
		echo "Map/Reduce is Running...plz wait a second";
	}
}
?>