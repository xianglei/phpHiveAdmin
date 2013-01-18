<?php

class Utilities_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function array_reindex($array = array())
	{
		if(count($array) != 0)
		{
			$i = 0;
			foreach($array as $value)
			{
				$arr[$i] = $value; 
				$i++;
			}
			return $arr;
		}
		else
		{
			return array();
		}
	}

	public function array_filters($array)
	{
		if(is_array($array) == FALSE)
		{
			return False;
		}
		$i = 0;
		$arr = array();
		foreach ($array as $key => $value)
		{
			if($value != "")
			{
				$arr[$i] = $value;
			}
			$i++;
		}
		$arr = $this->array_reindex($arr);
		return $arr;
	}

	
	public function make_finger_print()
	{
		$mtime = explode(" ",microtime());
		$date = date("Y-m-d-H-i-s",$mtime[1]);
		$mtime = (float)$mtime[1] + (float)$mtime[0];
		$sha1 = $date."_".sha1($mtime);
		return $sha1;
	}
	
	public function make_filename($finger_print = '')
	{
		$this->load->library('session');
		if($finger_print == "")
		{
			$finger_print = $this->make_finger_print();
		}
		
		$filename = array();
		$filename['log'] = $this->session->userdata('username') . "_" . $finger_print . ".log";
		$filename['out'] = 'hive_res.' . $finger_print . '.out';
		$filename['csv'] = 'hive_res.' . $finger_print . '.csv';
		$filename['run'] = 'hive_res.' . $finger_print . '.run';
		
		$filename['log_with_path'] = $this->config->item('log_path') . $filename['log'];
		$filename['out_with_path'] = $this->config->item('result_path') . $filename['out'];
		$filename['csv_with_path'] = $this->config->item('result_path') . $filename['csv'];
		$filename['run_with_path'] = $this->config->item('result_path') . $filename['run'];
		
		
		return $filename;
	}
	
	public function export_csv($finger_print)
	{
		$filename = $this->make_filename($finger_print);
		$filename1 = $filename['out_with_path'];
		$filename2 = $filename['csv_with_path'];
		try
		{
			$fp1 = @fopen($filename1,"r");
			$fp2 = @fopen($filename2,"w");
			while(!feof($fp1))
			{
				$str = str_replace($this->config->item('output_seperator'), ",", fgets($fp1));
				fputs($fp2,$str);
			}
			fclose($fp2);
			fclose($fp1);
			
			unlink($filename1);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function quicksort_log_file($file_name_array)
	{
		if (count($file_name_array) <= 1)
		{
			return $file_name_array;
		}
		$key = explode("_",$file_name_array[0]);
		$key = $key[1];
		$left_arr = array();
		$right_arr = array();
		for ($i=1; $i<count($file_name_array); $i++)
		{
			$sort_key = explode("_",$file_name_array[$i]);
			if ( $sort_key[1] <= $key)
			{
				$left_arr[] = $file_name_array[$i];
			}
			else
			{
				$right_arr[] = $file_name_array[$i];
			}
		}
		$left_arr = $this->QuickSortForLogFile($left_arr);
		$right_arr = $this->QuickSortForLogFile($right_arr);

		return array_merge($right_arr, array($file_name_array[0]), $left_arr);
	}
	
	public function read_log_path()
	{
		$this->load->library('session');
		$dir = $this->config->item('log_path');
		$i = 0;
		try
		{
			$dh = opendir($dir);
			while (($file = readdir($dh)) !== false)
			{
				if(($file == '.') || ($file == '..'))
				{
					continue;
				}
				else
				{
					if(!is_dir($dir.$file))
					{
						if($this->session->userdata('role') == 'superadmin')
						{
							$file_array[$i] = $file;
						}
						else
						{
							if(preg_match('/'.$this->session->userdata('username').'/', $file))
							{
								$file_array[$i] = $file;
							}
						}
					}
				}
				$i++;
			}
			$file_array = $this->array_reindex($file_array);
			closedir($dh);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}

		return $file_array;
	}
	
	public function download_csv($finger_print)
	{
		$this->load->helper('file');
		$this->load->helper('download');
		try
		{
			$csv = 'hive_res.' . $finger_print . '.csv';
			$csv_with_path = $this->config->item('result_path') . $csv;
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header('Content-Length: ' . filesize($csv_with_path));
			header("Content-Disposition: attachment; filename=".$csv);
			$fp = fopen($csv_with_path, "r");
			while(!feof($fp))
			{
				echo $str = fread($fp,4096);
			}
			fclose($fp);
		}
		catch (Exception $e)
		{
			echo 'Caught exception: '.  $e->getMessage(). "\n";
		}
	}
	
	public function get_csv_filesize($finger_print)
	{
		$csv = 'hive_res.'. $finger_print .'.csv';
		$csv_with_path = $this->config->item('result_path') . $csv;
		if(file_exists($csv))
		{
			$this->load->helper('number');
			$file_size = byte_format(filesize($csv_with_path));
			return $file_size;
		}
		else
		{
			return "File not found";
		}
	}
	
	public function split_sql_cols($finger_print)
	{
		$this->load->helper('file');
		$this->load->model('history_model', 'history');
		$res = $this->history->get_history_by_fingerprint($finger_print);
		$username = $res->username;
		$log =$username . "_" . $finger_print . ".log";
		$log_with_path = $this->config->item('log_path') . $log;
		
		if(file_exists($log_with_path))
		{
			try
			{
				$sql = read_file($log_with_path);
			}
			catch (Exception $e)
			{
				echo 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		else
		{
			die('no such file');
		}
		
		$start = stripos($sql, "select") + 6;
		$end = stripos($sql, "from");
		$length = $end - $start;
		$sub = trim(substr($sql,$start,$length));
		$columns = explode(",", $sub);
		
		return $columns; #as an array
	}
	
	public function auth_sql($sql)
	{
		if(	preg_match("/^\s*insert\s+/i", $sql) || 
			preg_match("/^\s*drop\s+/i", $sql) || 
			preg_match("/^\s*create\s+/i", $sql) || 
			preg_match("/^\s*alter\s+/i", $sql) ||
			preg_match("/^\s*load\s+/i", $sql) ||
			preg_match("/^\s*set\s+/i", $sql) ||
			preg_match("/^\s*dfs\s+/i", $sql) )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
}

?>