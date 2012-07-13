<?php
class Etc
{
	public function GetTableDetail($pArray,$pFlag)#flag=1 means columns detail, flag=2 means table properties, flag=3 means table sets flag=4 means partitions
	{
		foreach($pArray as $k=>$v)
		{
			if(preg_match('/^	 	 /',$v))
			{
				$index[$k] = $k;
			}
		}
		$index = $this->ArrayReindex($index);
		if($pFlag == "1")
		{
			$offset = 2;
			for ($i = $offset; $i < $index[1]; $i++)
			{
				$arr[$i] = trim($pArray[$i]);
			}
		}
		elseif($pFlag == "2")
		{
			foreach($pArray as $k => $v)
			{
				if(preg_match('/^# Detailed/',$v))
				{
					$offset_start = $k+1;
				}
				if(preg_match('/^# Storage/',$v))
				{
					$offset_end = $k-1;
				}
			}
			for($i = $offset_start; $i < $offset_end; $i++)
			{
				$arr[$i] = trim($pArray[$i]);
			}
		}
		elseif($pFlag == "3")
		{
			foreach($pArray as $k => $v)
			{
				if(preg_match('/^# Storage/',$v))
				{
					$offset_start = $k+1;
				}
			}
			$offset_end = count($pArray);
			for($i = $offset_start; $i <= $offset_end; $i++)
			{
				$arr[$i] = trim($pArray[$i]);
			}
		}
		elseif($pFlag == "4")
		{
			foreach($pArray as $k => $v)
			{
				if(preg_match('/^# Partition/',$v))
				{
					$offset_start = $k+3;
				}
				if(preg_match('/^# Detailed/',$v))
				{
					$offset_end = $k-1;
				}
			}
			if($offset_start == "")
			{
				$arr = array();
			}
			else
			{
				for($i = $offset_start; $i < $offset_end; $i++)
				{
					$arr[$i] = trim($pArray[$i]);
				}
			}
		}
		else
		{
			$arr = array();
		}
		$arr = $this->ArrayReindex($arr);
		return $arr;
	}
	
	public function SplitSqlColumn($pFilename)
	{
		$fp = @fopen($pFilename,"r");
		while(!@feof($fp))
		{
			$sql .= @fread($fp,1024);
		}
		@fclose($fp);
		$start = stripos($sql, "select") + 6;
		$end = stripos($sql, "from");
		$length = $end - $start;
		$sub = trim(substr($sql,$start,$length));
		$columns = explode(",", $sub);
				
		return $columns; #as an array
	}
	
	public function GetResult($pFilename)
	{
		$fp = fopen($pFilename,"r");
		$i = 0;
		while($i != 30)
		{
			$string .= fgets($fp,4096);
			$i++;
		}
		fclose($fp);
		return $string;
	}
	
	public function ArrayReindex($pArray)
	{
		if(is_array($pArray) == FALSE)
			$pArray[0] = "";
		$i = 0;
		foreach(@$pArray as $value)
		{
			$arr[$i] = $value; 
			$i++;
		}
		return $arr;
	}
	
	public function ArrayFilter($pArray)
	{
		if(is_array($pArray) == FALSE)
		{
			return False;
		}
		$i = 0;
		foreach ($pArray as $key => $value)
		{
			if($value != "")
			{
				$arr[$i] = $value;
			}
			$i++;
		}
		$arr = $this->ArrayReindex($arr);
		return $arr;
	}
	
	public function FingerPrintMake()
	{
		$mtime = explode(" ",microtime());
		$date = date("Y-m-d-H-i-s",$mtime[1]);
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
	
	public function NonBlockingRun($pCmd,$pTimestamp,$pFilename,$pType,&$pCode)
	{
		global $env;
		$descriptorspec = array(
			0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			2 => array("pipe", "w") // stderr is a file to write to
		);

		$pipes= array();
		
		#$log = $env['logs_path'].$pTimestamp.".debug";
		#$this->LogAction($log,"w",$pCmd."\n");
		
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
	
		$fp = fopen($pFilename,"w");
		#fwrite($fp,$pTimestamp."\n\n");
		while( true )
		{
			$read= array(); 
			#if( !feof($pipes[1]) ) $read[]= $pipes[1];
			if( !feof($pipes[$pType]) )	$read[]= $pipes[$pType];// get system stderr on real time
			
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

	public function ExportCSV($pFingerPrint)
	{
		global $env;
		$filename1 = $env['output_path'].'/hive_res.'.$pFingerPrint.'.out';
		$filename2 = $env['output_path'].'/hive_res.'.$pFingerPrint.'.csv';
		$fp1 = @fopen($filename1,"r");
		$fp2 = @fopen($filename2,"w");
		while(!feof($fp1))
		{
			$str = str_replace($env['seperator'], ",", fgets($fp1));
			fputs($fp2,$str);
		}
		fclose($fp2);
		fclose($fp1);
		
		unlink($filename1);
	}

	public function QuickSortForLogFile($pArray)
	{
		if (count($pArray) <= 1)
		{
			return $pArray;
		}
		$key = explode("_",$pArray[0]);
		$key = $key[1];
		$left_arr = array();
		$right_arr = array();
		for ($i=1; $i<count($pArray); $i++)
		{
			$sort_key = explode("_",$pArray[$i]);
			if ( $sort_key[1] <= $key)
			{
				$left_arr[] = $pArray[$i];
			}
			else
			{
				$right_arr[] = $pArray[$i];
			}
		}
		$left_arr = $this->QuickSortForLogFile($left_arr);
		$right_arr = $this->QuickSortForLogFile($right_arr);

		return array_merge($right_arr, array($pArray[0]), $left_arr);
	}
}



class Zebra_Pagination
{
    public function Zebra_Pagination()
    {

        $this->page = 1;
        $this->selectable_pages(11);
        $this->records_per_page(10);
        $this->records(0);
        $this->padding();
        $this->variable_name('page');
        $this->method('get');
        $this->trailing_slash(true);
        $this->base_url();
    }

    public function base_url($base_url = '')
    {
        $this->_base_url = ($base_url == '' ? $_SERVER['REQUEST_URI'] : $base_url);
        $this->_base_url = (strpos($this->_base_url, '?') !== false ? preg_replace('/^(.*?)\?.*$/', '$1', $this->_base_url) : $this->_base_url);
    }

    public function get_page()
    {
        if (!isset($this->page_set))
        {
            if (
                $this->_method == 'url' &&
                preg_match('/\b' . preg_quote($this->_variable_name) . '([0-9]+)\b/i', $_SERVER['REQUEST_URI'], $matches) > 0
            )
            {
                $this->set_page((int)$matches[1]);
            }
            elseif (isset($_GET[$this->_variable_name]))
            {
                $this->set_page((int)$_GET[$this->_variable_name]);
            }
        }
        $this->_total_pages = ceil($this->_records / $this->_records_per_page);
        if ($this->_total_pages > 0)
        {
            if ($this->page > $this->_total_pages) $this->page = $this->_total_pages;
            elseif ($this->page < 1) $this->page = 1;
        }
        return $this->page;
    }

    public function method($method)
    {
        $this->_method = 'get';
        $method = strtolower($method);
        if ($method == 'get' || $method == 'url') $this->_method = $method;
    }

    public function padding($enabled = true)
    {
        $this->_padding = $enabled;
    }

    public function records($records)
    {
        $this->_records = (int)$records;
    }

    public function records_per_page($records_per_page)
    {
        $this->_records_per_page = (int)$records_per_page;
    }

    public function render($return_output = false)
    {
    	global $lang;
        $this->get_page();
        if ($this->_total_pages <= 1) return '';
        $output = '<div class="pagination">';
        if ($this->_total_pages > $this->_selectable_pages)
        {
            $output .= '<a href="' .
                ($this->page == 1 ? 'javascript:void(0)' : $this->_build_uri($this->page - 1)) .
                '" class="navigation left' . ($this->page == 1 ? ' disabled' : '') . '"' .
                '>'.$lang['previousPage'].'</a>';
        }
        if ($this->_total_pages <= $this->_selectable_pages)
        {
            for ($i = 1; $i <= $this->_total_pages; $i++)
            {
                $output .= '<a href="' . $this->_build_uri($i) . '" ' .
                    ($this->page == $i ? 'class="current"' : '') . '>' .
                    ($this->_padding ? str_pad($i, strlen($this->_total_pages), '0', STR_PAD_LEFT) : $i) .
                    '</a>&nbsp;&nbsp;';
            }
        }
        else
        {
            $output .= '&nbsp;&nbsp;<a href="' . $this->_build_uri(1) . '" ' .
                ($this->page == 1 ? 'class="current"' : '') . '>' .
                ($this->_padding ? str_pad('1', strlen($this->_total_pages), '0', STR_PAD_LEFT) : '1') .
                '</a>&nbsp;&nbsp;';
            $adjacent = floor(($this->_selectable_pages - 3) / 2);
            $adjacent = ($adjacent == 0 ? 1 : $adjacent);
            $scroll_from = $this->_selectable_pages - $adjacent;
            $starting_page = 2;
            if ($this->page >= $scroll_from)
            {
                $starting_page = $this->page - $adjacent;
                if ($this->_total_pages - $starting_page < ($this->_selectable_pages - 2))
                {
                    $starting_page -= ($this->_selectable_pages - 2) - ($this->_total_pages - $starting_page);
                }
                $output .= '<span>&hellip;</span>';
            }

            $ending_page = $starting_page + $this->_selectable_pages - 3;
            if ($ending_page > $this->_total_pages - 1) $ending_page = $this->_total_pages - 1;
            for ($i = $starting_page; $i <= $ending_page; $i++)
            {
                $output .= '&nbsp;&nbsp;<a href="' . $this->_build_uri($i) . '" ' .
                    ($this->page == $i ? 'class="current"' : '') . '>' .
                    ($this->_padding ? str_pad($i, strlen($this->_total_pages), '0', STR_PAD_LEFT) : $i) .
                    '</a>&nbsp;&nbsp;';
            }
            if ($this->_total_pages - $ending_page > 1)
			{
            	$output .= '<span>&hellip;</span>';
			}

            $output .= '&nbsp;&nbsp;<a href="' . $this->_build_uri($this->_total_pages) . '" ' .
                ($this->page == $i ? 'class="current"' : '') . '>' .
                $this->_total_pages .
                '</a>&nbsp;&nbsp;';
            if ($this->_total_pages > $this->_selectable_pages)
            {
                $output .= '<a href="' .
                    ($this->page == $this->_total_pages ? 'javascript:void(0)' : $this->_build_uri($this->page + 1)) .
                    '" class="navigation right' . ($this->page == $this->_total_pages ? ' disabled' : '') . '"' .
                    '>'.$lang['nextPage'].'</a>';
            }
        }
        $output .= '</div>';
        if ($return_output) return $output;
        echo $output;
    }

    public function selectable_pages($selectable_pages)
    {
        $this->_selectable_pages = (int)$selectable_pages;
    }

    public function set_page($page)
    {
        $this->page = (int)$page;
        if ($this->page < 1) $this->page = 1;
        $this->page_set = true;
    }

    public function trailing_slash($enabled)
    {
        $this->_trailing_slash = $enabled;
    }

    public function variable_name($variable_name)
    {
        $this->_variable_name = strtolower($variable_name);
    }
	
    public function _build_uri($page)
    {
        if ($this->_method == 'url')
        {
            if (preg_match('/\b' . $this->_variable_name . '([0-9]+)\b/i', $this->_base_url, $matches) > 0)
            {
                $url = str_replace('//', '/', preg_replace(
                    '/\b' . $this->_variable_name . '([0-9]+)\b/i',
                    ($page == 1 ? '' : $this->_variable_name . $page),
                    $this->_base_url
                ));
            }
            else
            {
            	$url = rtrim($this->_base_url, '/') . '/' . ($page != 1 ? $this->_variable_name . $page : '');
			}
            $url = rtrim($url, '/') . ($this->_trailing_slash ? '/' : '');
            return ($_SERVER['QUERY_STRING'] != '' ? $url . '?' . $_SERVER['QUERY_STRING'] : $url);
        }
        else
        {
            parse_str($_SERVER['QUERY_STRING'], $query);
            if ($page != 1)
            {
                $query[$this->_variable_name] = $page;
			}
            else
            {
            	unset($query[$this->_variable_name]);
			}
            return htmlspecialchars($this->_base_url . (!empty($query) ? '?' . http_build_query($query) : ''));
        }
    }
}

?>