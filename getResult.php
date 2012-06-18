<?php
include_once 'config.inc.php';
include_once 'templates/style.css';

if(!@$_GET['str'])
{
	die($lang['invalidEntry']);
}
else
{
	$str = @$_GET['str'];
	$filename = $env['output_path']."/hive_res.".$str.".csv";
	if($_SESSION != 'all')
	{
		$logfile = $env['logs_path'].$_SESSION['username']."_".$str.".log";
	}
	else
	{
		if(@$_GET['filename'] != "")
		{
			$logfile = $env['logs_path'].$_GET['filename'];
		}
		else
		{
			die($lang['invalidEntry']);
		}
	}
	if(file_exists($filename))
	{
		if(filesize($filename) != 0)
		{
			echo "<input type=button name=download value=\"".$lang['downloadResultFile']."\" onclick=\"window.open('download.php?str=".$str."');\"><br><br>";
			
			$etc = new Etc;
			
			$array_column = $etc->SplitSqlColumn($logfile);
			
			$array = $etc->GetResult($filename);
			$array = explode("\n",substr($array,0,-1));//stop at last return
			$i = 0;
			echo "<table border=1 cellspacing=1 cellpadding=3>\n";
			echo "<tr bgcolor=#FFFF99>";
			foreach($array_column as $ka => $va)
			{
				echo "<td>";
				echo $va;
				echo "</td>";
			}
			echo "</tr>";
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
				#$arr = explode('	',$v);
				$arr = explode(",",$v);
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
			echo $lang['noResultFetched'];
		}
	}
	else
	{
		echo $lang['notReadyYet'];
	}
}
?>