<?php
include_once 'config.inc.php';
include_once 'templates/style.css';


if(!@$_POST['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	$transport = new TSocket(HOST, PORT);
	$protocol = new TBinaryProtocol($transport);
	$client = new ThriftHiveClient($protocol);
	
	$transport->open();

	$client->execute('use '.$_POST['database']);
	
	if(!@$_POST['table_name'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		if(!$_POST['confirm'])
		{
			echo "<form method=post>";
			foreach($_POST['table_name'] as $k => $v):
				echo "drop table ".$v."<br />\n";
				echo "<input type=hidden name=table_name[] value=".$v." />\n";
			endforeach;
			echo "<input type=hidden name=database value=".$_POST['database']." />\n";
			echo "<input type=hidden name=confirm value=1 />\n";
			echo "<input type=submit name=submit value=".$lang['submit']." />";
			echo "&nbsp;&nbsp;";
			echo "<input type=button value=".$lang['cancel']." onclick=\"javascript:this.location='dbStructure.php?database=".$_POST['database']."';\">";
			echo "</form>";
		}
		else
		{
			if(is_array($_POST['table_name']))
			{
				foreach($_POST['table_name'] as $k => $v):
					$sql = "drop table ".$v;
					$client->execute($sql);
				endforeach;
				echo "<script>alert('".$lang['dropTableSuccess']."');showsd('tableList.php?database=".$_POST['database']."','dbStructure.php?database=".$_POST['database']."');</script>";
			}
			else
			{
				die('Not valid table names');
			}
		}
	}
	$transport->close();
}