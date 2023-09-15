<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Admin_Controller {
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Admin - Logout";
		$this->load->library("Auth");
		$this->load->library("Session");
	}

	public function index(){
		$session_admin = $this->session->userdata('admin');
		/*$act = array();
		$act["module"] = "logout";
		$act["act_type"] = "user_logout";
		$act["act"] = "logout";
		$act["act_action"] = "user_logout";
		$act["act_status"] = "logout";
		$act["act_key"] = "user_id";
		$act["act_value"] = '1';
		$act["act_data"] = json_encode($session_admin);
		$this->acts_model->save_act($act)*/;
		
		$this->auth->logout();
		
		/*if (isset($_SERVER['HTTP_COOKIE'])){
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach ($cookies as $cookie){
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time() - 1000);
				setcookie($name, '', time() - 1000, '/');
			}
		}*/
		
		redirect($this->admin_url());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin/logout.php */