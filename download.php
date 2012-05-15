<?php
if(!@$_GET['str'])
{
	die('invalid url');
}
else
{
	include_once "config.inc.php";
	//$env['output_path'] = '/tmp/phpHiveAdmin';
	$filename = $env['output_path']."/hive_res.".$_GET['str'].".csv";
	if(file_exists($filename))
	{
		/*
		$fp = fopen($filename,"r");
		$fd = fopen($env['download_path']."/hive_res.".$_GET['str'].".out","w");
		while(!feof($fp))
		{
			$str = fgets($fp,1024);
			$str = str_replace("	","\t",$str);
			fputs($fd,$str);
		}
		fclose($fp);
		fclose($fd);
		*/
		
		//unlink($filename);
		
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header('Content-Length: ' . filesize($filename));
		header("Content-Disposition: attachment; filename=".$filename);
		//readfile($filename);
		$fp = fopen ($filename,"r");
		while (!feof($fp))
		{
			echo $str = fgets($fp,4096);
		}
		fclose($fp);
		//unlink($filename);
	}
	else
	{
		die($lang['invalidFilename']);
	}
}
?>