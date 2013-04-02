<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller
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

		#Generate Database list on left area
		$this->load->model('hive_model','hive');
		$data['db_list'] = $this->hive->show_databases();
		$this->load->view('db_list',$data);

		#Generate add database on right area
		$data['common_add_database'] = $this->lang->line('common_add_database');
		$data['common_add_schema'] = $this->lang->line('common_add_schema');
		$data['common_comment'] = $this->lang->line('common_comment');
		$data['common_submit'] = $this->lang->line('common_submit');
		$this->load->view('create_database',$data);

		#Generate Gauge meters
		$json = json_decode($this->hive->get_cluster_status());
		$data['maxMapTasks'] = $json->maxMapTasks;
		$data['maxReduceTasks'] = $json->maxReduceTasks;
		$data['mapTasks'] = $json->mapTasks;
		$data['reduceTasks'] = $json->reduceTasks;
		$data['common_mr_slots_used'] = $this->lang->line('common_mr_slots_used');
		$data['common_map_slots'] = $this->lang->line('common_map_slots');
		$data['common_reduce_slots'] = $this->lang->line('common_reduce_slots');
		$data['common_using'] = $this->lang->line('common_using');
		$data['common_value'] = $this->lang->line('common_value');
		$this->load->view('mapred_slot_realtime', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');

		#Generate Footer
		$this->load->view('footer');
	}
	
	public function GetClusterStatus($key = 'state')
	{
		$this->load->model('hive_model','hive');
		$json = $this->hive->get_cluster_status();
		echo $json;
	}
	
	public function Login()
	{
		$this->lang->load('commons');
		$data['lang'] = $this->lang->line('common_lang_set');
		$data['title'] = $this->lang->line('common_title');
		$this->load->view('header',$data);
		$this->load->view('login');
		$this->load->view('footer');
	}
	public function LoginAction()
	{
		$this->load->database();
		$this->load->model('user_model','user');
		$this->user->login_action(); 
	}

	public function Query($db_name = 'default', $table_name = '')
	{
		#Generate Header
		$this->lang->load('commons');
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

		$this->load->view('div_fluid');
		$this->load->view('div_row_fluid');

		#Generate left table list on left area
		$this->load->model('hive_model','hive');
		$data['var_db_name'] = $db_name;
		$data['table_list'] = $this->hive->show_tables($db_name);
		$this->load->view('table_list',$data);
		
		#generate query strict on right area
		$data['common_table_name'] = $this->lang->line('common_table_name');
		$data['common_back'] = $this->lang->line('common_back');
		$data['common_alter_table'] = $this->lang->line('common_alter_table');
		$data['common_load_data'] = $this->lang->line('common_load_data');
		$data['common_clone_table'] = $this->lang->line('common_clone_table');
		$data['common_table_detail'] = $this->lang->line('common_table_detail');
		$data['common_drop_table'] = $this->lang->line('common_drop_table');
		$data['table_name'] = $table_name;
		#Get table detail describe;
		$array_desc_desc = $this->hive->get_table_cols($db_name,$table_name);
		
		$data['column_name'] = $array_desc_desc['name'];
		$data['column_type'] = $array_desc_desc['type'];
		$data['column_comment'] = $array_desc_desc['comment'];
		$data['example_data'] = $this->hive->get_example_data($db_name, $table_name, 2);
		$data['common_hql_validator'] = $this->lang->line('common_hql_validator');
		$data['common_submit'] = $this->lang->line('common_submit');
		$data['common_close'] = $this->lang->line('common_close');
		$data['common_cli_done'] = $this->lang->line('common_cli_done');
		#load query template 
		
		$this->load->model('templates_model', 'templates');
		$data['templates_list'] = $this->templates->list_templates($this->session->userdata('id'));		
				
		$this->load->view('sql_query',$data);
		$this->load->view('get_query_plan_modal', $data);
		$this->load->view('sql_query_status_modal', $data);

		$this->load->view('div_end');
		$this->load->view('div_end');
		
		
		
		#Generate Footer
		$this->load->view('footer');
	}
	
	public function GetHiveUdfs($db_name = 'default', $table_name = '')
	{
		$this->load->model('hive_model','hive');
		$html = $this->hive->desc_table_hiveudfs($db_name, $table_name);
		echo $html;
	}
	
	public function CreateDatabase($db_name = "aaaaa", $db_desc = "teaaast")
	{
		$this->load->model('hive_model', 'hive');
		$html = $this->hive->create_database($db_name);
		echo $html;
	}
	
	public function GetQueryPlan()
	{
		$sql = $this->input->post('sql');
		$this->load->model('hive_model', 'hive');
		$html = $this->hive->get_query_plan($sql);
		echo $html;
	}
	
	public function SqlQuery()
	{
		set_time_limit(0);
		$sql = $this->input->post('sql');
		$db_name = $this->input->post('db_name');
		$sql = "USE ".$db_name. ";".$sql;
		$finger_print = $this->input->post('finger_print');
		$this->load->model('hive_model', 'hive');
		if($this->session->userdata('role') == "admin")
		{
			$this->hive->cli_query($sql, $finger_print);
		}
		else
		{
			$this->load->model('utilities_model', 'utils');
			if($this->utils->auth_sql($sql))
			{
				$this->hive->cli_query($sql, $finger_print);
			}
			else
			{
				echo "No privileges";
			}
		}
	}
	
	public function GetFingerPrint()
	{
		$this->load->model('utilities_model', 'utils');
		echo $this->utils->make_finger_print();
	}
	
	public function GetQueryStatus()
	{
		$finger_print = $this->uri->segment(3,0);
		//echo $finger_print . '<br /><br />';
		$this->load->model('hive_model', 'hive');
		$str = $this->hive->get_query_status($finger_print);
		echo $str;
	}
	
	
	public function GetResult()
	{
		$finger_print = $this->uri->segment(3,0);
		
		$this->load->model('hive_model', 'hive');
		$data_matrix = $this->hive->get_result($finger_print);
		$this->load->model('utilities_model', 'utils');
		$sql_columns = $this->utils->split_sql_cols($finger_print);
		
		#Generate Header
		$this->lang->load('commons');
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
		
		$data['common_download_result'] = $this->lang->line('common_download_result');
		$data['sql_columns'] = $sql_columns;
		$data['data_matrix'] = $data_matrix;
		$this->load->view('get_result', $data);
		
		$this->load->view('div_end');
		$this->load->view('div_end');

		#Generate Footer
		$this->load->view('footer');
	}
	
	public function GetResultSize()
	{
		$finger_print = $this->uri->segment(3,0);
		$this->load->model('utilities_model', 'utils');
		$filesize = $this->utils->get_csv_filesize($finger_print);
		echo $filesize;
	}
	
	public function DownloadResult()
	{
		$finger_print = $this->uri->segment(3,0);
		$this->load->model('utilities_model', 'utils');
		$this->utils->download_csv($finger_print);
	}
}