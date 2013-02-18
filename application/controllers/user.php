<?php

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function Index()
	{
		if($this->session->userdata('role') == 'user')
		{
			$this->load->helper('url');
			redirect($this->config->base_url() . 'user/changepassword/');
		}
		#Generate Header
		$this->lang->load('commons');
		$this->lang->load('errors');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_username'] = $this->lang->line('common_username');
		$data['common_password'] = $this->lang->line('common_password');
		$data['common_submit'] = $this->lang->line('common_submit');
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
		
		$data['common_user_list'] = $this->lang->line('common_user_list');
		$data['common_update_password'] = $this->lang->line('common_update_password');
		$data['common_password'] = $this->lang->line('common_password');
		$data['common_onlydb'] = $this->lang->line('common_onlydb');
		$data['common_role'] = $this->lang->line('common_role');
		$data['common_user'] = $this->lang->line('common_user');
		$data['common_admin'] = $this->lang->line('common_admin');
		$data['common_reduce'] = $this->lang->line('common_reduce');
		$data['common_description'] = $this->lang->line('common_description');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_add_user'] = $this->lang->line('common_add_user');
		$this->load->view('user_nav_bar', $data);
		
		$this->load->model('user_model', 'user');
		$data['common_update_user'] = $this->lang->line('common_update_user');
		$data['common_drop_user'] = $this->lang->line('common_drop_user');
		$data['common_delete'] = $this->lang->line('common_delete');
		$data['user_list'] = $this->user->get_user_list();
		
		$this->load->view('user_list', $data);
		$this->load->view('create_user_modal', $data);
		$this->load->view('update_user_modal', $data);
		$this->load->view('drop_user_modal', $data);
		
		#Generate div end
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#Generate Footer
		$this->load->view('footer');
	}
	
	public function Login()
	{
		$this->lang->load('commons');
		#Generate Header
		$this->lang->load('commons');
		$this->lang->load('errors');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_username'] = $this->lang->line('common_username');
		$data['common_password'] = $this->lang->line('common_password');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_delete'] = $this->lang->line('common_delete');
		$this->load->view('header',$data);
		
		$this->load->view('login_form', $data);
		
		#Generate Footer
		$this->load->view('footer');
	}
	
	public function LoginAction()
	{
		$this->load->model('user_model', 'user');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$this->user->login_action($username, $password);
		$this->load->helper('url');
		redirect($this->config->base_url());
	}
	
	public function CreateUserAction()
	{
		$this->load->model('user_model', 'user');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$repassword = $this->input->post('repassword');
		$onlydb = $this->input->post('onlydb');
		$role = $this->input->post('role');
		$reduce = $this->input->post('reduce');
		$description = $this->input->post('description');
		
		if($password == $repassword)
		{
			$this->user->create_user($username, $password, $onlydb, $role, $reduce=0, $description);
			echo "Successed";
		}
		else
		{
			echo "Password not matched";
		}
		$this->load->helper('url');
		redirect($this->config->base_url() . 'user/index/', '1', "refresh");
	}
	
	public function DropUserAction()
	{
		$this->load->model('user_model', 'user');
		$user_id = $this->input->post('user_id');
		
		$this->user->drop_user($user_id);
		$this->load->helper('url');
		redirect($this->config->base_url() . 'user/index/', '0', "refresh");
	}
	
	public function UpdateUserAction()
	{
		$this->load->model('user_model', 'user');
		$user_id = $this->input->post('user_id');
		
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$repassword = $this->input->post('repassword');
		$onlydb = $this->input->post('onlydb');
		$role = $this->input->post('role');
		$reduce = $this->input->post('reduce');
		$description = $this->input->post('description');
		
		if($password == $repassword)
		{
			$this->user->update_user($user_id, $username, $password, $onlydb, $role, $reduce="0", $description);
			echo "Successed";
		}
		else
		{
			echo "Password not matched";
		}
		$this->load->helper('url');
		redirect($this->config->base_url() . 'user/index/', '1', "refresh");
	}
	
	public function LogOut()
	{
		$this->load->model('user_model', 'user');
		$this->user->log_out();
		$this->load->helper('url');
		redirect($this->config->base_url(), "0", "refresh");
	}
	
	public function ChangePassword()
	{
		#Generate Header
		$this->lang->load('commons');
		$this->lang->load('errors');
		$data['common_lang_set'] = $this->lang->line('common_lang_set');
		$data['common_title'] = $this->lang->line('common_title');
		$data['common_username'] = $this->lang->line('common_username');
		$data['common_password'] = $this->lang->line('common_password');
		$data['common_submit'] = $this->lang->line('common_submit');
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
		
		$data['common_user_list'] = $this->lang->line('common_user_list');
		$data['common_update_password'] = $this->lang->line('common_update_password');
		$data['common_password'] = $this->lang->line('common_password');
		$data['common_onlydb'] = $this->lang->line('common_onlydb');
		$data['common_role'] = $this->lang->line('common_role');
		$data['common_user'] = $this->lang->line('common_user');
		$data['common_admin'] = $this->lang->line('common_admin');
		$data['common_reduce'] = $this->lang->line('common_reduce');
		$data['common_description'] = $this->lang->line('common_description');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_add_user'] = $this->lang->line('common_add_user');
		$this->load->view('user_nav_bar', $data);
		$this->load->model('user_model', 'user');
		$result = $this->user->get_user($this->session->userdata('id'));
		$data['result'] = $result;
		
		$this->load->view('update_password_form', $data);
		
		#Generate div end
		$this->load->view('div_end');
		$this->load->view('div_end');
		
		#Generate Footer
		$this->load->view('footer');
	}
	
	public function ChangePasswordAction()
	{
		$this->load->model('user_model', 'user');
		$user_id = $this->input->post('user_id');
		$password = $this->input->post('password');
		$repassword = $this->input->post('repassword');
		if($password == $repassword)
		{
			$this->user->update_password($user_id, $password);
			echo "Successed";
		}
		else
		{
			echo "Password not matched";
		}
		$this->load->helper('url');
		redirect($this->config->base_url() . 'user/index/', '1', "refresh");
	}
}

?>