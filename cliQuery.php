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
	$LANG = $env['setenv'].' LANG='.$env['lang_set'].'; ';
	$HADOOP_HOME = $env['setenv'].' HADOOP_HOME='.$env['hadoop_home'].'; ';
	$HIVE_HOME = $env['setenv'].' HIVE_HOME='.$env['hive_home'].'; ';
	$JAVA_HOME = $env['setenv'].'JAVA_HOME='.$env['java_home'].'; ';
	
	$UDF = ($env['udf'] != "") ? $env['udf'] : "";
	
	if(file_exists($env['output_path']))
	{
		$sql = trim(rawurldecode($query));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		#log sql action
		$logfile = $env['logs_path'].$_SESSION['username']."_".$time.".log";
		$etc->LogAction($logfile,"w",$sql."\n");
		#
		
		#didn't use sql verification, may cause be hacked
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			
			$exec = $LANG . $HADOOP_HOME . $HIVE_HOME . $JAVA_HOME. $env['hive_home'].'/bin/hive '.$UDF.' -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			
			//passthru($exec);
			#$log = $env['logs_path'].$time.".debug";
			#$etc->LogAction($log,"w",$exec."\n");
			$runfile = $env['output_path']."/hive_run.".$time.".out";
			$etc->NonBlockingRun($exec,$time,$runfile,2,$code);
			$etc->ExportCSV($time);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
	else
	{
		mkdir($env['output_path'],777);
		
		$sql = trim(urldecode($query,$key));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		#log sql action
		$logfile = $env['logs_path'].$_SESSION['username']."_".$time.".log";
		$etc->LogAction($logfile,"w",$sql."\n");
		#
		
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			$LANG = $env['setenv'].' LANG='.$env['lang_set'].'; ';
			$HADOOP_HOME = $env['setenv'].' HADOOP_HOME='.$env['hadoop_home'].'; ';
			$HIVE_HOME = $env['setenv'].' HIVE_HOME='.$env['hive_home'].'; ';
			$JAVA_HOME = $env['setenv'].'JAVA_HOME='.$env['java_home'].'; ';

			$exec = $LANG . $HADOOP_HOME . $HIVE_HOME . $JAVA_HOME. $env['hive_home'].'/bin/hive '.$UDF.' -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			
			//passthru($exec);
			$runfile = $env['output_path']."/hive_run.".$time.".out";
			$etc->NonBlockingRun($exec,$time,$runfile,2,$code);
			$etc->ExportCSV($time);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
}
?>