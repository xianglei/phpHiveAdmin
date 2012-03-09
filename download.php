<?php
if(!@$_GET['str'])
{
	die('invalid url');
}
else
{
	//include_once "config.inc.php";
	$env['output_path'] = '/tmp/phpHiveAdmin';
	$filename = $env['output_path']."/hive_res.".$_GET['str'].".out";
	if(file_exists($filename))
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$filename);
		readfile($filename);

		unlink($filename);
	}
	else
	{
		die('Invalid Filename');
	}
}
?>