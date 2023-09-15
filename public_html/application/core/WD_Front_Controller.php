<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WD_Front_Controller extends WD_Controller {
	
	protected $breadcrumbs;
	
	//data array
	protected $data;
	protected $current_link;
	protected $form;
	
	protected $add_js;
	protected $add_css;

	protected $site_title;
	protected $theme_title;
	protected $theme_dir;
	protected $assets_dir;
	
	
	public function __construct(){
		parent::__construct();
		
		//pass session to view files
		$this->load->library('session');
		$this->data['session'] 	= 	$this->session;
		
		$this->data['page'] 		= 'default';
		
		$this->data["breadcrumbs"]	 	= $this->breadcrumb(array("/"=>"Home"));
		
		$this->_per_page				= 16;
		
		$this->data['is_profile_area'] = false;
		
		$this->data['meta_title'] = "";
		$this->data['meta_description'] = "";
		$this->data['meta_keywords'] = "";

		$this->data['wd_includes'] = false;
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */