<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Submit_form extends Front_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
		$this->load->model("enquiries_model");
	}
	
	public function index(){
	
	}
	
	public function enquiries(){
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email ID', 'required|valid_email');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');
		
		$current_url =  base_url();
		if(isset($_POST['current_url']) && $_POST['current_url']!=''){
			$current_url = str_replace('index.php/', '', $_POST['current_url']);
			$current_url = str_replace('.html', '', $current_url);
			unset($_POST['current_url']);
		}
		
		if ($this->form_validation->run() == FALSE){
			error_msg('Error! fill required field', $current_url);//base_url("business/account/login")
		}else{
			$save_review = $this->enquiries_model->save();
			//exit;
			if($save_review){
				msg('Submited Your Message successfully.', $current_url.'?su=1');
			}else{
				error_msg('Error!', $current_url);
			}
		}
		
	}

}