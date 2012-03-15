<?php
class Authorize
{
	#pack the auth file with new value or removed values
	public function PackAuthFile($pAuthFilename,$pValues)
	{
		if(!file_exists($pAuthFilename))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	#$pValues username:password:onlydb, one name in array mode like this: array("xianglei:123:active","xianglei23:1234:active,active2")
	public function AddUsers($pAuthFilename,$pValues)
	{
		foreach($pValues as $k => $v)
		{
			$str .= $v."\n";
		}
		$filestr = trim($this->OpenAuthFile($pAuthFilename))."\n";
		$str = trim($filestr.$str);
		$fp = fopen($pAuthFilename,"w");
		fputs($fp,$str);
		fclose($fp);
	}
	
	#remove only one user each time,$pValue is a string like "xianglei:123:active,active2"
	public function RemoveUser($pAuthFilename,$pValue)
	{
		$str = $this->OpenAuthFile($pAuthFilename);
		$str = str_replace($pValue."\n","",$str);
		
		$fp = fopen($pAuthFilename,"w");
		fputs($fp,$str);
		fclose($fp);
	}
	
	#Open an authrize file and return a string, if it's not exists, try to create an empty one
	public function OpenAuthFile($pAuthFilename)
	{
		if(file_exists($pAuthFilename))
		{
			$fp = fopen($pAuthFilename,"r");
			while(!feof($fp))
			{
				$str .= fgets($fp,1024);
			}
			fclose($fp);
			return $str;
		}
		else
		{
			$fp = fopen($pAuthFilename,"w");
			fclose($fp);
		}
	}
	
	#Read username and password from a string, and try to return an onlydb string
	public function AuthUser($pAuthFilename,$pUsername,$pPassword)
	{
		if(file_exists($pAuthFilename))
		{
			$str = $this->OpenAuthFile($pAuthFilename);
			$auth = explode("\n",$str);
			foreach($auth as $k => $v)
			{
				$line = explode(":",$v);
				if(($pUsername == $line[0]) && ($pPassword == $line[1]))
				{
					$onlydb = $line[2];
					return $onlydb;
				}
				else
				{
					return "";
				}
			}
		}
		else
		{
			return "";
		}
	}
}
?>