<?php
include_once 'templates/style.css';

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);
	
	if(!@$_GET['table'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		if(!@$_POST['path'])
		{
			echo "<form method=post>\n";
			echo "<table border=1 cellspacing=1 cellpadding=3>\n";
			
			echo "<tr bgcolor=#FFFF99>\n";
			echo "<td>".$lang['chooseFS']."</td>\n";
			echo "<td>".$lang['pathToLoad']."</td>\n";
			echo "<td>".$lang['ifPartition']."</td>";
			echo "<tr>\n";
			
			echo "<tr bgcolor=#99FFFF>\n";
			echo "<td><select name=local><option value=local>".$lang['local']."</option><option value=hdfs>".$lang['hdfs']."</option></select></td>\n";
			echo "<td><input type=text name=path></td>\n";
			echo "<td><input type=text name=partition></td>";
			echo "<tr>\n";
			
			echo "</table><br>\n";
			echo "<input type=hidden name=database value=".$_GET['database'].">";
			echo "<input type=hidden name=table value=".$_GET['table'].">";
			echo "<input type=submit value=".$lang['submit'].">\n";
			echo "</form>\n";
		}
	}
	$transport->close();
}