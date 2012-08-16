<?php

include_once 'config.inc.php';
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
	
	if(!@$_GET['table'])
	{
		die($lang['dieTableChoose']);
	}
	else
	{
		$sql = "desc formatted ".$_GET['table'];
		$client->execute($sql);
		$arr = $client->fetchAll();
		$arr  = $etc->GetTableDetail($arr, "2");
		
		$j = 0;
		foreach ($arr as $k => $v)
		{
			$array_desc = explode(':',$v);
			$array_desc_desc['key'][$j] = trim($array_desc[0]);
			$array_desc_desc['value'][$j] = trim($array_desc[1]);
			if($array_desc_desc['value'][$j] =='MANAGED_TABLE' || $array_desc_desc['value'][$j] == "INDEX_TABLE")
			{
				$tmp['key'] = $array_desc_desc['key'][$j];
				$tmp['value'] = $array_desc_desc['value'][$j];
			}
			$j++;
		}var_dump($tmp);
		if($tmp['value'] != "MANAGED_TABLE" || $tmp['value'] != "INDEX_TABLE")
		{
			die('<script>alert("'.$lang['notExternalTable'].'");history.back();</script>');
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
}