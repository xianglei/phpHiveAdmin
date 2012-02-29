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
			echo "For local use, input path as /data/data1/. For HDFS use, input path as /data/data1/ where path in HDFS<br>";
			echo "<table border=1 cellspacing=1 cellpadding=3>\n";
			
			echo "<tr bgcolor=#FFFF99>\n";
			echo "<td>".$lang['chooseFS']."</td>\n";
			echo "<td>".$lang['pathToLoad']."</td>\n";
			echo "<td>".$lang['ifPartition']."</td>";
			echo "<td>".$lang['overwrite']."</td>";
			echo "<tr>\n";
			
			echo "<tr bgcolor=#99FFFF>\n";
			echo "<td><select name=local><option value=local>".$lang['local']."</option><option value=hdfs>".$lang['hdfs']."</option></select></td>\n";
			echo "<td><input type=text name=path></td>\n";
			echo "<td><input type=text name=partition></td>\n";
			echo "<td><input type=checkbox name=overwrite value=1></td>\n";
			echo "<tr>\n";
			
			echo "</table><br>\n";
			echo "<input type=hidden name=database value=".$_GET['database'].">";
			echo "<input type=hidden name=table value=".$_GET['table'].">";
			echo "<input type=submit value=".$lang['submit'].">\n";
			echo "</form>\n";
		}
		else
		{
			if($_POST['path'] == '')
			{
				echo "<script>alert('must enter path');history.back();</script>";
			}
			else
			{
				if($_POST['partition'] == '')
				{
					$par = "";
				}
				else
				{
					$par = " PARTITION ".$_POST['partition'];
				}
				
				if($_POST['local'] == "local")
				{
					$local = " LOCAL ";
				}
				else
				{
					$local = "";
				}
				
				if(@$_POST['overwrite'] == 1)
				{
					$over = " OVERWRITE ";
				}
				else
				{
					$over = "";
				}
				$sql = "LOAD DATA ".$local." INPATH '".$_POST['path']."' ".$over." INTO TABLE ".$_POST['table'].$par ;
				echo $sql;
				$client->execute($sql);
				echo "<script>alert('".$lang['loadDataSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
			}
		}
	}
	$transport->close();
}