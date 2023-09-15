<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WD_Admin_Controller extends WD_Controller {

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
		
		$this->data["breadcrumbs"]	 	= $this->breadcrumb(array("dashboard"=>"Dashboard"));
		
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
			$this->data['pop_form'] = $this->config->item('pop_form');
			$this->data['pop_form_title'] = $this->config->item('pop_form_title');

			$this->config->set_item('ci_form', '');
			$this->config->set_item('pop_form', '');
			$this->config->set_item('pop_form_title', '');
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