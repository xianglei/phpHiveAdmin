<?php

class History extends CI_Controller
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
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);

		#Generate Navigation top bar
		$data['common_hql_query'] = $this->lang->line('common_hql_query');
		$data['common_etl'] = $this->lang->line('common_etl');
		$data['common_hdfs_browser'] = $this->lang->line('common_hdfs_browser');
		$data['common_history'] = $this->lang->line('common_history');
		$data['common_log_out'] = $this->lang->line('common_log_out');
		$data['common_user_admin'] = $this->lang->line('common_user_admin');
		$data['common_templates'] = $this->lang->line('common_templates');
		$this->load->view('nav_bar',$data);

		#Generate div container
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$this->load->view('history_menu', $data);
		
		$this->load->model('history_model', 'history');
		$this->load->library('pagination');
		$config['base_url'] = $this->config->base_url() . 'index.php/history/index/';
		$config['total_rows'] = $this->history->count_history($this->session->userdata('role'));
		$config['per_page'] = 30;
		$offset = $this->uri->segment(3,0);
		if($offset == 0):
			$offset = 0;
		else:
			$offset = ($offset / $config['per_page']) * $config['per_page'];
		endif;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		
		$data['common_file_name'] = $this->lang->line('common_file_name');
		$data['common_file_content'] = $this->lang->line('common_file_content');
		$data['common_file_size'] = $this->lang->line('common_file_size');
		$data['common_delete'] = $this->lang->line('common_delete');
		if($this->session->userdata('role') == "admin")
		{
			$data['results'] = $this->history->get_history_list($config['per_page'], $offset);
		}
		else
		{
			$data['results'] = $this->history->get_history_list_by_user($this->session->userdata('username'), $config['per_page'], $offset);
		}
		$this->load->view('history_list', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');

		#Generate Footer
		$this->load->view('footer');
	}
	
	public function BatchDropHistory()
	{
		$history_ids = $this->input->post('history_id');
		$this->load->model('history_model', 'history');
		$this->history->batch_drop_history($history_ids);
		$this->load->helper('url');
		redirect($this->config->base_url(). 'index.php/history/index/', "0", "refresh");
	}
}

?>