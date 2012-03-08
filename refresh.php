<meta http-equiv="refresh" content="2">
<?php
include "templates/style.css";

if(!@$_GET['str'])
{
	die("Invalid Entry");
}
else
{
	$str = $_GET['str'];
	$fp = @fopen("/tmp/hive_run.".$str.".out","r");
	while(!@feof($fp))
	{
		$str .= fread($fp,128);
	}
	echo nl2br($str);
	fclose($fp);
}
?>