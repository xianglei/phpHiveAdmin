<?php
include_once 'config.inc.php';
include_once 'templates/style.css';


$etl = new Etl;

if(!@$_POST['filename'])
{
	$dir = dir($env['etl']);
	
	$i = 0;
	while (false !== ($entry = $dir->read())) 
	{
		if($entry != '.')
		{
			if($entry != '..')
			{
				if ($entry != "")
				{
					$filename[$i] = $entry;
				}
				else
				{
					$filename[$i] = "";
				}
			}
		}
		$i++;
	}
	$dir->close();

	echo "<<< <a href=index.php?frame=right>".$lang['back']."</a><br><br>";
	echo $lang['putIni']."'./etl/'<br><br>";
	echo "<form method=post>";
	echo "<table border=1 cellspacing=1 cellpadding=3>";
	$i = 0;
	foreach($filename as $k => $v)
	{
		echo "<tr>
		<td>
		<input type=checkbox name=filename[] value=".$v.">
		</td>
		<td>
		".$v."
		</td>
		<td>
		<a href=editEtl.php?filename=".$v.">".$lang['edit']."</a>
		</td>
		<td>
		<a href=# onclick=\"javascript:realconfirm('".$lang['dropEtlConfirm']."','dropEtl.php?filename=".$v."');\">".$lang['delete']."</a>
		</td>
		</tr>";
		$i++;
	}
	echo "</table><br>";
	echo "<input type=submit value=".$lang['execEtl'].">";
	echo "</form>";
}
else
{
	if($_POST['filename'][0] != "")
	{
		foreach($_POST['filename'] as $k => $v)
		{
			$ini = $etl->ParseEtl($v);
			if($ini == FALSE)
			{
				die($lang['iniFileError']);
			}
			//var_dump($ini);
			/*
			; Comments start with ';'
			[mysql]
			hostname = "192.168.1.50"
			port = "3306";
			username = "root"
			password = "password"
			database = "exampledb";
			table = "exampletable"
			; type can be set to 'load' and 'sql', but suggest use load
			type = "load"
	
			[hive]
			sql = "select count(*) from exampledb.exampletable where a=1"
			hostname = "192.168.1.49"
			port = "10000"
			username = ""
			password = ""
			database = "example_hive_database"
			table = "example_hive_table"
			; type can be set to csv and sql, but suggest to use csv
			type = "csv"
			udf = "/opt/modules/hive/hive-0.7.1/lib/hive-contrib-0.7.1.jar"
			terminator = ","
			*/
			$db = mysql_connect($ini['mysql']['hostname'].":".$ini['mysql']['port'] , $ini['mysql']['username'] , $ini['mysql']['password']);
			
			$transport = new TSocket($ini['hive']['hostname'], $ini['hive']['port']);
			$protocol = new TBinaryProtocol($transport);
			$client = new ThriftHiveClient($protocol);
			
			$filename = "/tmp/".sha1(time()).".csv";
			echo $hql = "INSERT OVERWRITE LOCAL DIRECTORY '/tmp/tmp0' ".$ini['hive']['sql'];
			
			$transport->open();
			$client->execute('add jar '.$ini['hive']['udf']);
			$client->execute($hql);
			
			$fd = fopen("/tmp/tmp0/000000_0","rb");
			$fp = fopen($filename,"wb");
			while (!feof($fd))
			{
				$str = trim(fgets($fd,4096));
				$str = str_replace("\x01", $ini['mysql']['terminator'], $str)."\n";
				fwrite($fp,$str);
				unset($str);
			}
			fclose($fp);
			fclose($fd);
			
			if($ini['mysql']['type'] == "load")
			{
				if($ini['mysql']['load'] == "overwrite")
				{
					$replace = " REPLACE ";
				}
				elseif ($ini['mysql']['load'] == "new")
				{
					$replace = " ";
				}
				else	
				{
					die($lang['iniFileError']);
				}
				$sql = "LOAD DATA LOCAL INFILE '/tmp/".$filename."' ".$replace." INTO TABLE ".$ini['mysql']['database'].".".$ini['mysql']['table']." FIELDS TERMINATED BY '\\".$ini['mysql']['terminator']."'";
			}
			echo "<br>".$sql."<br>";
			mysql_query($sql);
		
			$transport->close();
			unlink("/tmp/tmp0/000000_0");
			unlink($filename);
			mysql_close($db);
		}
	}
	else
	{
		die ('<script>alert(\''.$lang['noFileChoose'].'\';history.back();</script>');
	}
}