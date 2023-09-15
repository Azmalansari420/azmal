<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//need to do nothing here. just redirect to dashboard controller
class Index extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Module Manager";
	}

	public function frm(){
		$this->load->library('ciform');
		
		{
			$elements = array(
					array('name' => 'menu_tab', "label"=>"Menu Tab", "type"=>"select", "validation"=>'required', 'options'=>array(), 'comment'=>'Select menu tab to move the module inside.'),
					array('name' => 'module_tab', "label"=>"Module Tab", "type"=>"select", 'options'=>array(), 'comment'=>'Select a module to move the current module inside as a sub module.'),
			);
			
			//create form sections
			$this->ciform->sections['details'] =  $elements;
			
			$elements = array(
					array('name' => 'module_position', "label"=>"Module Position", "type"=>"text", 'comment'=>'Module position will be used to position the module in menu. (1-9)'),
					array('name' => 'module_name', "label"=>"Module Name", "type"=>"text", "validation"=>'required', 'value'=>'empty'),
			);
			
			$this->ciform->sections['a new form'] =  $elements;
			
			$elements = array(
							array('name' => 'module_model_name', "label"=>"Module Model Name", "type"=>"text", "validation"=>'required', 'value'=>'empty'),
							array('name' => 'module_table_name', "label"=>"Module Database Table Name", "type"=>"text", "validation"=>'required', 'value'=>'empty'),
							array('name' => 'id', "type"=>"hidden", 'value'=>'empty')
						);
			$this->ciform->sections['this is another form'] =  $elements;
		
		}
		
		$this->data['form'] = $this->ciform->create_form('new form');
		
		if($this->data['form']){
			$this->Page();
		}
	}
	
	public function ff(){
		$this->ciform->elements['menu_tab'] = array("label"=>"Menu Tab", "type"=>"select", "validation"=>'required', 'options'=>array(), 'comment'=>'Select menu tab to move the module inside.');
			$this->ciform->elements['module_tab'] = array("label"=>"Module Tab", "type"=>"select", 'options'=>array(), 'comment'=>'Select a module to move the current module inside as a sub module.');
			$this->ciform->elements['module_position'] = array("label"=>"Module Position", "type"=>"text", 'comment'=>'Module position will be used to position the module in menu. (1-9)');
			$this->ciform->elements['module_name'] = array("label"=>"Module Name", "type"=>"text", "validation"=>'required', 'value'=>'empty');
			$this->ciform->elements['module_model_name'] = array("label"=>"Module Model Name", "type"=>"text", "validation"=>'required', 'value'=>'empty');
			$this->ciform->elements['module_table_name'] = array("label"=>"Module Database Table Name", "type"=>"text", "validation"=>'required', 'value'=>'empty');
			$this->ciform->elements['id'] = array("type"=>"hidden", 'value'=>'empty');
	}	
	
	public function index(){
		echo "ok";
		//redirect($this->admin_url("dashboard"));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/admin/index.php */