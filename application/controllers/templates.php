<?php

class Templates extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('login') || $this->session->userdata('login') == FALSE)
		{
			$this->load->helper('url');
			redirect($this->config->base_url() . 'user/login/');
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
		$data['common_templates'] = $this->lang->line('common_templates');
		$this->load->view('nav_bar',$data);

		#Generate div container
		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');
		
		$data['common_templates_list'] = $this->lang->line('common_templates_list');
		$data['common_templates_user_list'] = $this->lang->line('common_templates_user_list');
		$this->load->view('templates_nav_bar', $data);
		
		$this->load->model('templates_model', 'templates');
		$data['templates_list'] = $this->templates->list_templates($this->session->userdata('id'));
		$data['common_templates_admin'] = $this->lang->line('common_templates_admin');
		$data['common_templates_add'] = $this->lang->line('common_templates_add');
		$data['common_templates_update'] = $this->lang->line('common_templates_update');
		$data['common_templates_delete'] = $this->lang->line('common_templates_delete');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_templates_name'] = $this->lang->line('common_templates_name');
		$data['common_templates_content'] = $this->lang->line('common_templates_content');
		$this->load->view('templates_list', $data);
		$this->load->view('create_template_modal', $data);
		$this->load->view('update_template_modal', $data);
		$this->load->view('drop_template_modal', $data);
		
		#Generate div end
		$this->load->view('div_end');
		$this->load->view('div_end');

		#Generate Footer
		$this->load->view('footer');
	}
	
	public function CreateTemplatesAction()
	{
		$role = $this->session->userdata('role');
		$user_id = $this->session->userdata('id');
		$this->load->model('templates_model', 'templates');
		$t_name = $this->input->post('t_name');
		$t_content = $this->input->post('t_content');
		
		if($this->templates->create_template($t_name, $t_content, $user_id))
		{
			echo "Success";
		}
		else
		{
			echo "Failed";
		}
		
		$this->load->helper('url');
		redirect($this->config->base_url() . "templates/index/", "2", "refresh");
	}
	
	public function DropTemplateAction()
	{
		$role = $this->session->userdata('role');
		$user_id = $this->session->userdata('id');
		$t_id = $this->input->post('d_t_id');
		$this->load->model('templates_model', 'templates');
		
		if($this->templates->delete_template($t_id))
		{
			echo "Success";
		}
		else
		{
			echo "Failed";
		}
		
		$this->load->helper('url');
		redirect($this->config->base_url() . "templates/index/", "2", "refresh");
	}
	
	public function GetTemplate($t_id)
	{
		$this->load->model('templates_model', 'templates');
		$json = $this->templates->get_template($t_id);
		echo $json;
	}
	
	public function UpdateTemplateAction()
	{
		$this->load->model('templates_model', 'templates');
		$t_id = $this->input->post('t_id');
		$t_name = $this->input->post('t_name');
		$t_content = $this->input->post('t_content');
		$user_id = $this->input->post('user_id');
		if($this->templates->update_template($t_id, $t_name, $t_content, $user_id))
		{
			echo "Success";
		}
		else
		{
			echo "Failed";
		}
		$this->load->helper('url');
		redirect($this->config->base_url() . "templates/index/", "2", "refresh");
	}
}

?>