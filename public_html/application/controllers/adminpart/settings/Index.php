<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {

	private $model = false;

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Settings";
		$this->breadcrumb(array("settings"=>"Settings"));
		$this->load->model("Settings_model");
	}

	public function index(){
	
		$total_rows = $this->db->order_by('id', 'desc')->get($this->Settings_model->table_name())->num_rows();
		
		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->Settings_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		$this->datatable->setControllerKey('settings_index');
		$this->datatable->setTitle("Manage Settings");
		
		$this->datatable->setColumns(array("name"=>"title", "column"=>"Title"));
		$this->datatable->setColumns(array("name"=>"key", "column"=>"Key"));
		$this->datatable->setColumns(array("name"=>"value", "column"=>"Value"));
		
		$this->datatable->editIndex = 'id';
		
		$this->datatable->addLink = admin_url('settings/add');
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}
	
	public function add($id=false){
		{//bread_crumb
			$this->breadcrumb(array("settings"=>"Settings", "settings/add"=>"Add/Edit Settings"));
		}

		{//form
			$this->load->library('ciform');
			$table = $this->Settings_model->table_name();
			$key = array('name' => 'key', "type"=>"text", "id"=>"key", 'label'=>'Setting Key', 'validate_unique'=>$table, 'comment'=>'Please enter a unique setting name. Only [a-z,-,_] allowed.', "validation"=>'required|alpha_dash|is_unique['.$table.'.key]');
			
			if($id){
				$data = (array)$this->Settings_model->get_by_id($id);
				$this->ciform->form_data = $data;
				$key = array('name' => 'key', "type"=>"view", "id"=>"key", 'label'=>'Setting Key');
			}
			
			$elements = array(
					array('name' => 'title', "type"=>"text", "id"=>"title", 'label'=>'Title', "validation"=>'required'),
					array('name' => 'setting_type', "type"=>"hidden", "id"=>"setting_type", 'label'=>'setting_type', 'value'=>'general_admin_settings', "validation"=>''),
					$key,
					array('name' => 'value', "type"=>"textarea", "id"=>"value", 'label'=>'Value', "validation"=>'required', "rows"=>'10', "class"=>'sm-12'),
				);	
		
			$this->ciform->sections['Details'] = $elements;
			
			$this->ciform->cancel_link = admin_url('settings');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("settings/index/remove");
			
			//$this->ciform->save_link = false;
		}
		
		$this->data['form'] = $this->ciform->create_form('');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			$saved = $this->Settings_model->save($id);
			if($saved){
				msg('Settings saved successfully.', admin_url("settings"));
			}else{
				error_msg(admin_url("settings"));
			}
		}
	}
	
	function remove($id=NULL){
		if(!is_null($id)){
			$this->Settings_model->remove($id);
			redirect($this->admin_url("settings"));
		}else{
			redirect($this->admin_url());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */