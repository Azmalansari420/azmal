<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Accounts_Controller extends WD_Controller {	

	protected $_account_dir;	

	public function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->helper('front_helper');
		
		$this->data['session'] = $this->session;
		/*$this->load->library('Auth');
		if($this->session->userdata('admin')){
			redirect(member_url('admin'));
		}*/
		

		//////////////////////////////////////////////////////////////////////////////////////////
		////////////// Validate Account /////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////
		if((($this->uri->segment(1) == "account") ||  $this->uri->segment(1)=='') && $this->uri->segment(2)==''){
			
			if($this->session->userdata('account')){
				//admin is logged in so rediect to dashboard
				//redirect(member_url());
				//exit;
			}else{//admin is not logged in so redirect to login
				redirect(base_url());
				exit;
			}
		}
		
		//check if the page is not login or logout
		if( ($this->uri->segment(2)!="login" && $this->uri->segment(2)!="logout" && $this->uri->segment(2)!="signup" && $this->uri->segment(2)!="reset_password" && $this->uri->segment(2)!="c" && $this->uri->segment(2)!="new_password") ){
			if($this->session->userdata('account')){
				//admin is logged in so let him do anything :)
				//redirect(admin_url("dashboard"));
				//exit;
			}else{
				redirect(base_url());
				exit;
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////
		
		$this->data['page'] 		= 'default';
		$this->data['this'] 		= $this;
		$this->data['is_profile_area'] = true;
		$profile = $this->session->userdata('account');
		
		$this->theme = $this->config->item('theme');
		$this->data['theme'] = $this->theme;
		
		$this->data['site_title'] = $this->config->item('site_title');

		$this->data['account_label'] 	= $this->config->item('account_label');
		$this->data["breadcrumbs"]	 	= $this->breadcrumb(array("dashboard"=>"Dashboard"));
		$this->_account_dir 			= $this->config->item('account_dir');
	}
	

	function page($view = false){
		//prepare page view
		if($this->config->item('ci_form')){
			$this->data['form'] = $this->config->item('ci_form');
			$this->config->set_item('ci_form', '');
		}
		
		$this->load->view("theme/".$this->theme.'/common/header', $this->data);
		if($view){
			$this->load->view("theme/".$this->theme."/".$this->_account_dir.'/'.$view, $this->data);
		}
		$this->load->view("theme/".$this->theme.'/common/footer', $this->data);
	}
	

	function account_data($data=false){
		$account_data = $this->session->userdata('account');
		if($data){
			if(isset($account_data[$data])){
				return $account_data[$data];
			}
		}
		return $this->session->userdata('account');
	}
	
	
	public function breadcrumb($crumbs = NULL){
		if(!is_null($crumbs)){
			foreach($crumbs as $crumb => $link){
				$this->breadcrumbs[$crumb] = $link;
			} 
		}
		$this->data['breadcrumbs'] = $this->breadcrumbs;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */