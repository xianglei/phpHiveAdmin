<?php

include_once 'config.inc.php';
include_once 'templates/style.css';


	//echo str_replace("/","\/",$_GET['dir']);
/*if(preg_match('/'.str_replace("/","\/",$_GET['dir']).'/i',$env['hdfsToHiveDir']) == '0')
{
	$dir = $_GET['dir'];
}
else
{
	$dir = $env['hdfsToHiveDir'];
}


//if (is_dir($dir)) {

if ($dh = opendir($dir))
{
	echo "<a href=fileBrowser.php?dir=".$env['hdfsToHiveDir'].">Back to Root</a><br><br>";
	echo "<table border=1 cellspacing=1 cellpadding=3>";
	echo "<tr bgcolor=\"#FFFF99\">";
	echo "<td>".$lang['filename']."</td><td>".$lang['filetype']."</td><td>".$lang['filesize']."</td>";
	echo "</tr>";
	$i = 0;
	while (($file = readdir($dh)) !== false)
	{
		if(($i % 2) == 0)
		{
			$color = $env['trColor1'];
		}
		else
		{
			$color = $env['trColor2'];
		}
		echo "<tr bgcolor=\"".$color."\">";
		if(($file == '.') || ($file == '..'))
		{
			continue;
		}
		else
		{
			if(is_dir($dir.$file))
			{
				echo "<td><a href=fileBrowser.php?dir=".$dir.$file."/>$file</a></td>\n";
			}
			else
			{
				echo "<td><a href=fileBrowser.php?dir=".$dir.$file.">$file</a></td>\n";
			}
			echo "<td>".filetype($dir.$file)."</td>\n";
			echo "<td>".filesize($dir.$file)."</td>\n";
		}
		echo "</tr>";
		$i++;
	}
	closedir($dh);
	echo "</table>";
	echo "Files: ".$i;
}

//}
*/

if(!@$_GET['path'])
{
	$path = "/";
}
else
{
	$path = $_GET['path'];
}
$etc = new Etc;
$sql = '"dfs -ls '.$path.'"';
if($env['setenv'] == 'export')
{
	$exec = ('export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql);
}
else
{
	$exec = ('setenv HADOOP_HOME '.$env['hadoop_home'].' && setenv HIVE_HOME '.$env['hive_home'].' && setenv JAVA_HOME '.$env['java_home'].' && '.$env['hive_home'].'/bin/hive -e '.$sql);
}
$time = time();
$etc->NonBlockingRun2($exec,$time,$code);
$filename = $env['output_path'].'/dfs_browse.'.$time.'.out';
$list_arr = file($filename);
echo "<br>";
echo "<a href=fileBrowser.php?dir=/>".$lang['backToRoot']."</a><br><br>";
echo "<a href=javascript:history.back()>".$lang['back']."</a><br><br>";
echo "<table border=1 cellspacing=1 cellpadding=3>";
echo "<tr bgcolor=\"#FFFF99\">";
echo "<td>".$lang['filename']."</td>";
echo "</tr>";
$i = 0;
foreach( $list_arr as $k => $v)
{
	if(($i % 2) == 0)
	{
		$color = $env['trColor1'];
	}
	else
	{
		$color = $env['trColor2'];
	}
	if($v != "")
	{
		echo "<tr bgcolor=\"".$color."\">";
		$pos = strpos($v,"/"); 
		$str = trim(substr($v,($pos-1),strlen($v)));
		echo "<td><a href=fileBrowser.php?path=$str>".$str."</a></td>\n";
		echo "</tr>";
	}
	$i++;
}
echo "</table>";

unlink($filename);


?>