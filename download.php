<?php
include_once 'config.inc.php';
//include_once 'templates/style.css';
if(!@$_GET['filename'])
{
	die('invalid url');
}

if(file_exists(@$_GET['filename']))
{
	$filename = explode('/',$_GET['filename']);
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename[1]);
	readfile($_GET['filename']);

	//unlink($_GET['filename']);
}
else
{
	die('Invalid Filename');
}
?>