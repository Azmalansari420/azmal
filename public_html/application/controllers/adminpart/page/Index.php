<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Pages";
		$this->breadcrumb(array("page"=>"Pages"));
		$this->load->model("Page_model");
	}
	
	function status_options(){
		return $status_options = array('1'=>"Enable", '2'=>"Unapproved", '0'=>"Disable");
	}
	
	function template_for_options(){
		return $template_for_options = array(1=>"New Register",  2=>"Option Form");
	}

	public function index(){

		$total_rows = $this->db->where("type", "pages")->get($this->Page_model->table_name())->num_rows();
		$query = $this->db->select("*")->where("type", "pages")->from($this->Page_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Pages");
		
		$this->datatable->setColumns(array("name"=>"id", "column"=>"id", "align"=>"left", "width"=>60));
		//$this->datatable->setColumns(array("name"=>"identifier", "column"=>"Page Identifier"));
		$this->datatable->setColumns(array("name"=>"slug", "column"=>"Slug"));//, 'callback'=>'Page_model/slug'
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Page Title"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status"));
		$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("page/index/add"=>'Add/Edit Page'));
		}

		{//form
			$this->load->library('ciform');
			
			$_templates = templates();
			$templates = array();
			foreach($_templates as $_template){
				$templates[strtolower($_template['name'])] = ucwords($_template['name']);
			}
			
			$slug = '';
			$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates, 'value'=>'default');
			if($id){
				$page_data = (array)$this->Page_model->getPageById($id);
				$this->ciform->form_data = $page_data;
				$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates);
			}
		
			$status_options = array(1=>"Enable", 0=>"Disable");
			$elements = array(
					array('name' => 'type', "type"=>"hidden", "id"=>"cms_type", "value"=>'pages'),
					//array('name'=> 'identifier', "label"=>"CMS Page Identifier", "type"=>"text", "validation"=>'required'),
					array('name' => 'slug', "label"=>"Slug URL", "type"=>"text", "validation"=>'required|alpha_dash'),
					array('name' => 'title', "label"=>"Page Title", "type"=>"text", "validation"=>'required'),
					array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", "validation"=>'required'),
					$template_select,
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$status_options, "validation"=>'required')
				);	
		
			$this->ciform->sections['CMS Page Details'] = $elements;
			
			{//gallery
				$this->data['gallery_images'] = array();
				if($id){
					$this->data['gallery_images'] = $this->Page_model->gallery_images($id);
				}
				$gallery_html = $this->load->view($this->admin_view('media/gallery'), $this->data, true);
				$_gallery = array(
					array('name' => 'gallery', "label"=>"Gallery", "type"=>"html", "html"=>$gallery_html),
				);
				$this->ciform->sections['Gallery'] = $_gallery;
			}
			
			$elements = array(
					array('name' => 'meta_title', "label"=>"Meta Title", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_description', "label"=>"Meta Description", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_keywords', "label"=>"Meta Keywords", "rows"=>"2", "type"=>"textarea"),
			);
			$this->ciform->sections['Page Meta'] = $elements;
			$this->ciform->cancel_link = admin_url('page');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("page/index/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			$saved = $this->Page_model->save($id);
			$gallery_save = $this->Page_model->save_gallery_images($saved);
		
			if($saved){
				msg('Page saved successfully.', admin_url("page"));
			}else{
				error_msg(admin_url("page"));
			}
		}

	}
	
	public function email_template(){
		{
			$this->data['title'] = "Email Template";
			$this->breadcrumb(array("email_template"=>"Email Template Management"));
		}
		
		$this->load->model('email_model');
		$total_rows = $this->db->order_by('id', 'desc')->get($this->email_model->table_name())->num_rows();
		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->email_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		$this->datatable->setTitle("Manage Email Template");

		$this->datatable->setColumns(array("name"=>"code", "column"=>"Code"));
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Title"));
		//$this->datatable->setColumns(array("name"=>"template_for", "column"=>"Template For", 'values'=>$this->template_for_options(), 'search'=>$this->template_for_options()));
		//$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", 'values'=>$this->status_options(), 'search'=>$this->status_options()));
		
		$this->datatable->editIndex = 'id';
		$this->datatable->editTitle = 'View';
		
		$this->datatable->addTitle = "Add New Template"; 
		
		$this->datatable->addLink = admin_url('page/add_template');
		$this->datatable->editLink = admin_url('page/add_template');
		
		//$this->datatable->row_buttons = array( array('link'=>admin_url().'email_template/add/', 'index'=>'id', 'title'=>'Edit') );
		$this->data['grid'] = $this->datatable->grid($query);
		
		//add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();
	}
	
	
	public function add_template($id=false){
		$user_type = 'admin';
		$fields = array();
		/*{//Important
			$user = $this->_user;
			if(isset($user['user_type']) && $user['user_type']=='user'){
				$user_type = 'user';
				$permissions = $user['permissions'];
				if(isset($permissions['fields'])){
					$fields = $permissions['fields'];
				}
			}
		}*/
		
		{// breadcrumb
			$this->data['title'] = "Email Template";
			$this->breadcrumb(array("email_template"=>"Email Template Management"));
		}
		$this->load->model('email_model');
		
		{
			$this->load->library('ciform');			
			
			//add_js(array('bootstrap-tagsinput.min.js' ,'bootstrap-tagsinput-angular.min.js'));
			//add_css('bootstrap-tagsinput.css');
			
			$code = array('name' => 'code', "label"=>"Code", "id"=>"code", "type"=>"text", "validation"=>'required');
			
			if($id){
				$data = (array)$this->email_model->fetch_row_by_field("id", $id);
				$this->ciform->form_data = $data;
				$this->data['data'] = $data;
				$code = array('name' => 'code', "label"=>"Code", "id"=>"code", "type"=>"text", 'disabled'=>'disabled');
			}
			
			//print_r($data);
			$variables = $this->load->view('admin/template/template_text', $this->data, true);
			
			$table = $this->email_model->table_name();
			$general_elements = array(
				$code ,
				array('name' => 'title', "label"=>"Title", "id"=>"title", "type"=>"text", "validation"=>'required'),
				array('name' => 'content', "label"=>"Content *", "id"=>"content", "type"=>"inline-html", "html"=>$variables),
				//array('name' => 'template_for', "label"=>"Template For", "id"=>"template_for", "type"=>"select", 'options'=>$this->template_for_options(), "validation"=>'required'),
				//array('name' => 'status', "label"=>"Status", "id"=>"status", "type"=>"select", 'options'=>$this->status_options(), "validation"=>'required'),
			);	
			
			
			$this->ciform->sections['General Details'] 	= $general_elements;
			$this->ciform->cancel_link = admin_url("email_template");
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("page/index/remove_template");
		}
		
		$this->data['form'] = $this->ciform->create_form('Manage Vendors Profile');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			
			$saved = $this->email_model->save($id);
			if($saved){
				msg('Email Template Saved successfully.', admin_url("page/email_template"));
			}else{
				error_msg(admin_url("page/email_template"));
			}
		}
	}
	

	public function help_content(){
		
		$this->data['title'] = 'Help & Support Pages';

		$total_rows = $this->db->where("type", "help-support")->get($this->Page_model->table_name())->num_rows();
		$query = $this->db->select("*")->where("type", "help-support")->from($this->Page_model->table_name());//IMPORTANT: without get() METHOD

		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		
		$this->datatable->setTitle("Pages");
		
		$this->datatable->setColumns(array("name"=>"id", "column"=>"id", "align"=>"left", "width"=>20));
		//$this->datatable->setColumns(array("name"=>"identifier", "column"=>"Page Identifier"));
		//$this->datatable->setColumns(array("name"=>"slug", "column"=>"Slug"));//, 'callback'=>'Page_model/slug'
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Page Title"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", 'values'=>array(0=>'Inactive', 1=>'Active'), 'search'=>array(0=>'Inactive', 1=>'Active') ));
		$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->datatable->editIndex = 'id';
		$this->datatable->editTitle = 'Edit';
		
		$this->datatable->addTitle = "Add New Page"; 
		
		$this->datatable->addLink = admin_url('page/add_help_content');
		$this->datatable->editLink = admin_url('page/add_help_content');
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
	
		$this->Page();	
	}


	public function add_help_content($id=false){
		{//bread_crumb
			$this->breadcrumb(array("page/index/add_help_content"=>'Add/Edit Help & Support Page'));
		}

		{//form
			
			$this->data['title'] = "Help & Support Page";
			$this->load->library('ciform');
			
			$_templates = templates();
			$templates = array();
			foreach($_templates as $_template){
				$templates[strtolower($_template['name'])] = ucwords($_template['name']);
			}
			
			$slug = '';
			$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates, 'value'=>'default');
			if($id){
				$page_data = (array)$this->Page_model->getPageById($id);
				$this->ciform->form_data = $page_data;
				$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates);
			}
		
			$status_options = array(1=>"Enable", 0=>"Disable");
			$elements = array(
					array('name' => 'type', "type"=>"hidden", "id"=>"cms_type", "value"=>'pages'),
					//array('name'=> 'identifier', "label"=>"CMS Page Identifier", "type"=>"text", "validation"=>'required'),
					//array('name' => 'slug', "label"=>"Slug URL", "type"=>"text", "validation"=>'required|alpha_dash'),
					array('name' => 'title', "label"=>"Page Title", "type"=>"text", "validation"=>'required'),
					array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", "validation"=>'required'),
					//$template_select,
					array('name' => 'sort_order', "label"=>"Sort Order", "type"=>"text", "validation"=>''),
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$status_options, "validation"=>'required')
				);	
		
			$this->ciform->sections['Page Details'] = $elements;
			
			{//gallery
				$this->data['gallery_images'] = array();
				if($id){
					$this->data['gallery_images'] = $this->Page_model->gallery_images($id);
				}
				$gallery_html = $this->load->view($this->admin_view('media/gallery'), $this->data, true);
				$_gallery = array(
					array('name' => 'gallery', "label"=>"Gallery", "type"=>"html", "html"=>$gallery_html),
				);
				$this->ciform->sections['Gallery'] = $_gallery;
			}
			
			$elements = array(
					array('name' => 'meta_title', "label"=>"Meta Title", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_description', "label"=>"Meta Description", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_keywords', "label"=>"Meta Keywords", "rows"=>"2", "type"=>"textarea"),
			);
			$this->ciform->sections['Page Meta'] = $elements;
			$this->ciform->cancel_link = admin_url('page/index/help_content');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("page/index/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			$saved = $this->Page_model->save_help_content($id);
			$gallery_save = $this->Page_model->save_gallery_images($saved);
		
			if($saved){
				msg('Page saved successfully.', admin_url("page/help_content"));
			}else{
				error_msg(admin_url("page/help_content"));
			}
		}

	}

	function remove($id=NULL){
		if(!is_null($id)){
			$this->Page_model->remove($id);
			redirect($this->admin_url("page"));
		}else{
			redirect($this->admin_url());
		}
	}
	
	

	function remove_template($id=NULL){
		if(!is_null($id)){
			$this->Email_template_model->remove($id);
			redirect($this->admin_url("page/email_template"));
		}else{
			redirect($this->admin_url());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */