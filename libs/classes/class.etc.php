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

class StringCoding extends Etc
{
	
	public $gb;          // 待转换的GB2312字符串
	public $utf8;        // 转换后的UTF8字符串
	public $CodeTable;   // 转换过程中使用的GB2312代码文件数组
	public $ErrorMsg;    // 转换过程之中的错误讯息

	public function Gb2Utf8($InStr="")
	{
		$this->gb=$InStr;
		$this->SetGb2312();
		($this->gb=="")?0:$this->Convert();
	}

	public function SetGb2312($InStr="gb2312.txt")
	{
		$this->ErrorMsg="";
		$tmp=@file($InStr);
        if (!$tmp)
        {
			$this->ErrorMsg="No GB2312";
			return false;
		}
		$this->CodeTable=array();
		while(list($key,$value)=each($tmp))
		{
			$this->CodeTable[hexdec(substr($value,0,6))]=substr($value,7,6);
		}
	}
 
	public function Convert()
	{
		$this->utf8="";
		if(!trim($this->gb) || $this->ErrorMsg!="")
		{
			return ($this->utf8=$this->ErrorMsg);
		}
		$str=$this->gb;

		while($str)
		{
			if (ord(substr($str,0,1))>127)
			{
				$tmp=substr($str,0,2);
				$str=substr($str,2,strlen($str));
				$tmp=$this->U2Utf8(hexdec($this->CodeTable[hexdec(bin2hex($tmp))-0x8080]));
				for($i=0;$i<strlen ($tmp);$i+=3)
				$this->utf8.=chr(substr($tmp,$i,3));
			}
			else
			{
				$tmp=substr($str,0,1);
				$str=substr($str,1,strlen($str));
				$this->utf8.=$tmp;
			}
		}
		return $this->utf8;
	}


	public function U2Utf8($InStr)
	{
		for($i=0;$i<count($InStr);$i++)
		{
			$str="";
			if ($InStr < 0x80)
			{
				$str.=ord($InStr);
			}
			else if ($InStr < 0x800)
			{
				$str.=(0xC0 | $InStr>>6);
				$str.=(0x80 | $InStr & 0x3F);
			}
			else if ($InStr < 0x10000)
			{
				$str.=(0xE0 | $InStr>>12);
				$str.=(0x80 | $InStr>>6 & 0x3F);
				$str.=(0x80 | $InStr & 0x3F);
			}
			else if ($InStr < 0x200000)
			{
				$str.=(0xF0 | $InStr>>18);
				$str.=(0x80 | $InStr>>12 & 0x3F);
				$str.=(0x80 | $InStr>>6 & 0x3F);
				$str.=(0x80 | $InStr & 0x3F);
			}
			return $str;
		}
	}
}
?>