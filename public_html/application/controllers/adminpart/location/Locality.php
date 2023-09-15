<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Locality extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Locality";
		$this->breadcrumb(array("location/locality"=>"Locality"));
		$this->load->model("location/locality_model");
		$this->load->model("location/cities_model");
	}
	
	public function status_options(){
		return array('1'=>'Enable', '0'=>'Disable');
	}
	
	public function footer_options(){
		return array(0=>"No", 1=>"Yes");
	}
	
	public function index(){

		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->locality_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Locality");
		
		$this->datatable->setColumns(array("name"=>"state", "column"=>"State"/*, "align"=>"left"*/));
		$this->datatable->setColumns(array("name"=>"city", "column"=>"City"));
		$this->datatable->setColumns(array("name"=>"name", "column"=>"Locality"));//, 'callback'=>'locality_model/slug'
		$this->datatable->setColumns(array("name"=>"mobile_no", "column"=>"Mobile No."));
		$this->datatable->setColumns(array("name"=>"whatapp_no", "column"=>"Whatapp No."));
		$this->datatable->setColumns(array("name"=>"sort_order", "column"=>"Sort Order"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->status_options(), 'search'=>$this->status_options()));
		//$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("location/locality/add"=>'Add/Edit Locality'));
		}

		{//form
			$this->load->library('ciform');
			
			$slug = '';
			$data = '';
			$content = '';
			if($id){
				$data = (array)$this->locality_model->fetch_row_by_id($id);
				$this->ciform->form_data = $data;
				$content = ($data['content']);
			}
			
			$this->data['data']		= $data;
			$city_html = $this->load->view($this->admin_view('location/add_city'), $this->data, true);
		
			$elements = array(
				
				array('name' => 'state', "label"=>"State", "type"=>"select", 'id'=>'state', "options"=>get_states(), "validation"=>'required'),
				
				array('name' => 'city', "label"=>"City", "type"=>"html", "html"=>$city_html),
			
				array('name' => 'name', "label"=>"Locality Name", "id"=>"name", "type"=>"text", "validation"=>'required'),
				
				array('name' => 'mobile_no', "label"=>"Mobile No.", "type"=>"text"),
				array('name' => 'whatapp_no', "label"=>"Whatapp No.", "type"=>"text"),
				
				array('name' => 'p_mobile_no', "label"=>"All Profiles Mobile No.", "type"=>"text"),
				array('name' => 'p_whatapp_no', "label"=>"All Profiles Whatapp No.", "type"=>"text"),
				
				array('name' => 'sort_order', "label"=>"Sort Order", "id"=>"sort_order", "type"=>"text", "validation"=>'required'),
				
				//array('name' => 'image', "class"=>'pr_image col-lg-4', "label"=>"Image", "type"=>"image", "image_path"=>"media/uploads/"),
				
				array('name' => 'content', "label"=>"Content", "id"=>"page_content", "type"=>"texteditor", 'value'=>$content),
				
				array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->status_options(), "validation"=>'required'),
			
			);	
		
			$this->ciform->sections['General Details'] = $elements;
			
			$elements = array(
				array('name' => 'meta_title', "label"=>"Meta Title", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_description', "label"=>"Meta Description", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'meta_keywords', "label"=>"Meta Keywords", "rows"=>"5", "type"=>"textarea"),
				array('name' => 'header_code', "label"=>"Header Code", "rows"=>"5", "type"=>"textarea"),
			);
			$this->ciform->sections['Meta Details'] = $elements;
			$this->ciform->cancel_link = admin_url('location/locality');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("location/locality/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post

			$saved = $this->locality_model->save($id);
			if($saved){
				msg('Locality saved successfully.', admin_url("location/locality"));
			}else{
				error_msg(admin_url("location/locality"));
			}
		}

	}
	
	function get_cities(){
		$html = '<option value="">Select City</option>';
		
		if(isset($_POST['city']) && isset($_POST['state'])){
			$city				= $_POST['city'];
			$state				= $_POST['state'];
			foreach(get_cities($state) as $k => $c){
				if($c->name == $city){
					$html .= '<option selected="selected" value="'.$c->name.'">'.$c->name.'</option>';
				}else{
					$html .= '<option value="'.$c->name.'">'.$c->name.'</option>';
				}
			}
		}
		
		echo $html;
	}
	
	function remove($id=NULL){
		if(!is_null($id)){
			$this->locality_model->remove($id);
			redirect($this->admin_url("location/locality"));
		}else{
			redirect($this->admin_url("location/locality"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */