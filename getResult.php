<?php
if(!@$_GET['str'])
{
	die('Invalid entry');
}
else
{
	$str = @$_GET['str'];
	if(file_exists("/tmp/hive_res.".$str.".out"))
	{
		$fp = fopen("/tmp/hive_res.".$str.".out","r");
		$i = 0;
		while(!feof($fp) && $i != 10)
		{
			$string .= fread($fp,10240);
			$i++;
		}
		$array = explode("\n",$string);
		foreach($array as $k=>$v)
		{
			echo $v."<br>";
		}
	}
	else
	{
		echo "Not yet";
	}
}
?>