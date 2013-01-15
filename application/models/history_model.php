<?php

class History_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function count_history($role = 'user')
	{
		if($role == "admin")
		{
			$sql = "select * from ehm_pha_history_job";
			$query = $this->db->query($sql);
			$count = $query->num_rows();
		}
		else
		{
			$username = $this->session->userdata('username');
			$sql = "select * from ehm_pha_history_job where username = '".$username."'";
			$query = $this->db->query($sql);
			$count = $query->num_rows();
		}
		return $count;
	}
	
	public function get_history_list($limit = "20", $offset = "0")
	{
		$sql = "select * from ehm_pha_history_job order by access_time desc limit ". $offset . ",". $limit;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;// object array need foreach to fetch it
	}
	
	public function get_history_list_by_user($username , $limit = "20", $offset = "0")
	{
		$sql = "select * from ehm_pha_history_job where username = '". $username ."' order by access_time desc limit ". $offset . ",". $limit;
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;// object array need foreach to fetch it
	}
	
	public function batch_drop_history($id = array())
	{
		if(count($id) > 0)
		{
			$ids = implode(",", $id);
			echo $sql = "select username,fingerprint from ehm_pha_history_job where id in (".$ids.")";
			$query = $this->db->query($sql);
			$result = $query->result();
			
			$this->load->model('utilities_model', 'utils');
			foreach($result as $row)
			{
				$filename = $this->utils->make_filename($row->fingerprint);
				$log = $row->username."_" . $row->fingerprint . ".log";
				$log_with_path =  $this->config->item('log_path') . $log;
				try
				{
					unlink($log_with_path);
					unlink($filename['csv_with_path']);
					unlink($filename['run_with_path']);
				}
				catch (Exception $e)
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
			}
			
			$sql = "delete from ehm_pha_history_job where id in (" . $ids . ")";
			if($this->db->simple_query($sql))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function create_history($username, $finger_print)
	{
		$sql = "insert ehm_pha_history_job set username = '". $username ."', fingerprint = '". $finger_print ."'";
		if($this->db->simple_query($sql))
		{
			$json = '{"status":"success"}';
		}
		else
		{
			$json = '{"status":"fail"}';
		}
		return $json;
	}
	
	public function get_history_by_fingerprint($finger_print)
	{
		$sql = "select * from ehm_pha_history_job where fingerprint = '".$finger_print."'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0];
	}
	
}

?>