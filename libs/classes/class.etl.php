<?php
class Etl
{
	public function DropEtl($pFilenames = array())
	{
		global $env;
		if(!is_array($pFilenames))
		{
			$pFilenames = array("$pFilenames");
		}
		foreach ($pFilenames as $k => $v)
		{
			if(unlink($env['etl'].$v))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
			
		}
	}
	
	public function GetEtl($pFilename = "")
	{
		global $env;
		if(file_exists($env['etl'].$pFilename))
		{
			$fp = fopen($env['etl'].$pFilename,"r");
			while (!feof($fp))
			{
				$str .= fgets($fp,1024);
			}
			fclose($fp);
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function PutEtl($pStr,$pFilename)
	{
		global $env;
		$fp = fopen($env['etl'].$pFilename,"w");
		fputs($fp, $pStr);
		fclose($fp);
	}
	
	public function ParseEtl($pFilename)
	{
		global $env;
		if(file_exists($env['etl'].$pFilename))
		{
			$array = parse_ini_file($env['etl'].$pFilename,TRUE);
			return $array;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function EtlHelp()
	{
		echo "Syntax: ".$_SERVER['argv'][0]. " -option value -option value...\n";
		echo "Options description:\n";
		echo "  -r      etl settings ini file\n";
		echo "  -s      Time range start flag\n";
		echo "  -t      Time range end flag\n";
		echo "  -h      This Help\n";
		echo "Example:  \n";
		echo "  /path/to/phpHiveAdmin/".$_SERVER['argv'][0]." -r example.ini -s 2012-07-06 -e 2012-08-06\n";
		echo "  /path/to/phpHiveAdmin/".$_SERVER['argv'][0]." -r example.ini\n\n";
	}
}
?>