<?php
if(!@$_GET['str'])
{
	die('invalid url');
}
else
{
	include_once "config.inc.php";

	$filename = $env['output_path']."/hive_res.".$_GET['str'].".csv";
	$fname = "hive_res.".$_GET['str'].".csv";
	if(file_exists($filename))
	{		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header('Content-Length: ' . filesize($filename));
		header("Content-Disposition: attachment; filename=".$fname);

		$fp = fopen ($filename,"r");
		while (!feof($fp))
		{
			echo $str = fgets($fp,4096);
		}
		fclose($fp);

	}
	else
	{
		die($lang['invalidFilename']);
	}
}
?>