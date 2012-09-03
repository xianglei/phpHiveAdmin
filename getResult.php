<?php
include_once 'config.inc.php';
include_once 'templates/style.css';
if(!@$_GET['str'])
{
	die($lang['invalidEntry']);
}
else
{
	echo '<div class="container">';
	echo '<div class="span10">';
	if(!$_SESSION['onlydb'] || $_SESSION['onlydb'] == "")
	{
		die("<script>parent.location='index.php'</script>");
	}
	else
	{
		if($_SESSION['onlydb'] != "all")
		{
			$str = @$_GET['str'];
		}
		else
		{
			if(!is_numeric(substr($_GET['str'],0,1)))
			{
				$str = explode("_",$_GET['str']);
				$str = substr($str[1]."_".$str[2],0,-4);
			}
			else
			{
				$str = $_GET['str'];
			}
		}

		$filename = $env['output_path']."/hive_res.".$str.".csv";
		if($_SESSION['role'] != "superadmin")
		{
			$logfile = $env['logs_path'].$_SESSION['username']."_".$str.".log";
		}
		else
		{
			if(is_numeric(substr($_GET['str'],0,1)))
			{
				$logfile = $env['logs_path'].$_SESSION['username']."_".$_GET['str'].".log";
			}
			else
			{
				$logfile = $env['logs_path'].$_GET['str'];
			}		
		}
	
		if(file_exists($filename))
		{
			if(filesize($filename) != 0)
			{
				echo "<input type=button class=\"btn btn-success\" name=download value=\"".$lang['downloadResultFile']."\" onclick=\"window.open('download.php?str=".$str."');\"><br><br>";

				$etc = new Etc;

				$array_column = $etc->SplitSqlColumn($logfile);
			
				$array = $etc->GetResult($filename);
				$array = explode("\n",substr($array,0,-1));//stop at last return
				$i = 0;
				echo "<table class=\"table table-striped table-hover\">\n";
				echo "<tr class=\"info\">";
				foreach($array_column as $ka => $va)
				{
					echo "<td>";
					echo $va;
					echo "</td>";
				}
				echo "</tr>";
				foreach($array as $k=>$v)
				{
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
echo "</div></div>";
}
?>