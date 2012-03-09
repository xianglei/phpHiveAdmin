<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['str'])
{
	die('Invalid entry');
}
else
{
	$str = @$_GET['str'];
	if(file_exists("/tmp/hive_res.".$str.".out") || filesize("/tmp/hive_res.".$str.".out") != 0)
	{
		$fp = fopen("/tmp/hive_res.".$str.".out","r");
		$i = 0;
		while($i != 30)
		{
			$string .= fgets($fp,4096);
			$i++;
		}
		$array = explode("\n",$string);
		$i = 0;
		echo "<table border=1 cellspacing=1 cellpadding=3>\n";
		foreach($array as $k=>$v)
		{
			if(($i % 2) == 0)
			{
				$color = "bgcolor=\"".$env['trColor1']."\"";
			}
			else
			{
				$color = "bgcolor=\"".$env['trColor2']."\"";
			}
			echo "<tr ".$color.">\n";
			$arr = explode("        ",$v);
			
			foreach($arr as $kk=>$vv)
			{
				$value = str_replace('<','&lt;',$value);
				$value = str_replace('>','&gt;',$value);
				echo "<td>".$v."</td>\n";
			}
			echo "</tr>\n";
			$i++;
		}
		echo "</table>\n";
	}
	else
	{
		echo "Not yet";
	}
}
?>