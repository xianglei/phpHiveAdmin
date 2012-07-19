<?php
class Authorize
{
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
			$array = parse_ini_file($pAuthFilename,TRUE);
			foreach($array as $k => $v)
			{
				if(preg_match("/\b".$pUsername."\b/",$v["username"]) && preg_match("/\b".$pPassword."\b/", $v["password"]))
				{
					return $v;
				}
				else
				{
					continue;
				}
			}
			return FALSE;
			
		}
		else
		{
			return FALSE;
		}
	}
	
	public function AuthMapReduceSlots($pAuthFilename,$pUsername,$pPassword)
	{
		if(file_exists($pAuthFilename))
		{
			$array = file($pAuthFilename);
			foreach($array as $k => $v)
			{
				if(preg_match("/".$pUsername."/",$v["username"]) && preg_match("/".$pPassword."/", $v["password"]))
				{
					return $v["mrslots"];
				}
				else
				{
					continue;
				}
			}
			return FALSE;
			
		}
		else
		{
			return FALSE;
		}
	}
	
	public function AuthSql($pPrivilege,$pSql)
	{
		if(preg_match("/\bcreate\b/i",$pSql) || 
			preg_match("/\bdrop\b/i",$pSql) || 
			preg_match("/\balter\b/i",$pSql) || 
			preg_match("/\binsert\b/i",$pSql) || 
			preg_match("/\bload\b/i",$pSql) || 
			preg_match("/\bset\b/i",$pSql) || 
			preg_match("/\bdfs -\b/i",$pSql)
			)
		{
			if($pPrivilege != "superadmin" || $pPrivilege != "dbadmin")
			{
				return FALSE;
			}
			else
			{
				return $pSql;
			}
		}
		else
		{
			return $pSql;
		}
	}
	
}
?>