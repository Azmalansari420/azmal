<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends WD_Admin_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->helper('admin_helper');
		$this->load->model('acts_model');
		$this->load->model('users_model');

		$this->load->library('wd_admin');
		
		//load app_config
		$this->load->config('app_config');
		$this->admin_dir	= $this->config->item('admin_dir');
		$this->admin_url	= $this->config->item('admin_url');
		
		//////////////////////////////////////////////////////////////////////////////////////////
		////////////// Validate Admin /////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////
		
		//Is admin or is user
		$is_user = false;
		$is_admin = false;
		if($this->session->userdata('admin')){
			$this->_user = $this->session->userdata('admin');
			if($this->_user['user_type'] == $this->users_model->super_admin_type()){
				
				$is_admin = true;
				$this->_user_type = 'admin';
			}else{
				
				$is_user = true;
				$this->_user_type = 'user';
			}
		}
		
		if(($this->uri->segment(1) == $this->admin_url) && $this->uri->segment(2)==''){
			$this->load->library('Auth');
			if($is_user){
				if($this->auth->is_user_logged_in(false, false)){
					redirect(admin_url("dashboard"));
					exit;
				}else{//admin is not logged in so redirect to login
					redirect(admin_url("login"));
					exit;
				}
			}else{
				if($this->auth->is_admin_logged_in(false, false)){
					redirect(admin_url("dashboard"));
					exit;
				}else{//admin is not logged in so redirect to login
					redirect(admin_url("login"));
					exit;
				}
			}	
		}
		
		//check if the page is not login or logout
		if( ($this->uri->segment(2)!="login" && $this->uri->segment(2)!="logout") ){
			$this->load->library('Auth');
			if($is_user){
				if($this->auth->is_user_logged_in(false, false)){
				}else{
					redirect(admin_url("login"));
					exit;
				}
			}else{
				if($this->auth->is_admin_logged_in(false, false)){
				}else{
					redirect(admin_url("login"));
					exit;
				}
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////
		$dat = (array)$this->session->userdata('admin');
		
		////////Checking user has access to a particular module or not
		$modules = $this->config->item('modules');

		//if($this->uri->segment(2)!=''){//$this->uri->segment(1) == "") && 
			if($is_user){
				$this->load->library('Auth');
				
				$roles = $dat['permissions'];
				//validate page access
				if( ($this->uri->segment(2)!="login" && $this->uri->segment(2)!="logout" && $this->uri->segment(2)!="dashboard") && $this->uri->segment(3)!="alerts" && !$this->input->is_ajax_request() ){
					$current_segments_path = '';
					if($this->uri->segment(2)){
						$current_segments_path = $this->uri->segment(2);
					}

					if($this->uri->segment(3)){
						$current_segments_path .= '/'.$this->uri->segment(3);
					}

					if(!in_array($current_segments_path, $roles)){
						error_msg("Unauthorised Access!", admin_url("dashboard"));
					}
				}
				
				$menu = array();
				if($roles){
					foreach($modules as $key => $module){
						if(in_array($module['path'], $roles)){
							$_module = $module;
							unset($_module['childs']);
							$menu[$key] = $_module;
						}
						
						$is_childs = false;

						if(isset($module['childs'])){
							foreach($module['childs'] as $child){
								if(in_array($child['path'], $roles)){
									$menu[$key]['childs'][] = $child;
								}
							}
						}
					}	
				}
				$this->data['modules'] = $menu;

			}elseif($is_admin){
				//Modules
				$this->data['modules'] = $modules;
			}
		//}

		$this->data['site_title']		= $this->config->item('site_title');
		
		$this->data["breadcrumbs"]	 	= $this->breadcrumb(array("dashboard"=>"Dashboard"));
		
		{//form variable used in header view to display form
			$this->data['form']						= '';
			//default back button
			//can be override by the cancelLink() method
			$this->cancel_link						= admin_url($this->data['_module'] . "/" .$this->router->class);
		}
	
		//grid variable used in header view to display form
		$this->data['grid']				= '';
		
		//default add new item link
		$this->data['add_link']			= '';
		
		//admin view variables
		$this->data['add_css']			= $this->add_css();
		$this->data['add_js']			= $this->add_js();
		$this->data['admin_url']		= admin_url();
		$this->data['admin_view']		= $this->admin_view();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */