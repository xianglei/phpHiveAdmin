<meta http-equiv="refresh" content="2">
<?php
$env['output_path'] = './results';

include "templates/style.css";
if(!@$_GET['str'])
{
	die($lang['invalidEntry']);
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
		echo $lang['runningMapReduce'];
	}
}
?>