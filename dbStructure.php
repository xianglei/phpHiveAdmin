<?php
include_once "config.inc.php";
include_once 'templates/style.css';

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

	//$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_GET['database']);

	$client->execute($env['showTables']);
	$db_array = $client->fetchAll();
	$db_array = array_reverse($db_array);

	if(!@$_POST["table_name"] || "" == $_POST["table_name"])
	{
		echo "<table border=1 cellspacing=1 cellpadding=3><tr bgcolor=#FFFF99><td>";
		if(count($db_array) != 0)
		{
			echo "<img src=images/b_deltbl.png>".$lang['dropDatabase'];
		}
		else
		{
			echo "<a href=dropDatabase.php?database=".$_GET['database']."><img src=images/b_deltbl.png>".$lang['dropDatabase']."</a>";
		}
		echo "</td></tr></table><br><br>";
		
		$i = 0;
		echo "<form method=post action=dbStructure.php name=tablenames>";
		echo "<table border=1 cellspacing=1 cellpadding=3>\n";
		echo "<tr bgcolor=#FFFF99>
		<td><input name=\"allSelect\" type=\"checkbox\" id=\"allSelect\" value=\"\" onClick=\"javascript:isSelect('tablenames');\" /></td>
		<td>".$lang['tableName']."</td>";
		if($_SESSION['onlydb'] == "all")
		{
			echo "<td>".$lang['alterTable']."</td>
			<td>".$lang['loadData']."</td>
			<td>".$lang['cloneTable']."</td>
			<td>".$lang['dropTable']."</td>";
		}
		echo "</tr>";
		while ('' != @$db_array[$i])
		{
			if(($i % 2) == 0)
			{
				$color = $env['trColor1'];
			}
			else
			{
				$color = $env['trColor2'];
			}
			echo "<tr bgcolor=".$color.">\n";

			echo "<td>\n";
			echo "<input type=checkbox name=table_name[] value=".$db_array[$i].">";
			echo "</td>\n";
		
			echo "<td>\n";
			echo '<a href=sqlQuery.php?table='.$db_array[$i].'&database='.$_GET['database'].' target="right">'.$db_array[$i].'</a>';
			echo "</td>\n";
			if($_SESSION['onlydb'] == "all")
			{
				echo "<td>\n";
				echo "<a href=alterTable.php?database=".$_GET['database']."&table=".$db_array[$i]."><img src=images/b_props.png>".$lang['alterTable']."</a>";
				echo "</td>\n";
			
				echo "<td>\n";
				echo "<a href=loadData.php?database=".$_GET['database']."&table=".$db_array[$i]."><img src=images/b_import.png>".$lang['loadData']."</a>";
				echo "</td>\n";
				
				echo "<td>\n";
				echo "<a href=cloneTable.php?database=".$_GET['database']."&table=".$db_array[$i]."\">".$lang['cloneTable']."</a>";
				echo "</td>\n";
		
				echo "<td>\n";
				echo "<a href=# onclick=\"javascript:realconfirm('".$lang['dropTableConfirm']."','dropTable.php?database=".$_GET['database']."&table=".$db_array[$i]."');return false;\"><img src=images/b_drop.png>".$lang['dropTable']."</a>";
				echo "</td>\n";
			}
			
			echo "</tr>\n";
			$i++;
		}
		echo "</table>\n";
		echo "<input name=\"allSelect\" type=\"checkbox\" id=\"allSelect\" value=\"\" onClick=\"isSelect('tablenames')\" />";
		echo $lang['select']." / ".$lang['deselect']."<bR><br>\n";
		echo "<input type=submit name=submit value=".$lang['submit'].">";
		echo "</form><br><br>";
		if($_SESSION['onlydb'] == "all")
		{
			include_once "templates/add_table.html";
		}
	}
	else
	{
		var_dump($_POST["table_name"]);
	}
	$transport->close();
}
?>