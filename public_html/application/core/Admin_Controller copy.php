<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends WD_Controller {

	protected $breadcrumbs;
	
	protected $_user;
	protected $_user_type;
	
	//data array
	protected $data;
	protected $current_link;
	protected $form;
	
	//form elements
	protected $elements;
	protected $form_data;
	protected $append_form_body;
	
	//admin view variables
	protected $add_css;
	protected $add_js;
	protected $admin_url;
	protected $admin_view;
	
	protected $modules;
	
	//config group settings
	//types of config groups
	protected $_config_group_types;
	
	
	public function __construct(){
		parent::__construct();

		$this->load->helper('admin_helper');
		$this->load->model('acts_model');
		$this->load->model('users_model');
		
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

		//////////////////////////////////////////////////////////////////////////////////////////
		//Modules
		//$this->data['modules'] = $this->config->item('modules');

		//current link
		if(!$this->currentLink()){
			$this->data["_link"] 			= base_url().$this->uri->uri_string;
		}else{
			$this->data["_link"] 			= base_url().$this->currentLink();
		}
		
		//get the module name (name of the module folder)
		$this->data['_module'] = $this->_module = $this->uri->segment(2);
		
		//get the action name of the module)
		$this->data['_action'] = $this->_action = $this->uri->segment(3);
		
		//module links
		//$this->data["_menu"] 			= $this->adminMenu();
		//$this->data["_sub_menu"] 		= $this->adminMenu($this->data['_module']);
		
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
		
		//config group types
		$this->_config_group_types = array("frontend"=>"Frontend", "system"=>"System");
		//input types for config
		$this->_input_type_options = array(""=>"Select Input Type", "text"=>"Input", "select"=>"Dropdown", "textarea"=>"Textarea", "texteditor"=>"Texteditor", "yesno"=>"Yes/No");
		

		//pass session to view files
		$this->load->library('session');
		$this->data['session'] = 	$this->session;
	}
	
	///////////////////////////////////////////////////////////////
	//////////////// ADMIN FUNCTION ///////////////////////////////
	///////////////////////////////////////////////////////////////

	function admin_url($path=false){
		$url = rtrim(admin_url(), "/")."/";//rtrim(base_url(), "/") . "/".$this->admin_url."/";
		if($path){
			$url .= rtrim(ltrim($path, "/"), "/") . "/";
		}
		return $url;
	}

	function add_js($path=false){
		$url = rtrim(base_url(), "/") . "/js/admin/";
		if($path){
			$url .= rtrim(ltrim($path, "/"), "/");
		}
		return $url;
	}

	function add_css($path=false){
		$url = rtrim(base_url(), "/") . "/style/admin/";
		if($path){
			$url .= rtrim(ltrim($path, "/"), "/");
		}
		return $url;
	}
	
	function admin_view($path=false){
		if(!$path) return false;
		
		return $this->admin_dir."/" . rtrim(ltrim($path, "/"), "/");
	}
	
	function admin_header(){
		return $this->admin_view("common/header");
	}
	
	function admin_footer(){
		return $this->admin_view("common/footer");
	}
	
	function Page($view = false){
		
		//prepare page view
		if($this->config->item('ci_form')){
			$this->data['form'] = $this->config->item('ci_form');
			$this->config->set_item('ci_form', '');
		}
		
		$this->load->view($this->admin_header(), $this->data);
		if($view){
			$this->load->view($this->admin_view($view), $this->data);
		}
		$this->load->view($this->admin_footer());
	}
	//////////////////////////////////////////////////////////////////////////////////////
		
	//admin breadcrumbs
	public function breadcrumb($crumbs = NULL){
		if(!is_null($crumbs)){
			foreach($crumbs as $crumb => $link){
				$this->breadcrumbs[$crumb] = $link;
			} 
		}
		$this->data['breadcrumbs'] = $this->breadcrumbs;
	}
	
	//menu
	public function adminMenu($module=false){
		$modules = unserialize(file_get_contents(APPPATH.'controllers/'.$this->admin_url."/menu.php"));				
		if($module){
			return isset($modules[$module]['submenu']) ? $modules[$module]['submenu'] : false;
		}
		return $modules;				
	}
	
	//return url string without method or index function
	public function currentLink(){
		$str = explode("/", $this->uri->uri_string);
		if(count($str)>2){
			$url = array();
			for($i = 0; $i<3; $i++){
				if($str[$i] != 'index'){
					$url[] = $str[$i];
				}
			}
			return implode("/",$url);
		}
		return false;
	}
	
	//request URL
	public function referer_uri(){
		return $ref_uri = $_SERVER['HTTP_REFERER'];
	}
	
	public function removeButton($index, $remove_link){
		$this->remove_index = $index;
		$this->remove_link = $remove_link;
	}
	
	public function cancelLink($link){
		$this->cancel_link = $link;
	}
	
	public function createForm($form_title='', $method='POST'){
		
		{
			$this->load->helper('form');
			
			//form elements are stored as array in elements object inside controller before calling the createForm function.
			$data['form_elements'] 				= $this->elements;
			$data['form_data'] 					= $this->form_data;
			$data['append_form_body']			= $this->append_form_body;
			
			$data['form_title'] = $form_title;
			$data['form_method'] = $method;
			
			$data['remove_index'] = isset($this->remove_index) ? $this->remove_index : "" ;
			$data['remove_link'] = isset($this->remove_link) ? $this->remove_link : "" ;
			
			$data['cancel_link'] = isset($this->cancel_link) ? $this->cancel_link : "";
		}
		
		{//validate form
			$this->load->library('form_validation');
			$tiny_mce = false;
			foreach($data['form_elements'] as $element => $f_d){
				//if multiple column form row
				if($element=='col_grid'){
					$elements = $f_d["elements"];
					foreach($elements as $element_name => $_element){
						if(isset($_element["type"]) && $_element["type"]=='texteditor'){
							$tiny_mce = true;
						}
						if(isset($_element['validation'])){
							$this->form_validation->set_rules($element_name, $_element['label'], $_element['validation']);
						}
					}
				}else{
					if(isset($f_d["type"]) && $f_d["type"]=='texteditor'){
						$tiny_mce = true;
					}
					if(isset($f_d['validation'])){
						$this->form_validation->set_rules($element, $f_d['label'], $f_d['validation']);
					}
				}
			}	
		}
		
		{//prepare tinymce
			if($tiny_mce){
				$this->data['js'] = array("tiny_mce/tiny_mce.js", "tiny_mce_setup.js");
			}
		}
		
		if ($this->form_validation->run() == FALSE){
			$this->data['form'] = $this->load->view($this->admin_view("base/form/form"), $data, TRUE);
			return true;
		}else{
			$form = false;
		}
	}
	
	public function createElement($element_name=false, $attributes=false){
		if( (!$element_name) || (!$attributes)) return false;
		
		$this->load->helper('form');
		
		if($element_name == 'col_grid'){
			$_attributes = $attributes;
			$data['_element'] 			= $element_name;
			$data['_attributes'] 		= $attributes;
			$form_data					= array();
			foreach($_attributes as $element_title => $attribute){
				$form_data						= array($element_title => $attribute['value']);
			}
			return $this->load->view($this->admin_view("base/form/form_row"), $data, TRUE);
			
		}else{
			$data['_element'] 			= $element_name;
			$data['_attributes'] 		= $attributes;
			$data['form_data']			= array($element_name => $attributes['value']);
			return $this->load->view($this->admin_view("base/form/form_row"), $data, TRUE);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */