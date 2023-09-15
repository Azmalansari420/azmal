<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Front_Controller {
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Logout";
		//$this->load->library("Auth");
		$this->load->library("Session");
	}

	public function index(){
		//$this->auth->logout();
		$customer = $this->session->unset_userdata('customer');
		
		msg('Logout successfully.', base_url());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin/logout.php */