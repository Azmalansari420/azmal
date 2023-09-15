<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Page extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Pages";
		$this->breadcrumb(array("cms/page"=>"Pages"));
		$this->load->model("cms/page_model");
	}
	
	public function index(){

		$query = $this->db->select("*")->where("type", "pages")->order_by('id', 'desc')->from($this->page_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Pages");
		
		$this->datatable->setColumns(array("name"=>"id", "column"=>"id", "align"=>"left", "width"=>60));
		//$this->datatable->setColumns(array("name"=>"identifier", "column"=>"Page Identifier"));
		$this->datatable->setColumns(array("name"=>"slug", "column"=>"Slug"));//, 'callback'=>'page_model/slug'
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Page Title"));
		$this->datatable->setColumns(array("name"=>"template", "column"=>"Template"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->page_model->status_options(), 'search'=>$this->page_model->status_options()));
		//$this->datatable->setColumns(array("name"=>"visibility", "column"=>"Visibility", "values"=>$this->page_model->visibility_options(), 'search'=>$this->page_model->visibility_options()));
		$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("cms/page/add"=>'Add/Edit Page'));
		}

		{//form
			$this->load->library('ciform');
			
			$_templates = templates();

			$templates = array();
			foreach($_templates as $_template){
				$templates[strtolower($_template['name'])] = ucwords($_template['name']);
			}
			
			//print_r($_templates);
			
			$slug = '';
			$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates, 'value'=>'default');
			if($id){
				$page_data = (array)$this->page_model->fetch_row_by_id($id);
				$this->ciform->form_data = $page_data;
				$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'options'=>$templates);
			}
		
			$elements = array(
					array('name' => 'type', "type"=>"hidden", "id"=>"cms_type", "value"=>'pages'),
					//array('name'=> 'identifier', "label"=>"CMS Page Identifier", "type"=>"text", "validation"=>'required'),
					array('name' => 'slug', "label"=>"Slug URL", "type"=>"text", "validation"=>'required|alpha_dash'),
					array('name' => 'title', "label"=>"Page Title", "type"=>"text", "validation"=>'required'),
					//array('name'=>'banner_image', "label"=>"Banner Image", "id"=>"banner_image", "type"=>"image", 'upload_dir'=>'banners/', 'allowed_types'=>'jpg,jpeg,gif,pnp','ajax'=>true, "validation"=>''),
					array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", "validation"=>''),
					$template_select,
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->page_model->status_options(), "validation"=>'required'),
					array('name' => 'visibility', "label"=>"Visibility", 'id'=>'visibility', "type"=>"select", "options"=>$this->page_model->visibility_options(), "validation"=>'required')
				);	
		
			$this->ciform->sections['CMS Page Details'] = $elements;
			
			$elements = array(
					array('name' => 'meta_title', "label"=>"Meta Title", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_description', "label"=>"Meta Description", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'meta_keywords', "label"=>"Meta Keywords", "rows"=>"2", "type"=>"textarea"),
					array('name' => 'header_code', "label"=>"Header Code", "rows"=>"2", "type"=>"textarea"),
			);
			$this->ciform->sections['Page Meta'] = $elements;
			$this->ciform->cancel_link = admin_url('cms/page');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("cms/page/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post

			$saved = $this->page_model->save($id);
			if($saved){
				msg('Page saved successfully.', admin_url("cms/page"));
			}else{
				error_msg(admin_url("cms/page"));
			}
		}

	}

	function remove($id=NULL){
		if(!is_null($id)){
			$this->page_model->remove($id);
			redirect($this->admin_url("cms/page"));
		}else{
			redirect($this->admin_url());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */