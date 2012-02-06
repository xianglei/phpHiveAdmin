<?php
ignore_user_abort(true);
set_time_limit(0);

if(!$_GET['database'] || '' == $_GET['database'])
{
	die($lang['dieTableChoose']);
}
else
{

	echo $_GET['database']." --  <a href=index.php>Back</a><br /><br />";

	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	
	$sql = 'use '.$_GET['database'];
	//echo $sql.'<br /><br />';
	$client->execute($sql);
	$client->fetchOne();

	if(!@$_POST['sql'] || '' == @$_POST['sql'])
	{
		include_once 'templates/sql_query.html';
	}
	else
	{

		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

		$sql = $_POST['sql'].' limit 10';
		echo $sql.'<br /><br />';
		$client->execute($sql);
		$array = $client->fetchAll();
		//$array = call_user_func('query',$sql);
		$i = 0;
		while ('' != @$array[$i])
		{
			echo $array[$i]."<br />";
			$i++;
		}
		include_once 'templates/sql_query.html';
	}

	$transport->close();
}
?>
