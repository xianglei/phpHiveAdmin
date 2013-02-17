<?php

class Hdfs extends CI_Controller
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
	
	public function Index()
	{
		#Generate Header
		$this->lang->load('commons');
		$this->lang->load('errors');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		
		#Generate Navigation top bar
		$data['common_hql_query'] = $this->lang->line('common_hql_query');
		$data['common_etl'] = $this->lang->line('common_etl');
		$data['common_cluster_status'] = $this->lang->line('common_cluster_status');
		$data['common_hdfs_browser'] = $this->lang->line('common_hdfs_browser');
		$data['common_meta_summury'] = $this->lang->line('common_meta_summury');
		$data['common_history'] = $this->lang->line('common_history');
		$data['common_log_out'] = $this->lang->line('common_log_out');
		$data['common_user_admin'] = $this->lang->line('common_user_admin');
		$this->load->view('nav_bar',$data);

		#Generate div container
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');

		#Generate left table list on left area
		$this->load->model('hive_model','hive');
		$data['db_list'] = $this->hive->show_databases();
		$this->load->view('db_list',$data);
		
		$data['common_file_size'] = $this->lang->line('common_file_size');
		$data['common_file_property'] = $this->lang->line('common_file_property');
		$data['common_file_group'] = $this->lang->line('common_file_group');
		$data['common_file_user'] = $this->lang->line('common_file_user');
		$data['common_file_time'] = $this->lang->line('common_file_time');
		$data['common_file_name'] = $this->lang->line('common_file_name');
		$data['common_back_to_root'] = $this->lang->line('common_back_to_root');
		$data['common_back'] = $this->lang->line('common_back');
		$this->load->model('hdfs_model', 'hdfs');
		$path = $this->uri->segment(3,0);
		$path = base64_decode($path);
		if($path == "")
		{
			$path = "/";
		}
		$hdfs = $this->hdfs->read_hdfs($path);
		$data['hdfs_matrix'] = $hdfs;
		
		$this->load->view('hdfs_table', $data);
		
		
		#Generate div end
		$this->load->view('div_end');
		$this->load->view('div_end');

		#Generate Footer
		$this->load->view('footer');
	}
}

?>