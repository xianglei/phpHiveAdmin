<?php
ignore_user_abort(true);
set_time_limit(0);


if(!$_GET['database'] || '' == $_GET['database'])
{
	die($lang['dieTableChoose']);
}
else
{

	echo $_GET['database'].' --  <a href=index.php?frame=right target="right">Back</a><br /><br />';
	
	include_once 'config.inc.php';
	include_once 'templates/sql_query_navi.html';
	echo "<br /><br />";
	
	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	
	$sql = 'use '.$_GET['database'];
	//echo $sql.'<br /><br />';
	$client->execute($sql);
	//$client->fetchOne();
	
	$sql = 'desc '.$_GET['table'];
	$client->execute($sql);
	$array_desc_table = $client->fetchAll();
	//get table description and explode the desc into a multi-dimensional array
	//获取表说明，并放入二维数组$array_desc_desc
	$i = 0;
	while ('' != @$array_desc_table[$i])
	{
		$array_desc = explode('	',$array_desc_table[$i]);
		$array_desc_desc['name'][$i] = $array_desc[0];
		$array_desc_desc['type'][$i] = $array_desc[1];
		$i++;
	}
	//var_dump($array_desc_desc);

	if(!@$_POST['sql'] || '' == @$_POST['sql'])
	{
		echo "<body bgcolor=\"".$env['bodyColor']."\">";
		echo $lang['exportSQL'];
		include_once 'templates/sql_query.html';
	}
	else
	{
		$timer->start();
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		echo "<body bgcolor=\"".$env['bodyColor']."\">";
		$sql = $_POST['sql'];
		//add limit to standard sql
		
		echo $sql.'<br /><br />';
		$client->execute($sql);
		$array = $client->fetchAll();

		//construct table desc table
		//construct result file
		$time = date('Y-m-d_H-i-s',time());
		$timehash = sha1($time);
		$filename = 'tmp/'.$_GET['table'].'_'.$time.'_'.$timehash.'.csv';
		
		$fp = fopen($filename,'w');
		$i = 0;
		while ('' != @$array[$i])
		{
			$str = str_replace('	',',',$array[$i])."\n";
			fwrite($fp,$str);
			$i++;
		}
		fclose($fp);
		echo "<br /><br />";
		echo "<a href=download.php?filename=".$filename.">".$lang['download']."</a><br /><br />";
		include_once 'templates/sql_query.html';
		$timer->stop();
		echo 'Excution time: '.$timer->spent().'s';
		unset($timer);
	}

	$transport->close();
}
?>
