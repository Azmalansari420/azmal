<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Section extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Sections";
		$this->breadcrumb(array("cms/section"=>"Sections"));
		$this->load->model("cms/section_model");
	}
	
	public function index(){

		$query = $this->db->select("*")->from($this->section_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Sections");
		
		$this->datatable->setColumns(array("name"=>"id", "column"=>"id", "align"=>"left", "width"=>60));
		//$this->datatable->setColumns(array("name"=>"identifier", "column"=>"Page Identifier"));
		$this->datatable->setColumns(array("name"=>"identifier", "column"=>"Identifier"));//, 'callback'=>'section_model/slug'
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Title"));
		$this->datatable->setColumns(array("name"=>"template", "column"=>"Template"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->section_model->status_options(), 'search'=>$this->section_model->status_options()));
		//$this->datatable->setColumns(array("name"=>"visibility", "column"=>"Visibility", "values"=>$this->section_model->visibility_options(), 'search'=>$this->section_model->visibility_options()));
		$this->datatable->setColumns(array("name"=>"added_on", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("cms/section/add"=>'Add/Edit Section'));
		}

		{//form
			$this->load->library('ciform');

			$table_name = $this->section_model->table_name();
			
			$_templates = templates(false, 'cms_section');
			$templates = array(''=>'Select Template');
			foreach($_templates as $_template){
				$templates[strtolower($_template['name'])] = ucwords($_template['name']);
			}
			
			$slug = '';
			$template_select = array('name' => 'template', 'id'=>'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'force_select'=>true, 'options'=>$templates, 'value'=>'default');
			$identifier_field = array('name'=> 'identifier', 'id'=>'identifier', "label"=>"Identifier", "type"=>"text", "validation"=>"required|is_unique[{$table_name}.identifier]", 'validate_unique'=>$table_name);

			if($id){
				$page_data = (array)$this->section_model->fetch_row_by_id($id);
				$this->ciform->form_data = $page_data;
				$template_select = array('name' => 'template', "label"=>"Template", "id"=>"template", "type"=>"select", 'force_select'=>true, 'options'=>$templates);
				$identifier_field = array('name'=> 'identifier', "label"=>"Identifier", "type"=>"text", "validation"=>"required");
			}
			
			$elements = array(
					$identifier_field,
					array('name' => 'title', "label"=>"Title", "type"=>"text", "validation"=>'required'),
					array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", "validation"=>''),
					$template_select,
					array('name' => 'content_position', 'id'=>'content_position', "label"=>"Content Position", "type"=>"select", "options"=>$this->section_model->content_position_options()),
					array('name' => 'includes', "label"=>"Includes - Data Source", "id"=>"includes", "type"=>"textarea", "validation"=>'', 'comment'=>'[type="model", name="section_model", method="method_name", var="variable"]<br />[type="helper", name="utility_helper", method="method_name",  var="variable"]'),
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->section_model->status_options(), "validation"=>'required'),
				);	
		
			$this->ciform->sections['CMS Page Details'] = $elements;
			
			$this->ciform->cancel_link = admin_url('cms/section');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("cms/section/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post

			$saved = $this->section_model->save($id);
			if($saved){
				msg('Page saved successfully.', admin_url("cms/section"));
			}else{
				error_msg(admin_url("cms/section"));
			}
		}

	}

	function remove($id=NULL){
		if(!is_null($id)){
			$this->section_model->remove($id);
			redirect($this->admin_url("cms/section"));
		}else{
			redirect($this->admin_url());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */