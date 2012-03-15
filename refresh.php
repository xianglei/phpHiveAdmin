<meta http-equiv="refresh" content="2">
<?php
$env['output_path'] = '/data2/tmp/phpHiveAdmin';
//include_once "config.inc.php";
//echo "<meta http-equiv=\"refresh\" content=\"2\">";
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
		$array = @file($env['output_path']."/hive_run.".$str.".out");
		$array = array_reverse($array);
		foreach($array as $k=>$v)
		{
			echo nl2br($v);
		}
	}
	else
	{
		echo "Map/Reduce is Running...plz wait a second";
	}
}
?>