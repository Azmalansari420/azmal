<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Tags";
		$this->breadcrumb(array("tags"=>"Tags"));
		$this->load->model("tags_model");
		$this->load->model("location/cities_model");
	}
	
	public function status_options(){
		return array('1'=>'Enable', '0'=>'Disable');
	}
	
	public function footer_options(){
		return array(0=>"No", 1=>"Yes");
	}
	
	public function index(){

		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->tags_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Tags");

		$this->datatable->setColumns(array("name"=>"name", "column"=>"Tag"));
		$this->datatable->setColumns(array("name"=>"url", "column"=>"URL"));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->status_options(), 'search'=>$this->status_options()));
		//$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("tags/add"=>'Add/Edit Tag'));
		}

		{//form
			$this->load->library('ciform');
			
			$slug = '';
			if($id){
				$data = (array)$this->tags_model->fetch_row_by_id($id);
				$this->ciform->form_data = $data;
			}
			
			$general_elements = array(
				array('name' => 'name', "label"=>"Tag Name", "id"=>"name", "type"=>"text", "validation"=>'required'),
				array('name' => 'url', "label"=>"Page Url", "id"=>"url", "type"=>"text", "validation"=>'required'),
				//array('name' => 'sort_order', "label"=>"Sort Order", "id"=>"sort_order", "type"=>"text", "validation"=>'required'),
				
				
				array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->status_options(), "validation"=>'required'),
			);		
		
			$this->ciform->sections['General Details'] = $general_elements;
			
			$this->ciform->cancel_link = admin_url('tags');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("tags/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Page');
		if($this->data['form']){
			$this->Page();
		}else{//save post

			$saved = $this->tags_model->save($id);
			if($saved){
				msg('Tags saved successfully.', admin_url("tags"));
			}else{
				error_msg(admin_url("tags"));
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
			$this->tags_model->remove($id);
			redirect($this->admin_url("tags"));
		}else{
			redirect($this->admin_url("tags"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */