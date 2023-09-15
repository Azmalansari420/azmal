<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Admin_Controller {

	private $model = false;

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Navigation Management";
		$this->breadcrumb(array("menu"=>"Navigation Management"));
		$this->load->model("menu_model");
	}
	
	public function index(){
		$query = $this->db->select("*")->from($this->menu_model->table_name());
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('navigation_index');
		$this->datatable->setTitle("Manage Navigations");

		$this->datatable->setColumns(array("name"=>"id", "column"=>"ID", 'width'=>60));
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", 'values'=>$this->menu_model->status_options(), 'search'=>$this->menu_model->status_options()));
		
		$this->datatable->editIndex = 'id';
		$this->datatable->editTitle = false;
		
		$this->datatable->addTitle = "Add New Menu"; 
		
		$this->datatable->addLink = admin_url('menu/add');
		$this->datatable->editLink = admin_url('menu/view');
		
		//$this->datatable->row_buttons = array( array('link'=>admin_url().'products/add/', 'index'=>'id', 'title'=>'Edit') );
	
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();
	}

	public function add($id=false){
		$this->data['title'] = 'Add New Navigation';
		$this->breadcrumb(array("menu/add"=>"Add/Edit Navigation"));

		$nav_items = $this->wd_admin->extract_navigation_items();
		$this->data['nav_items'] = $nav_items;

		$this->data['main_form'] = $this->load->view(admin_view('menu/main-form'), $this->data, true);
		
		add_css(array("menu.css"));

		$this->Page('menu/page');
	}

	public function save_menu(){
		print_r($_POST);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */