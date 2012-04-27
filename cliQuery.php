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
		$sql = trim(urldecode($query));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		#log sql action
		$logfile = $env['logs_path'].$_SESSION['username']."_".$time.".log";
		$etc->LogAction($logfile,"w",$sql."\n");
		#
		
		#didn't use sql verification, may cause be hacked
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			if($env['setenv'] == 'export')
			{
				$exec = 'export LANG='.$env['lang_set'].'; export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			}
			else
			{
				$exec = 'setenv LANG '.$env['lang_set'].' && setenv HADOOP_HOME '.$env['hadoop_home'].' && setenv HIVE_HOME '.$env['hive_home'].' && setenv JAVA_HOME '.$env['java_home'].' && '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			}
			//passthru($exec);
			#$log = $env['logs_path'].$time.".debug";
			#$etc->LogAction($log,"w",$exec."\n");
			$etc->NonBlockingRun($exec,$time,$code);
			$etc->ExportCSV($time);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
	else
	{
		mkdir($env['output_path'],0777);
		
		$sql = trim(urldecode($query,$key));
		$sql = str_replace("\"","'",$sql);
		$sql = '"'.str_replace('`',"",$sql).'"';
		
		#log sql action
		$logfile = $env['logs_path'].$_SESSION['username']."_".$time.".log";
		$etc->LogAction($logfile,"w",$sql."\n");
		#
		
		if(!file_exists($env['output_path'].'/hive_res.'.$time.'.out') || filesize($env['output_path'].'/hive_res.'.$time.'.out') == 0)
		{
			if($env['setenv'] == 'export')
			{
				$exec = 'export LANG='.$env['lang_set'].'; export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			}
			else
			{
				$exec = 'setenv LANG '.$env['lang_set'].' && setenv HADOOP_HOME '.$env['hadoop_home'].' && setenv HIVE_HOME '.$env['hive_home'].' && setenv JAVA_HOME '.$env['java_home'].' && '.$env['hive_home'].'/bin/hive -e '.$sql.' > '.$env['output_path'].'/hive_res.'.$time.'.out';
			}
			//passthru($exec);
			$etc->NonBlockingRun($exec,$time,$code);
			$etc->ExportCSV($time);
		}
		else
		{
			echo $lang['cliDone'];
		}
	}
}
?>