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
				echo $v." remove success <br>\n";
			}
			else
			{
				echo $v." remove unsuccess, check the file authority <BR>\n";
			}
			
		}
	}
	
	public function GetEtl($pFilename = "")
	{
		global $env;
		if(file_exists($env['etl'].$pFilename))
		{
			$fp = fopen($env['etl'].$pFilename,"r");
			while (!feof($env['etl'].$pFilename)) {
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
		else {
			return "ini file not exists";
		}
	}
}
?>