#!/usr/loca/bin/php
<?php
set_time_limit(0);
include_once "config.inc.php";

$etl = new Etl;

if(count($_SERVER['argv'][0]) == 1)
{
	$etl->EtlHelp();
}
else
{
	foreach ($_SERVER['argv'] as $key => $value)
	{
		if(($key % 2) != 0)
		{
			switch ($value) {
				case '-h':
					$etl->EtlHelp();
					break;
					
				case '-r':
					if(file_exists($env['etl']."/".$_SERVER['argv'][$key+1]))
					{
						$ini = $etl->ParseEtl($v);
						if($ini == FALSE)
						{
							die($lang['iniFileError']);
						}
						$db = mysql_connect($ini['mysql']['hostname'].":".$ini['mysql']['port'] , $ini['mysql']['username'] , $ini['mysql']['password']);
			
						$transport = new TSocket($ini['hive']['hostname'], $ini['hive']['port']);
						$protocol = new TBinaryProtocol($transport);
						$client = new ThriftHiveClient($protocol);
			
						$filename = "/tmp/".sha1(time()).".csv";
						echo $hql = "INSERT OVERWRITE LOCAL DIRECTORY '/tmp/tmp0' ".$ini['hive']['sql'];
						echo "\n";
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
					}
					else
					{
						echo "Not found etl setting file\n";
					}
					break;
					
				default:
					
					break;
			}
		}
	}
}
?>