<?php
ignore_user_abort(true);
set_time_limit(0);
include_once "config.inc.php";

function runNonBlocking($cmd,$timestamp,$sql,&$code)
{
	$descriptorspec = array(
		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
		1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
		2 => array("pipe", "w") // stderr is a file to write to
	);

	$pipes= array();
	$process = proc_open($cmd, $descriptorspec, $pipes);

	$output= "";

	if (!is_resource($process))
	{
		return false;
	}

	#close child's input imidiately
	fclose($pipes[0]);

	stream_set_blocking($pipes[1],0);
	stream_set_blocking($pipes[2],0);
		
	$todo= array($pipes[1],$pipes[2]);
	
	$fp = fopen("/tmp/hive_run.".$timestamp.".out","w");
	//$fd = fopen("/tmp/hive_res.".$timestamp.".out","w");
	fwrite($fp,$timestamp."\n\n");
	while( true )
	{
		$read= array(); 
		#if( !feof($pipes[1]) ) $read[]= $pipes[1];
		if( !feof($pipes[2]) )	$read[]= $pipes[2];// get system stderr on real time
			
		if (!$read)
		{
			break;
		}
	
		$ready= stream_select($read, $write=NULL, $ex= NULL, 2);
	
		if ($ready === false)
		{
			break; #should never happen - something died
		}
			
		foreach ($read as $r)
		{
			$s= fread($r,128);
			$output .= $s;
			fwrite($fp,$s);
		}
	
	}

	/*while( true )
	{
		$read= array();
		if( !feof($pipes[1]) )	$read[]= $pipes[1];// get system stdout on real time
		#if( !feof($pipes[2]) ) $read[]= $pipes[2];

		if (!$read)
		{
			break;
		}

		$ready= stream_select($read, $write=NULL, $ex= NULL, 2);

		if ($ready === false)
		{
			break; #should never happen - something died
		}

		foreach ($read as $r)
		{
			$s= fread($r,128);
			$output .= $s;
			fwrite($fd,$s);
		}
	}
	fclose($fd);*/
	fclose($fp);

	fclose($pipes[1]);
	fclose($pipes[2]);

	$code= proc_close($process);

	return $output;
}


$query = @$_GET['query'];
$time = @$_GET['time'];

if("" == $query || "" == $time)
{
	die('Invalid entry');
}
else
{
	$sql = base64_decode($query);
	$sql = '"'.str_replace("\"","'",$sql).'"';
	$exec = 'export HADOOP_HOME='.$env['hadoop_home'].'; export HIVE_HOME='.$env['hive_home'].'; export JAVA_HOME='.$env['java_home'].'; '.$env['hive_home'].'/bin/hive -e '.$sql.' > /tmp/hive_res.'.$timestamp.'.out';
	//passthru($exec);
	runNonBlocking($exec,$time,$sql,$code);
}
?>