<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hive extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			$this->load->helper('url');
			redirect($this->config->base_url() . 'index.php/user/login/');
		}
	}
	
	public function DropDatabase($db_name)
	{
		$this->load->model('hive_model','hive');
		echo $this->hive->drop_database($db_name);
		$this->load->helper('url');
		redirect($this->config->base_url(), "0", "refresh");
	}
	
	public function CreateDatabase()
	{
		$this->load->model('hive_model','hive');
		$db_name = $this->input->post('db_name');
		$db_comment = $this->input->post('db_comment');
		
		$this->load->helper('url');
		echo $this->hive->create_database($db_name, $db_comment);
		redirect($this->config->base_url(), "3", "refresh");
	}
}

?>