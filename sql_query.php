<?php
ignore_user_abort(true);
set_time_limit(0);

/*function query($sql)
{
	global $client;
	$client->execute($sql);
	$array = $client->fetchAll();
	return $array;
}*/

if(!$_GET['database'] || '' == $_GET['database'])
{
	die('must choose a database');
}
else
{

	echo $_GET['database']." --  <a href=index.php>Back</a><br /><br />";

	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar /opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar');
	$sql = 'use '.$_GET['database'];
	echo $sql.'<br /><br />';
	$client->execute('use '.$_GET['database']);
	$client->fetchOne();

	if(!@$_POST['sql'] || '' == @$_POST['sql'])
	{
		$sql = "show tables";
                $client->execute($sql);
                $array = $client->fetchAll();
                $i = 0;
                while('' != @$array[$i])
                {
                        echo $array[$i]."&nbsp;|&nbsp;";
                        $i++;
                }
		echo "<br>";
		$sql = "desc ".$array[0];
		$client->execute($sql);
		$array = $client->fetchAll();
		$i = 0;
		while('' != @$array[$i])
		{
			echo $array[$i]."&nbsp;|&nbsp;";
			$i++;
		}

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
