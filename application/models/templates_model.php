<?php

interface TemplatesIf
{
	public function create_template($template_name, $template_content);
	public function delete_template($template_id);
	public function update_template($template_id, $template_name, $template_content);
	public function list_templates();
}

class Templates_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function create_template($template_name, $template_content, $user_id)
	{
		$sql = "insert ehm_pha_templates set t_name = '". $template_name ."', t_content = '" . mysql_real_escape_string($template_content) . "', user_id = '".$user_id."'";
		if($this->db->simple_query($sql))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function delete_template($template_id)
	{
		$sql = "delete from ehm_pha_templates where id='". $template_id ."'";
		if($this->db->simple_query($sql))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function update_template($t_id, $t_name, $t_content, $user_id)
	{
		if($this->session->userdata('role') == "admin")
		{
			$sql = "update ehm_pha_templates set t_name = '".mysql_real_escape_string($t_name)."', t_content = '". mysql_real_escape_string($t_content) ."' where id = '".$t_id."' and user_id = '". $user_id ."'";
		}
		else
		{
			$sql = "update ehm_pha_templates set t_name = '".mysql_real_escape_string($t_name)."', t_content = '". mysql_real_escape_string($t_content) ."' where id = '".$t_id."'";
		}
		if($this->db->simple_query($sql))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	public function add_count($user_id,$id)
	{
		
		$sql = "update ehm_pha_templates set  q_hit =q_hit +1 where id = '".$id."'  and user_id = '". $user_id ."'";
		
		if($this->db->simple_query($sql))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
			
				
	}
	public function list_templates($user_id)//, $limit = '20', $offset = '0')
	{
		if($this->session->userdata('role') == "admin")
		{
			if ($query = $this->db->query("select a.*,b.username from ehm_pha_templates a, ehm_pha_user b where a.user_id = b.id order by a.q_hit desc")):
				return $query->result(); // Return value is an objected matrix
			else:
				return FALSE;
			endif;
		}
		elseif($this->session->userdata('role') == 'user')
		{
			if ($query = $this->db->query("select * from ehm_pha_templates where user_id = ".$user_id." order by q_hit desc")):// limit ".$offset.", ". $limit)):
				return $query->result(); // Return value is an objected matrix
			else:
				return FALSE;
			endif;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function get_template($t_id)
	{
		$sql = "select * from ehm_pha_templates where id = '". $t_id ."'";
		$query = $this->db->query($sql);
		$result = $query->result();
		return json_encode($result[0]);
	}
}

?>