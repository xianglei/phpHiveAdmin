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

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	
	$sql = 'use '.$_GET['database'];
	//echo $sql.'<br /><br />';
	$client->execute($sql);
	$client->fetchOne();
	
	$sql = 'desc '.$_GET['table'];
	$client->execute($sql);
	$array_desc_table = $client->fetchAll();
	$i = 0;
	while ('' != @$array_desc_table[$i])
	{
		$array_desc = explode('	',$array_desc_table[$i]);
		$array_desc_name[$i] = $array_desc[0];
		$array_desc_type[$i] = $array_desc[1];
		$i++;
	}
	

	if(!@$_POST['sql'] || '' == @$_POST['sql'])
	{
		echo '<table border=1>';
		$i = 0;
		while ('' != @$array_desc_table[$i])
		{
			echo '<tr><td>'.$array_desc_name[$i].'</td></tr>';
			echo '<tr><td>'.$array_desc_type[$i].'</td></tr>';
			$i++;
		}
		echo '</table>';
		include_once 'templates/sql_query.html';
	}
	else
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

		$sql = $_POST['sql'];
		if(stristr($sql,'limit') === FALSE)
		{
			$sql .= ' limit 100';
		}
		else
		{
			$sql = $sql;
		}
		echo '<table border=1>';
		$i = 0;
		while ('' != @$array_desc_table[$i])
		{
			echo '<tr><td>'.$array_desc_name[$i].'</td></tr>';
			echo '<tr><td>'.$array_desc_type[$i].'</td></tr>';
			$i++;
		}
		echo '</table>';
		echo $sql.'<br /><br />';
		$client->execute($sql);
		$array = $client->fetchAll();
		//$array = call_user_func('query',$sql);
		$i = 0;
		echo '<table border=1>';
		while ('' != @$array[$i])
		{
			echo '<tr><td>'.$array[$i]."</td></tr>";
			$i++;
		}
		echo '</table>';
		include_once 'templates/sql_query.html';
	}

	$transport->close();
}
?>
