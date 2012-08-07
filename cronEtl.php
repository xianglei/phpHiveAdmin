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