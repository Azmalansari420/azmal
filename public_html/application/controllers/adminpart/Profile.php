<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Admin_Controller {

	private $model = false;

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Admin Profile";
		$this->breadcrumb(array("profile"=>"Profile"));
		$this->load->model("Users_model");
	}

	public function index(){
		$admin = $this->session->userdata('admin');
		if(isset($admin['user_type']) && $admin['user_type']=='super'){
				
		}else{
			error_msg(admin_url("dashboard"));
		}
		
		{//form
			$this->load->library('ciform');
			
			$data = (array)$this->db->where('id', $admin['id'])->get($this->Users_model->table_name())->row();
			$this->ciform->form_data = $data;
			
			$status_options = array(1=>"Enable", 0=>"Disable");
			
			$elements = array(
					array('name' => 'id', "type"=>"hidden", "id"=>"id", 'label'=>'Email', "validation"=>'', 'value'=>$data['id']),
					array('name' => 'useremail', "type"=>"view", "id"=>"useremail", 'label'=>'Email', "validation"=>''),
					array('name' => 'username', "type"=>"view", "id"=>"username", 'label'=>'Username', "validation"=>''),
					array('name' => 'first_name', "type"=>"text", "id"=>"first_name", 'label'=>'First Name', "validation"=>'required'),
					array('name' => 'last_name', "type"=>"text", "id"=>"last_name", 'label'=>'Last Name', "validation"=>'required'),
					array('name' => 'contact_number', "type"=>"text", "id"=>"contact_number", 'label'=>'Contact Number', "validation"=>''),
				);	
		
			$this->ciform->sections['Admin Details'] = $elements;
			
			$this->ciform->cancel_link = $this->admin_url('profile');
		}
		
		$this->data['form'] = $this->ciform->create_form('Manage Profile');
		if($this->data['form']){
			//print_r($_POST);
			$this->Page();
		}else{//save post
			
			$saved = $this->Users_model->update_admin();
			if($saved){
				msg('Profile updated successfully.', $this->admin_url("profile"));
			}else{
				error_msg($this->admin_url("profile"));
			}
			//redirect($this->admin_url("cms/index/add/$saved"));
		}
	}
	
	public function password(){
		$this->data['title'] = "Change Password";
		$admin = $this->session->userdata('admin');
		if(isset($admin['user_type']) && $admin['user_type']=='super'){
				
		}else{
			error_msg(admin_url("dashboard"));
		}
		{//bread_crumb
			$this->breadcrumb(array("password"=>"Change Password"));
		}
		
		{//form
			$this->load->library('ciform');
			
			$data = (array)$this->db->where('id', $admin['id'])->get($this->Users_model->table_name())->row();
			$this->ciform->form_data = $data;
			
			$status_options = array(1=>"Enable", 0=>"Disable");
			
			$elements = array(
					array('name' => 'id', "type"=>"hidden", "id"=>"id", 'label'=>'Email', "validation"=>'', 'value'=>$data['id']),
					array('name' => 'email', "type"=>"hidden", "id"=>"email", 'label'=>'Email', "validation"=>'', 'value'=>$data['useremail']),
					array('name' => 'old_password', "type"=>"password", "id"=>"old_password", 'label'=>'Old Password', "validation"=>'required|min_length[6]'),
					array('name' => 'new_password', "type"=>"password", "id"=>"new_password", 'label'=>'New Password', "validation"=>'required|min_length[6]|matches[con_password]'),
					array('name' => 'con_password', "type"=>"password", "id"=>"con_password", 'label'=>'Confirm Password', "validation"=>'required|min_length[6]'),
				);	
		
			$this->ciform->sections['Admin Details'] = $elements;
			
			$this->ciform->cancel_link = $this->admin_url('dashboard');
		}
		
		$this->data['form'] = $this->ciform->create_form('Manage Profile');
		if($this->data['form']){
			//print_r($_POST);
			$this->Page();
		}else{//save post
			
			$saved = $this->Users_model->update_password();
			if($saved){
				msg('Password updated successfully.', $this->admin_url("profile/password"));
			}else{
				error_msg("Old Password was wrong or New password not matched Confirmed password.", $this->admin_url("profile/password"));
			}
			//redirect($this->admin_url("cms/index/add/$saved"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */