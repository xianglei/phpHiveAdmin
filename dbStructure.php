<?php
include_once "config.inc.php";
include_once 'templates/style.css';

$etc = new Etc;

if(!@$_GET['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_GET['database']);

	$client->execute($env['showTables']);
	$db_array = $client->fetchAll();
	$db_array = array_reverse($db_array);

	echo '<div class="container">';
	echo '<div class="span10">';
	
	if(!@$_POST["table_name"] || "" == $_POST["table_name"])
	{
		echo "<table class=\"table table-bordered table-condensed\"><tr class=\"error\"><td>";
		if(count($db_array) != 0)
		{
			echo "<i class=icon-remove></i>".$lang['dropDatabase'];
		}
		else
		{
			echo "<i class=icon-remove></i><a href=# onclick=\"javascript:realconfirm('".$lang['dropDbConfirm']."','dropDatabase.php?database=".$_GET['database']."');return false;\"><img src=images/b_deltbl.png>".$lang['dropDatabase']."</a>";
		}
		echo "</td></tr></table><br><br>";
		
		$i = 0;
		echo "<form method=post action=dropTableBatch.php  name=tablenames>";
		echo "<table class=\"table table-bordered table-striped table-hover table-condensed\">\n";
		echo "<thead><tr class=\"info\">
		<td></td>
		<td>".$lang['tableName']."</td>";
		if($_SESSION['role'] == "superadmin" || $_SESSION['role'] == "dbadmin")
		{
			echo "<td>".$lang['alterTable']."</td>
			<td>".$lang['loadData']."</td>
			<td>".$lang['cloneTable']."</td>
			<td>".$lang['tableDetail']."</td>
			<td>".$lang['dropTable']."</td>";
		}
		echo "</tr><thead>";
		echo "<tbody>";
		while ('' != @$db_array[$i])
		{
			echo "<tr>\n";

			echo "<td>\n";
			echo "<input type=checkbox name=table_name[] value=".$db_array[$i].">";
			echo "</td>\n";
		
			echo "<td>\n";
			echo '<a href=sqlQuery.php?table='.$db_array[$i].'&database='.$_GET['database'].' target="right">'.$db_array[$i].'</a>';
			echo "</td>\n";
			if($_SESSION['role'] == "superadmin" || $_SESSION['role'] == "dbadmin")
			{
				echo "<td>\n";
				echo "<a href=alterTable.php?database=".$_GET['database']."&table=".$db_array[$i]."><i class=icon-pencil></i> ".$lang['alterTable']."</a>";
				echo "</td>\n";
			
				echo "<td>\n";
				echo "<a href=loadData.php?database=".$_GET['database']."&table=".$db_array[$i]."><i class=icon-chevron-right></i> ".$lang['loadData']."</a>";
				echo "</td>\n";
				
				echo "<td>\n";
				echo "<a href=\"cloneTable.php?database=".$_GET['database']."&table=".$db_array[$i]."\"><i class=icon-random></i> ".$lang['cloneTable']."</a>";
				echo "</td>\n";

				echo "<td>\n";
				echo "<a href=\"tableDetail.php?database=".$_GET['database']."&table=".$db_array[$i]."\"><i class=icon-zoom-in></i> ".$lang['tableDetail']."</a>";
				echo "</td>\n";

				echo "<td>\n";
				echo "<a href=# onclick=\"javascript:realconfirm('".$lang['dropTableConfirm']." ".$db_array[$i]."','dropTable.php?database=".$_GET['database']."&table=".$db_array[$i]."');return false;\"><i class=icon-remove></i>".$lang['dropTable']."</a>";
				echo "</td>\n";
			}
			
			echo "</tr>\n";
			$i++;
		}
		echo "</tbody>";
		echo "</table><br>\n";
		if($_SESSION['role'] == "superadmin" || $_SESSION['role'] == "dbadmin")
		{
			echo "<input type=hidden name=database value=".$_GET['database'].">";
			echo "<input name=\"allSelect\" type=\"checkbox\" id=\"allSelect\" value=\"\" onClick=\"isSelect(tablenames)\" />";
			echo $lang['select']." / ".$lang['deselect']."&nbsp;&nbsp;<input type=submit name=submit value=".$lang['dropTable']." class=\"btn btn-danger btn-small\"><bR>\n";
		}
		echo "</form><br><br>";
		if($_SESSION['role'] == "superadmin" || $_SESSION['role'] == "dbadmin")
		{
			include_once "templates/add_table.html";
		}
	}
	else
	{
		var_dump($_POST["table_name"]);
	}
	echo "</div>";
	echo "</div>";
	$transport->close();
}
?>