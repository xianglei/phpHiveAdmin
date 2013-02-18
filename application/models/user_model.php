<?php
class User_model extends CI_Model
{
	public function __construct()
	{ 
		parent::__construct();
	} 

	public function login_action($username, $password)
	{
		if(!empty($username) && !empty($password))
		{
			$sql="select * from ehm_pha_user where username='".$username."' and password='".md5($password)."'";
			$query = $this->db->query($sql);
			if ($query->num_rows() > 0)
			{
				$result = $query->result();
				$login = TRUE;
				$onlydb = explode(',', $result[0]->onlydb);
				$session_array = array(	'id' => $result[0]->id,
										'username' => $result[0]->username,
										'password' => $result[0]->password,
										'login' => $login,
										'onlydb' => $onlydb,
										'role' => $result[0]->role,
										'reduce' => $result[0]->reduce);
				$this->session->set_userdata($session_array);
			}
			else
			{
				$login = FALSE;
				$this->load->helper('url');
				redirect($this->config->base_url().'user/login/');
			}
		}
	}
	
	public function create_user($username, $password, $onlydb, $role = "user", $reduce = '2', $description)
	{
		#role must be user | admin
		$sql = "insert ehm_pha_user set username = '" . $username . "', password = '" . md5($password) . "', onlydb = '" . $onlydb . "', role = '" . $role . "', reduce = '" . $reduce . "', description = '" . $description . "'";
		if($this->db->simple_query($sql))
		{
			return '{"status":"success"}';
		}
		else
		{
			return '{"status":"fail"}';
		}
	}
	
	public function update_user($id, $username, $password, $onlydb, $role, $reduce="0", $description)
	{
		if($password != "")
		{
			$sql = "update ehm_pha_user set username = '" . $username . "', password = '" . md5($password) . "', onlydb = '" . $onlydb . "', role = '" . $role . "', reduce = '" . $reduce . "', description = '" . $description . "' where id = '" . $id . "'";
		}
		else
		{
			$sql = "update ehm_pha_user set username = '" . $username . "', onlydb = '" . $onlydb . "', role = '" . $role . "', reduce = '" . $reduce . "', description = '" . $description . "' where id = '" . $id . "'";
		}
		if($this->db->simple_query($sql))
		{
			return '{"status":"success"}';
		}
		else
		{
			return '{"status":"fail"}';
		}
	}
	
	public function drop_user($id)
	{
		$sql = "select user.username as username,job.fingerprint as fingerprint from ehm_pha_user user, ehm_pha_history_job job where user.id = '". $id ."' and user.username = job.username";
		$query = $this->db->query($sql);
		$result = @$query->result();
		$this->load->model('utilities_model', 'utils');
		$username = @$result[0]->username;
		foreach(@$result as $row)
		{
			$username = $row->username;
			$finger_print = $row->fingerprint;
			$filename = $this->utils->make_filename($finger_print);
			$log = $username .  "_" . $finger_print . ".log";
			$log_with_path = $this->config->item('log_path') . $log;
			try
			{
				@unlink($log_with_path);
				@unlink($filename['csv_with_path']);
				@unlink($filename['run_with_path']);
			}
			catch (Exception $e)
			{
				echo 'Caught exception: '.  $e->getMessage(). "\n";
			}
		}
		
		$sql = "delete from ehm_pha_user where id = '" . $id . "'";
		$this->db->simple_query($sql);
		$sql = "delete from ehm_pha_history_job where username = '".$username."'";
		if($this->db->simple_query($sql))
		{
			return '{"status":"success"}';
		}
		else
		{
			return '{"status":"fail"}';
		}
	}
	
	public function log_out()
	{
		$this->session->sess_destroy();
	}
	
	public function get_user($id)
	{
		$sql = "select * from ehm_pha_user where id = '" . $id . "'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0];//object
	}
	
	public function get_user_list()
	{
		$sql = "select * from ehm_pha_user where username != 'admin'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result;// object array need foreach to fetch it
	}
	
	public function update_password($user_id, $password)
	{
		if($password != "")
		{
			$sql = "update ehm_pha_user set password = '" . md5($password) . "' where id = '". $user_id ."'";
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
			echo "Empty password";
		}
	}
	
}
?>
