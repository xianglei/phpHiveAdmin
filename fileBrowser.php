<?php

include_once 'config.inc.php';
include_once 'templates/style.css';


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

$LANG = 'export LANG='.$env['lang_set'].'; ';
$HADOOP_HOME = 'export HADOOP_HOME='.$env['hadoop_home'].'; ';
$HIVE_HOME = 'export HIVE_HOME='.$env['hive_home'].'; ';
$JAVA_HOME = 'export JAVA_HOME='.$env['java_home'].'; ';
$UDF = ($env['udf'] != "") ? $env['udf'] : "";

$exec = ($LANG . $HADOOP_HOME . $HIVE_HOME . $JAVA_HOME. $env['hive_home'].'/bin/hive '.$UDF.' -e '.$sql);

$time = time();
$filename = $env['output_path'].'/dfs_browse.'.$time.'.out';
$etc->NonBlockingRun($exec,$time,$filename,1,$code);

$list_arr = file($filename);
echo "<br>";
echo "<a href=fileBrowser.php?dir=/>".$lang['backToRoot']."</a><br><br>";
echo "<a href=javascript:history.back()>".$lang['back']."</a><br><br>";
echo "<table border=1 cellspacing=1 cellpadding=3 width=70%>";
echo "<tr bgcolor=\"#FFFF99\">";
echo "<td>".$lang['fileProperty']."</td>";
echo "<td>".$lang['fileUser']."</td>";
echo "<td>".$lang['fileGroup']."</td>";
echo "<td>".$lang['filesize']."</td>";
echo "<td>".$lang['fileTime']."</td>";
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
	if(!preg_match("/Found/i", $v))
	{
		echo "<tr bgcolor=\"".$color."\">";
		$tmp = explode(" ", $v);
		$tmp = $etc->ArrayFilter($tmp);
		#var_dump($tmp);
		echo "<td>".$tmp[0]."</td>";
		echo "<td>".$tmp[2]."</td>";
		echo "<td>".$tmp[3]."</td>";
		echo "<td>".round($tmp[4]/1024/1024,2)."MB</td>";
		echo "<td>".$tmp[5]." ".$tmp[6]."</td>";
		echo "<td><a href=fileBrowser.php?path=".$tmp[7].">".$tmp[7]."</a></td>\n";
		echo "</tr>";
	}
	$i++;
}
echo "</table>";
echo "<br>";
echo "<a href=javascript:history.back()>".$lang['back']."</a><br><br>";
echo "<a href=fileBrowser.php?dir=/>".$lang['backToRoot']."</a><br><br>";
unlink($filename);

?>