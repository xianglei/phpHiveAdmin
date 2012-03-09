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
	if(file_exists($env['output_path']."/hive_res.".$str.".out") && filesize("/tmp/hive_res.".$str.".out") != 0)
	{
		$fp = fopen($env['output_path']."/hive_res.".$str.".out","r");
		$i = 0;
		while($i != 30)
		{
			$string .= fgets($fp,4096);
			$i++;
		}
		$array = explode("\n",substr($string,0,-1));//stop at last return
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
			$arr = explode('	',$v);
			echo "<tr ".$color.">\n";
			foreach($arr as $kk=>$vv)
			{
				$vv = str_replace('<','&lt;',$vv);
				$vv = str_replace('>','&gt;',$vv);
				echo "<td>".$vv."</td>\n";
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