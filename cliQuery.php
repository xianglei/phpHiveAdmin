<?php
ignore_user_abort(true);
set_time_limit(0);
include_once "config.inc.php";

$etc = new Etc;

$query = @$_GET['query'];
$time = @$_GET['time'];

if("" == $query || "" == $time)
{
	die($lang['invalidEntry']);
}
else
{
	if(file_exists($env['output_path']))
	{
		$sql = trim(base64_decode($query));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		#log sql action
		$logfile = $env['logs_path'].$_SESSION['username']."_".$time.".log";
		$etc->LogAction($logfile,"w",$sql."\n");
		#
		
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			$exec = $env['lang'].' export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			//passthru($exec);
			$etc->NonBlockingRun($exec,$time,$code);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
	else
	{
		mkdir($env['output_path'],0777);
		
		$sql = trim(base64_decode($query,$key));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			$exec = $env['lang'].' export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			//passthru($exec);
			$etc->NonBlockingRun($exec,$time,$code);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
}
?>