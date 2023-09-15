<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller {
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Admin";
		$this->load->library("Auth");
		$this->load->model('users_model');
	}
	var $session_expire	= 6000;

	public function index(){
	
		//check if user is logged in
		$is_admin_logged_in = $this->auth->is_admin_logged_in(false, false);
		//$is_user_logged_in = $this->auth->is_user_logged_in(false, false);
		
		if($is_admin_logged_in/* || $is_user_logged_in*/){
			
			redirect(admin_url("dashboard"));
			
		}else{//ask to login in not logged in
			
			$this->load->library('form_validation');
			//$this->load->model(array('Option_model', 'Category_model'));
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
			$data['username']				= '';
			$data['password']				= '';
			
			$this->form_validation->set_rules('username', 'lang:sku', 'trim|required');
			$this->form_validation->set_rules('password', 'lang:sku', 'trim|required');
		
			if ($this->form_validation->run() == FALSE){
				$this->data['title'] = '';
				$this->data['session'] = $this->session;
				$this->data['login_type'] = 'admin';
				
				$this->load->view(admin_view('base/login_page'), $this->data);
			}else{
				
				//try to login
				$username = trim($this->input->post('username'));
				$password = trim($this->input->post('password'));
				
				//Get user details
				$user_details = $this->users_model->get_by_username($username);
				if(!$user_details){
					error_msg("No user found of this username.", admin_url("login"));
				}
				
				$keep_login = $this->input->post('keep_login') ? true : false;
				if($user_details->user_type != $this->users_model->super_admin_type()){
					
					$result = (array)$this->users_model->get_data_by_username($username, $password);
					if($result){
						$admin['admin']						= array();
						$admin['admin']['id']				= $result['id'];
						$admin['admin']['roles'] 			= $result['roles'];
						$admin['admin']['username'] 			= $result['username'];
						$admin['admin']['first_name']		= $result['first_name'];
						$admin['admin']['last_name']			= $result['last_name'];
					
						$admin['admin']['useremail']			= $result['useremail'];
						$admin['admin']['contact_number']	= $result['contact_number'];
						$admin['admin']['user_type']			= $result['user_type'];
						$admin['admin']['job_profile']			= $result['job_profile'];
						$admin['admin']['profile_thumb']		= $result['profile_thumb'];
						$admin['admin']['profile_pic']		= $result['profile_pic'];
						$admin['admin']['expire'] 			= time()+$this->session_expire;
						
						$this->load->model('roles_model');
						$admin['admin']['permissions']		= $this->roles_model->get_user_permissions($result['job_profile']);
						$admin['admin']['fields']		= $this->roles_model->get_user_fields($result['job_profile']);
						$admin['admin']['actions']		= $this->roles_model->get_user_actions($result['job_profile']);
						$this->session->set_userdata($admin);
						$login_admin = $admin;
					}else{
						error_msg("Username or Password doesn't match.", admin_url("login"));
					}	
				}else{
					$login_admin = $this->auth->login_admin($username, $password, $keep_login);					
				}
				
				if($login_admin){//if admin login send to dashboard
					$session_admin = $this->session->userdata('admin');
					
					// $act = array();
					// $act["module"] = "login";
					// $act["act_type"] = "user_login";
					// $act["act"] = "login";
					// $act["act_action"] = "user_login";
					// $act["act_status"] = "login";
					// $act["act_key"] = "user_id";
					// $act["act_value"] = trim($login_admin);
					// $act["act_data"] = json_encode($session_admin);
					// $this->acts_model->save_act($act);
					
					redirect(admin_url("dashboard"));
				}else{
					error_msg("Username or Password doesn't match.", admin_url("login"));
				}
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */