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
	public function encrypt($txt,$key)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
		$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
		$nh1 = rand(0,64);
		$nh2 = rand(0,64);
		$nh3 = rand(0,64);
		$ch1 = $chars{$nh1};
		$ch2 = $chars{$nh2};
		$ch3 = $chars{$nh3};
		$nhnum = $nh1 + $nh2 + $nh3;
		$knum = 0;$i = 0;
		while(isset($key{$i}))
		{
			$knum +=ord($key{$i++});
		}
		$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
		$txt = base64_encode($txt);
		$txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
		$tmp = '';
		$j=0;$k = 0;
		$tlen = strlen($txt);
		$klen = strlen($mdKey);
		for ($i=0; $i<$tlen; $i++)
		{
			$k = $k == $klen ? 0 : $k;
			$j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
			$tmp .= $chars{$j};
		}

		$tmplen = strlen($tmp);
		$tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
		$tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
		$tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
		return $tmp;
	}
	
	function decrypt($txt,$key)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
		$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
		$knum = 0;$i = 0;
		$tlen = strlen($txt);
		while(isset($key{$i}))
		{
			$knum +=ord($key{$i++});
		}
		$ch1 = $txt{$knum % $tlen};
		$nh1 = strpos($chars,$ch1);
		$txt = substr_replace($txt,'',$knum % $tlen--,1);
		$ch2 = $txt{$nh1 % $tlen};
		$nh2 = strpos($chars,$ch2);
		$txt = substr_replace($txt,'',$nh1 % $tlen--,1);
		$ch3 = $txt{$nh2 % $tlen};
		$nh3 = strpos($chars,$ch3);
		$txt = substr_replace($txt,'',$nh2 % $tlen--,1);
		$nhnum = $nh1 + $nh2 + $nh3;
		$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
		$tmp = '';
		$j=0; $k = 0;
		$tlen = strlen($txt);
		$klen = strlen($mdKey);
		for ($i=0; $i<$tlen; $i++)
		{
			$k = $k == $klen ? 0 : $k;
			$j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
			while ($j<0) $j+=64;
			$tmp .= $chars{$j};
		}
		$tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
		return trim(base64_decode($tmp));
	}
} 
?>