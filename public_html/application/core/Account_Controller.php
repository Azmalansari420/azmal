<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Controller extends WD_Front_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->helper('front_helper');

		//Theme Setup
		{
			$this->load->library('theme');
			$_theme = $this->theme;

			$this->data['_theme'] = $_theme;

			$this->site_title = $this->theme->get_site_title();
			//Default social card
			$this->data['social_card_image'] = home_url($this->theme->get_social_card_image());
		}	
		
		$this->_per_page = 16;

		$this->data['site_title'] = $this->site_title;

		$this->load->library('session');
		$this->data['session'] = $this->session;
		
		/*$this->load->library('Auth');
		if($this->session->userdata('admin')){
			redirect(base_url('admin'));
		}*/

		//////////////////////////////////////////////////////////////////////////////////////////
		////////////// Validate Account /////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////
	
		
		//check if the page is not login or logout
		if( ($this->uri->segment(2)!="login" && $this->uri->segment(2)!="logout") ){
			
			if($this->session->userdata('customer')){
				//admin is logged in so let him do anything :)
				//redirect(admin_url("dashboard"));
				//exit;
			}else{
				//echo base_url('vendor/'.$this->uri->segment(2));exit;
				//redirect (base_url('vendor/'.$this->uri->segment(2)));
				//redirect(base_url("vendor/login"));
				//exit;
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////
		
		$this->data['page'] 		= 'default';
		
		//$this->data['this'] 		= $this;
		
		$this->data['is_profile_area'] = true;
	}
	
	function page_view($path=false){
		if(!$path) return false;
		
		//return "/theme/" . $this->theme_dir . "/" . rtrim(ltrim($path, "/"), "/");
		return $this->theme->get_view($path);
	}
	
	function page_header(){
		return $this->page_view("common/header");
	}
	
	function page_footer(){
		return $this->page_view("common/footer");
	}
	
	function page($view = false){
		
		//prepare page view
		if($this->config->item('ci_form')){
			$this->data['form'] = $this->config->item('ci_form');
			$this->config->set_item('ci_form', '');
		}
		
		$this->load->view($this->page_header(), $this->data);
		if($view){
			$this->load->view($this->page_view($view), $this->data);
		}
		$this->load->view($this->page_footer());
	}
	
	public function breadcrumb($crumbs = NULL){
		if(!is_null($crumbs)){
			foreach($crumbs as $crumb => $link){
				$this->breadcrumbs[$crumb] = $link;
			} 
		}
		$this->data['breadcrumbs'] = $this->breadcrumbs;
	}

	public function process_includes($page){
		$this->load->model('cms/page_model');
		$includes = $this->page_model->process_includes($page);

		$this->theme->process_includes($includes);

		$this->data = array_merge($this->data, $includes);
	}
	
	function user($data=false){
		$user = $this->session->userdata('customer');
		if($data){
			if(isset($user[$data])){
				return $user[$data];
			}
		}
		return $this->session->userdata('customer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */