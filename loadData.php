<?php

include_once 'config.inc.php';
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

	$client->execute('use '.$_GET['database']);
	
	if(!@$_GET['table'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		echo "<a href=dbStructure.php?database=".$_GET['database'].">".$lang['back']."</a><br><br>";
		if(!@$_POST['path'])
		{
			include_once "templates/load_data.html";
		}
		else
		{
			if($_POST['path'] == '')
			{
				echo "<script>alert(".$lang['mustEnterPath'].");history.back();</script>";
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