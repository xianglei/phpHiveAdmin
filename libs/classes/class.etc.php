<?php
class Etc
{
	public function FingerPrintMake()
	{
		$mtime = explode(" ",microtime());
		$date = date("Y-m-d",$mtime[1]);
		$mtime = (float)$mtime[1] + (float)$mtime[0];
		$sha1 = $date."_".sha1($mtime);
		return $sha1;
	}

	public function LogAction($pFilename,$pAct,$pStream)
	{
		$fp = fopen($pFilename,$pAct);
		fputs ($fp,$pStream);
		fclose($fp);
	}
	
	public function NonBlockingRun($pCmd,$pTimestamp,&$pCode)
	{
		global $env;
		$descriptorspec = array(
			0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			2 => array("pipe", "w") // stderr is a file to write to
		);

		$pipes= array();
		$process = proc_open($pCmd, $descriptorspec, $pipes);

		$output= "";

		if (!is_resource($process))
		{
			return false;
		}

		#close child's input imidiately
		fclose($pipes[0]);

		stream_set_blocking($pipes[1],0);
		stream_set_blocking($pipes[2],0);
		
		$todo= array($pipes[1],$pipes[2]);
	
		$fp = fopen($env['output_path']."/hive_run.".$pTimestamp.".out","w");
		fwrite($fp,$pTimestamp."\n\n");
		while( true )
		{
			$read= array(); 
			#if( !feof($pipes[1]) ) $read[]= $pipes[1];
			if( !feof($pipes[2]) )	$read[]= $pipes[2];// get system stderr on real time
			
			if (!$read)
			{
				break;
			}
	
			$ready= stream_select($read, $write=NULL, $ex= NULL, 2);
	
			if ($ready === false)
			{
				break; #should never happen - something died
			}
			
			foreach ($read as $r)
			{
				$s= fread($r,128);
				$output .= $s;
				fwrite($fp,$s);
			}
	
		}
	
		fclose($fp);

		fclose($pipes[1]);
		fclose($pipes[2]);

		$pCode= proc_close($process);

		return $output;
	}
}

class Encryption
{ 
/** 
* 最终的密文代码，可设为任意不重复的10位英文字符a-zA-Z 
*/ 
	private $replacement = 'urskydMeIV'; 
/** 
* 增加的密文第一位，可设为1位除0以外的整数，即 1-9 
*/ 
	private $prefix = "8"; 
/** 
* 公钥,长度小于8位的正整数 
*/ 
	private $match = "111111"; 
/** 
* 转换后对照数组 
*/ 
	private $replaceenc; 
	private $replacedec; 

	function __construct()
	{ 
		for($i =0; $i < 10; $i++)
		{ 
			$this->replaceenc['/'.$i.'/'] = $this->replacement{$i}; 
			$this->replacedec['/'.$this->replacement{$i}.'/'] = $i; 
		}
	}

	public function encrypt($str)
	{
		return preg_replace(array_keys($this->replaceenc),$this->replaceenc,$this->mynotin(preg_replace("/(.)(.)/", "${2}${1}", $str))); 
	}

	public function decrypt($str)
	{
		return preg_replace("/(.)(.)/", "${2}${1}",$this->mynotout(preg_replace(array_keys($this->replacedec),$this->replacedec,$str)));
	}

	private function mynotin($str)
	{ 
		$str_out = "";
		$i = 0;
		while(isset($str{7*$i}))
		{
			$str_out .= (($this->prefix.substr($str, $i*7, 7))+0)^$this->match;
			$i++;
		}
		return $str_out;
	}

	private function mynotout($str)
	{
		$str_out = "";
		$i = 0;
		while(isset($str{8*$i}))
		{
			$str_out .= substr((substr($str, $i*8, 8)+0)^$this->match, 1);
			$i++;
		}
		return $str_out;
	}
} 
?>