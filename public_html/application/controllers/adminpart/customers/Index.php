<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Customers";
		$this->breadcrumb(array("customers"=>"Customers"));
		$this->load->model("customers_model");
	}
	
	public function status_options(){
		return array('1'=>'Enable', '0'=>'Disable');
	}
	
	public function footer_options(){
		return array(0=>"No", 1=>"Yes");
	}
	
	public function index(){

		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->customers_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Customers");

		$this->datatable->setColumns(array("name"=>"name", "column"=>"Name"));
		$this->datatable->setColumns(array("name"=>"email_id", "column"=>"Email"));
		$this->datatable->setColumns(array("name"=>"mobile_no", "column"=>"Mobile No."));
		$this->datatable->setColumns(array("name"=>"added_on", "column"=>"Date"));
		
		
		//$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", "values"=>$this->status_options(), 'search'=>$this->status_options()));
		//$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		$this->datatable->addLink = false;
		$this->datatable->addTitle = ""; 
		
		//$this->datatable->editIndex = 'order_id';
		$this->datatable->editTitle = '';
		$this->datatable->editLink = false;//admin_url('sales/orders/view');
		
		$this->datatable->buttons = array(array("label"=>"Export To Excel", 'link'=>admin_url("customers/export")));
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}
	
	
	public function export(){
		
		$institutes_data = $this->customers_model->fetch_data();
		
		$fields_to_export = array();
		$data_to_export = array();
		
		$n = 0;
		foreach($institutes_data as $c_data){
			$n++;
	
			if($n==1){
				foreach($c_data as $index => $data){
					$fields_to_export[$index] = $index;
				}
				$data_to_export[] = $fields_to_export;
			}
			
			$row_data = array();
			foreach($c_data as $index => $data){
				$row_data[$index] = $data;
			}
			$data_to_export[] = $row_data;
		}
		
		$file_name = $this->customers_model->table_name()."_".date("Y_m_d").".csv";
		
		header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment;filename=$file_name");
				
		$output = fopen('php://output', 'w');
				
		foreach($data_to_export as $fields){
			fputcsv($output, $fields);
		}
		exit;
	}

	
	function remove($id=NULL){
		if(!is_null($id)){
			$this->customers_model->remove($id);
			redirect($this->admin_url("customers"));
		}else{
			redirect($this->admin_url("customers"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */