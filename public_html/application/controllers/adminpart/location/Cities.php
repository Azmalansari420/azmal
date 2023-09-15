<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cities extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Cities";
		$this->breadcrumb(array("location/cities"=>"Cities"));
		$this->load->model("location/cities_model");
	}
	
	public function status_options(){
		return array('1'=>'Enable', '0'=>'Disable');
	}
	
	public function footer_options(){
		return array(0=>"No", 1=>"Yes");
	}
	
	public function index(){

		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->cities_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Cities");
		
		$this->datatable->setColumns(array("name"=>"state", "column"=>"State"/*, "align"=>"left"*/));
		$this->datatable->setColumns(array("name"=>"name", "column"=>"City"));//, 'callback'=>'cities_model/slug'
		$this->datatable->setColumns(array("name"=>"mobile_no", "column"=>"Mobile No."));
		$this->datatable->setColumns(array("name"=>"whatapp_no", "column"=>"Whatapp No."));
		$this->datatable->setColumns(array("name"=>"sort_order", "column"=>"Sort Order"));
		
		$this->datatable->setColumns(array("name"=>"home_view", "column"=>"Home View", "values"=>$this->footer_options(), 'search'=>$this->footer_options()));
		$this->datatable->setColumns(array("name"=>"most_searched", "column"=>"Most Searched", "values"=>$this->footer_options(), 'search'=>$this->footer_options()));
		
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->status_options(), 'search'=>$this->status_options()));
		//$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("location/cities/add"=>'Add/Edit City'));
		}

		{//form
			$this->load->library('ciform');
			
			$slug = '';
			$content = '';
			$content_male	= '';
			if($id){
				$data = (array)$this->cities_model->fetch_row_by_id($id);
				$this->ciform->form_data = $data;
				$content = ($data['content']);
				$content_male = ($data['content_male']);
			}
			
			$this->data['data']		= $data;
			$content_html = $this->load->view($this->admin_view('content_editor/content'), $this->data, true);
		
			$elements = array(
				//array('name' => 'slug', "label"=>"Slug URL", "type"=>"text", "validation"=>'required|alpha_dash'),
				//array('name' => 'title', "label"=>"Cities Name", "type"=>"text", "validation"=>'required'),
				//array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", "validation"=>''),
				
				array('name' => 'state', "label"=>"State", "type"=>"select", "options"=>get_states(), "validation"=>'required'),
			
				array('name' => 'name', "label"=>"City Name", "id"=>"name", "type"=>"text", "validation"=>'required'),
				
				array('name' => 'mobile_no', "label"=>"Mobile No.", "type"=>"text"),
				array('name' => 'whatapp_no', "label"=>"Whatapp No.", "type"=>"text"),
				
				array('name' => 'p_mobile_no', "label"=>"All Profiles Mobile No.", "type"=>"text" ),
				array('name' => 'p_whatapp_no', "label"=>"All Profiles Whatapp No.", "type"=>"text"),
				
				array('name' => 'sort_order', "label"=>"Sort Order", "id"=>"sort_order", "type"=>"text", "validation"=>'required'),
				//array('name' => 'image', "class"=>'pr_image col-lg-4', "label"=>"Image", "type"=>"image", "image_path"=>"media/uploads/"),
				
				//array('name' => 'content', "label"=>"Content", "type"=>"html", "html"=>$content_html),
				
				array('name' => 'home_view', "label"=>"Home View", "type"=>"select", "options"=>$this->footer_options(), "validation"=>'required'),
				array('name' => 'most_searched', "label"=>"Most Searched", "type"=>"select", "options"=>$this->footer_options(), "validation"=>'required'),
				
				array('name' => 'footer', "label"=>"Footer View", "type"=>"select", "options"=>$this->footer_options(), "validation"=>'required'),
				array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->status_options(), "validation"=>'required'),
			);	
		
			$this->ciform->sections['General Details'] = $elements;
			
			$content_elements = array(
				array('name' => 'content', "label"=>"Call Girls Content", "id"=>"page_content", "type"=>"texteditor", 'value'=>$content),
				array('name' => 'content_male', "label"=>"Male Escorts Content", "id"=>"content_male", "type"=>"texteditor", 'value'=>$content_male),
			);	
		
			$this->ciform->sections['Content  Details'] = $content_elements;
			
			$elements = array(
				array('name' => 'meta_title', "label"=>"Call Girls Meta Title", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_description', "label"=>"Call Girls Meta Description", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_keywords', "label"=>"Call Girls Meta Keywords", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'header_code', "label"=>"Call Girls Header Code", "rows"=>"5", "type"=>"textarea"),
				
				
				array('name' => 'meta_title_male', "label"=>"Male Escorts Meta Title", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_description_male', "label"=>"Male Escorts Meta Description", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_keywords_male', "label"=>"Male Escorts Meta Keywords", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'header_code_male', "label"=>"Male Escorts Header Code", "rows"=>"5", "type"=>"textarea"),
			);
			$this->ciform->sections['Meta Details'] = $elements;
			$this->ciform->cancel_link = admin_url('location/cities');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("location/cities/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post

			$saved = $this->cities_model->save($id);
			if($saved){
				msg('Cities saved successfully.', admin_url("location/cities"));
			}else{
				error_msg(admin_url("location/cities"));
			}
		}

	}

	function remove($id=NULL){
		if(!is_null($id)){
			$this->cities_model->remove($id);
			redirect($this->admin_url("location/cities"));
		}else{
			redirect($this->admin_url("location/cities"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */