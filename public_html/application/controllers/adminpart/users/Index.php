<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {
	private $model = false;
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Users";
		$this->breadcrumb(array("users"=>"Users"));
		$this->load->model("users_model");
	}
	public function index(){
		
		$job_profile = user_details("job_profile");
		
		if($job_profile == $this->users_model->super_admin_job_profile()){
			$total_rows = $this->db->where('id !=', user_details("id"))->order_by('id', 'desc')->get($this->users_model->table_name())->num_rows();
			$query = $this->db->where('id !=', user_details("id"))->select("*")->order_by('id', 'desc')->from($this->users_model->table_name());//IMPORTANT: without get() METHOD
		}else{
			$total_rows = $this->db->where('user_type', 'user')->order_by('id', 'desc')->get($this->users_model->table_name())->num_rows();
			$query = $this->db->select("*")->where('user_type', 'user')->order_by('id', 'desc')->from($this->users_model->table_name());//IMPORTANT: without get() METHOD
		}	
		
		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		
		$this->datatable->setTitle("Manage Teachers");
		
		$this->datatable->setColumns(array("name"=>"first_name", "column"=>"First Name"));
		$this->datatable->setColumns(array("name"=>"last_name", "column"=>"Last Name"));
		$this->datatable->setColumns(array("name"=>"username", "column"=>"User Name"));
		$this->datatable->setColumns(array("name"=>"roles", "column"=>"User Role"));
		$this->datatable->setColumns(array("name"=>"contact_number", "column"=>"Contact Number"));
		$this->datatable->setColumns(array("name"=>"useremail", "column"=>"Email"));
		
		$this->datatable->editIndex = 'id';
		$this->datatable->editTitle = 'View';
		
		$this->datatable->addTitle = "Add New User"; 
		$this->datatable->addLink = admin_url('users/add');
		
		$this->datatable->buttons = array(array("label"=>"Create Super Admin", 'link'=>admin_url("users/add_super_admin")));
		//$this->datatable->addLink = '';
		
		//$this->datatable->row_buttons = array( array('link'=>admin_url().'users/index/featured_mark/', 'index'=>'adv_id', 'title'=>'Mark Featured') );
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}
	
	public function add($id=false){
		
		{//bread_crumb
			$this->breadcrumb(array("users/index/add"=>"Add New User"));
		}
		{//form
			$this->load->library('ciform');
			
			$selected_vendors = false;
			if($id){
				$data = (array)$this->users_model->getDataById($id);
				
				$this->ciform->form_data = $data;
				$this->data['user_data'] = $data; 
				
				if($data['job_profile'] == $this->users_model->super_admin_job_profile()){
					redirect(admin_url("users/add_super_admin/{$id}"));
				}
			}
			
			$this->load->model("roles_model");
			$user_type_options = $this->db->get($this->roles_model->table_name());
			$role_title[''] = "Please Select User Role";
			foreach($user_type_options->result() as $row){
				$role_title[$row->role_key] = $row->role_title;
			}
			
			$status_options = array(1=>"Enable", 0=>"Disable");
			$table_name = $this->users_model->table_name();
			$table_index = $this->users_model->table_index();
			
			if($id){
				$inline_passowrd = $this->load->view(admin_view('users/password'), $this->data, true);
				//$email_field = array('name' => 'useremail', "type"=>"view", "id"=>"useremail", 'label'=>'Email', 'inline-edit'=>true, 'row_id'=>$id, 'table_name'=>$table_name, 'table_index'=>$table_index, "validation"=>"required|valid_email|is_unique[users.useremail]");
				$password = array('name' => 'password', "type"=>"inline-html", "id"=>"password", 'label'=>'Password', 'html'=>$inline_passowrd);
				$username = array('name' => 'username', "type"=>"view", "id"=>"username", 'label'=>'Username', 'inline-edit'=>true, 'row_id'=>$id, 'table_name'=>$table_name, 'table_index'=>$table_index, "validation"=>"required|is_unique[{$table_name}.username]");
			}else{
				$username = array('name' => 'username', "type"=>"text", "id"=>"username", 'label'=>'Username', 'validate_unique'=>$table_name, "validation"=>"required|is_unique[{$table_name}.username]");
				//$email_field = array('name' => 'useremail', "type"=>"text", "id"=>"useremail", 'label'=>'Email', "validation"=>'required|valid_email');
				$password = array('name' => 'password', "type"=>"text", "id"=>"password", 'label'=>'Password', "validation"=>'required|min_length[6]');
			}
			
			$elements = array(
					array('name' => 'user_type', "type"=>"hidden", "id"=>"user_type", 'label'=>'User Type', 'value'=>'user', "validation"=>'required'),
					array('name' => 'job_profile', "type"=>"select", "id"=>"job_profile", 'label'=>'Job Profile', 'options'=>$role_title, 'force_select'=>true, "validation"=>'required'),
					array('name' => 'first_name', "type"=>"text", "id"=>"first_name", 'label'=>'First Name', "validation"=>'required'),
					array('name' => 'last_name', "type"=>"text", "id"=>"last_name", 'label'=>'Last Name', "validation"=>'required'),
					array('name' => 'contact_number', "type"=>"text", "id"=>"contact_number", 'label'=>'Contact Number', "validation"=>'required|numeric'),
					$username,
					array('name' => 'useremail', "type"=>"text", "id"=>"useremail", 'label'=>'Email', "validation"=>'required|valid_email'),
					$password,
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$status_options, "validation"=>'required')
				);	
		
			$this->ciform->sections['User Details'] = $elements;
			
			$this->ciform->cancel_link = admin_url('users');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("users/index/remove");
			
			//add_js(array("base/jquery.multi-select.js", "base/jquery.quicksearch.js"));
			//add_css(array("base/multi-select.css"));
			
			//$this->ciform->append_form_body = $this->load->view(admin_view('roles/user-form'), $this->data, true);
			//$this->ciform->save_link = false;
		}
		
		$this->data['userform'] = $this->ciform->create_form('Manage User Profile');
		$this->data['user_form'] = $this->config->item('ci_form');
		if($this->data['userform']){
			$this->Page();
		}else{//save post
			$saved = $this->users_model->save($id);
			if($saved){
				msg('User saved successfully.', admin_url("users/index"));
			}else{
				error_msg(admin_url("users"));
			}
		}
	}
	
	public function reset_user_password($id=false){
		if(!$id){
			redirect(admin_url('users'));
		}
		
		$user = $this->users_model->getDataById($id);
		if($user->user_type == $this->users_model->super_admin_type()){
			redirect(admin_url('users'));
		}
		
		{//bread_crumb
			$this->data['title'] = "Reset User Password";
			$this->breadcrumb(array("users/index/reset_user_password"=>"Reset User Password"));
		}
		
		{//form
			$this->load->library('ciform');
			
			$elements = array(
				array('name' => 'id', "type"=>"hidden", "id"=>"id", 'label'=>'ID', 'value'=>$id),
				array('name' => 'password', "type"=>"text", "id"=>"password", 'label'=>'Password', "validation"=>'required|min_length[6]'),
				array('name' => 'c_password', "type"=>"text", "id"=>"c_password", 'label'=>'Re Enter Password', "validation"=>'required|min_length[6]|matches[password]')
			);
			
			$this->ciform->sections['Details'] = $elements;
			
			$this->ciform->cancel_link = admin_url('users');
			$this->ciform->remove_index = false;
			$this->ciform->remove_link = false;//$this->admin_url("users/index/remove");
			
			$this->data['form'] = $this->ciform->create_form('Manage Password');
		}
		if($this->data['form']){
			$this->Page();
		}else{//save post
			$saved = $this->users_model->reset_user_password();
			if($saved){
				msg('Password updated successfully.', admin_url("users/add/{$id}"));
			}else{
				error_msg(admin_url("users"));
			}
		}
	}
	
	public function add_super_admin($id=false){
		
		$job_profile = user_details("job_profile");
		if($this->users_model->super_admin_job_profile() != $job_profile){
			error_msg("Unauthorized Access!!", admin_url("users"));
		}
		
		{//bread_crumb
			$this->breadcrumb(array("users/index/add"=>"Add New User"));
			$this->data['title'] = 'Create Super Admin';
		}
		
		{//form
			$this->load->library('ciform');
			
			$selected_vendors = false;
			if($id){
				$data = (array)$this->users_model->getDataById($id);
				
				$this->ciform->form_data = $data;
				$this->data['user_data'] = $data;
			}
			
			$this->load->model("roles_model");
			$user_type_options = $this->db->get($this->roles_model->table_name());
			$role_title[''] = "Please Select User Role";
			foreach($user_type_options->result() as $row){
				$role_title[$row->role_key] = $row->role_title;
			}
			
			$status_options = array(1=>"Enable", 0=>"Disable");
			$table_name = $this->users_model->table_name();
			$table_index = $this->users_model->table_index();
			
			if($id){
				$inline_passowrd = $this->load->view(admin_view('users/password'), $this->data, true);
				//$email_field = array('name' => 'useremail', "type"=>"view", "id"=>"useremail", 'label'=>'Email', 'inline-edit'=>true, 'row_id'=>$id, 'table_name'=>$table_name, 'table_index'=>$table_index, "validation"=>"required|valid_email|is_unique[users.useremail]");
				$password = array('name' => 'password', "type"=>"inline-html", "id"=>"password", 'label'=>'Password', 'html'=>$inline_passowrd);
				$username = array('name' => 'username', "type"=>"view", "id"=>"username", 'label'=>'Username', 'inline-edit'=>true, 'row_id'=>$id, 'table_name'=>$table_name, 'table_index'=>$table_index, "validation"=>"required|is_unique[{$table_name}.username]");
			}else{
				$username = array('name' => 'username', "type"=>"text", "id"=>"username", 'label'=>'Username', 'validate_unique'=>$table_name, "validation"=>"required|is_unique[{$table_name}.username]");
				//$email_field = array('name' => 'useremail', "type"=>"text", "id"=>"useremail", 'label'=>'Email', "validation"=>'required|valid_email');
				$password = array('name' => 'password', "type"=>"text", "id"=>"password", 'label'=>'Password', "validation"=>'required|min_length[6]');
			}
			
			$elements = array(
					array('name' => 'user_type', "type"=>"hidden", "id"=>"user_type", 'label'=>'User Type', 'value'=>$this->users_model->super_admin_type(), "validation"=>'required'),
					array('name' => 'job_profile', "type"=>"hidden", "id"=>"job_profile", 'label'=>'Job Profile', 'value'=>$this->users_model->super_admin_job_profile(), "validation"=>'required'),
					array('name' => 'first_name', "type"=>"text", "id"=>"first_name", 'label'=>'First Name', "validation"=>'required'),
					array('name' => 'last_name', "type"=>"text", "id"=>"last_name", 'label'=>'Last Name', "validation"=>'required'),
					array('name' => 'contact_number', "type"=>"text", "id"=>"contact_number", 'label'=>'Contact Number', "validation"=>'required|numeric'),
					$username,
					array('name' => 'useremail', "type"=>"text", "id"=>"useremail", 'label'=>'Email', "validation"=>'required|valid_email'),
					$password,
					array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$status_options, "validation"=>'required')
			);
			
			$this->ciform->sections['User Details'] = $elements;
			
			$this->ciform->cancel_link = admin_url('users');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("users/index/remove");
			
			//add_js(array("base/jquery.multi-select.js", "base/jquery.quicksearch.js"));
			//add_css(array("base/multi-select.css"));
			
			//$this->ciform->append_form_body = $this->load->view(admin_view('roles/user-form'), $this->data, true);
			//$this->ciform->save_link = false;
		}
		
		$this->data['userform'] = $this->ciform->create_form('Manage User Profile');
		$this->data['user_form'] = $this->config->item('ci_form');
		if($this->data['userform']){
			$this->Page();
		}else{//save post
			$saved = $this->users_model->save($id);
			if($saved){
				msg('User saved successfully.', admin_url("users/index"));
			}else{
				error_msg(admin_url("users"));
			}
		}
	}
	
	public function roles(){
		{
			$this->data['title'] = "Roles";
			//bread_crumb
			$this->breadcrumb(array("users/roles"=>"Roles"));
		}
		
		$this->load->model("roles_model");
		$total_rows = $this->db->order_by('id', 'desc')->get($this->roles_model->table_name())->num_rows();
		
		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->roles_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setRecords($total_rows);
		
		$this->datatable->setTitle("Manage Roles");
		
		$this->datatable->setColumns(array("name"=>"id", "column"=>"ID"));
		$this->datatable->setColumns(array("name"=>"role_key", "column"=>"Role Identifier"));
		$this->datatable->setColumns(array("name"=>"role_title", "column"=>"Role Title"));
		$this->datatable->setColumns(array("name"=>"permissions", "column"=>"Permissions", 'callback'=>"roles_model/permissions"));
		
		$this->datatable->editIndex = 'id';
		$this->datatable->editTitle = 'View/Edit';
		$this->datatable->editLink = admin_url('users/roles_add');
		
		$this->datatable->addTitle = "Add New Role"; 
		$this->datatable->addLink = admin_url('users/roles_add');
		
		//$this->datatable->row_buttons = array( array('link'=>admin_url().'users/index/remove_role/', 'index'=>'id', 'title'=>'Remove Role') );
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}
	
	public function roles_add($id=false){
		$this->load->model("roles_model");
		
		{//bread_crumb
			$this->breadcrumb(array("users/roles"=>"Roles", "users/roles_add"=>"Add New Role"));
		}
		{//form
			$this->load->library('ciform');
			
			$table_name = $this->roles_model->table_name();
			
			$this->data['permissions'] = false;
			$this->data['fields'] = false;
			$this->data['actions'] = false;
			if($id){
				$data = (array)$this->roles_model->getDataById($id);
				$this->ciform->form_data = $data;
				$this->data['permissions'] = $data['permissions'];
				$this->data['fields'] = $data['fields'];
				$this->data['actions'] = $data['actions'];
				
				$identifier = array('name' => 'role_key', "type"=>"view", "id"=>"role_key", 'label'=>'Identifier');
			}else{
				$identifier = array('name' => 'role_key', "type"=>"text", "id"=>"role_key", 'label'=>'Identifier', 'validate_unique'=>$table_name, "validation"=>"required|alpha_underscore|is_unique[{$table_name}.role_key]");
			}
			
			$elements = array(
					$identifier,
					array('name' => 'role_title', "type"=>"text", "id"=>"role_title", 'label'=>'Role Title', "validation"=>'required'),
				);	
			$this->ciform->sections['General Details'] = $elements;
			
			$modules = $this->config->item('modules');

			$_modules = $this->wd_admin->extract_fields($modules);
			$this->data['modules'] = $_modules;
			
			$roles_html = $this->load->view($this->admin_view('roles/form'), $this->data, true);
			$_role = array(
				array('name' => 'roles', "label"=>"roles", "type"=>"html", "html"=>$roles_html),
			);
			$this->ciform->sections['Permissions'] = $_role;
			
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("users/index/remove_role/");
			
			$this->data['form'] = $this->ciform->create_form('Manage Vendors Profile');
		}
		
		if($this->data['form']){
			$this->Page();
		}else{//save post
			//print_r(serialize($_POST['permissions']));exit;
			$saved = $this->roles_model->save($id);
			if($saved){
				msg('Role saved successfully.', admin_url("users/roles"));
			}else{
				error_msg(admin_url("users/roles_add"));
			}
		}
		return;
		///////////////$this->data['_form'] = $this->config->item('ci_form');
		
		add_css(array('base/jquery.nestable.css', 'admin/tree-style.css'));
		
		if($this->form_validation->run() == FALSE/* && $this->data['_form']*/){
			$this->load->view($this->admin_header(), $this->data);
			$this->load->view($this->admin_view("roles/form"), $this->data);
			$this->load->view($this->admin_footer());
		}else{//save post
			//print_r(serialize($_POST['permissions']));exit;
			$saved = $this->roles_model->save($id);
			if($saved){
				msg('Role saved successfully.', admin_url("users/roles"));
			}else{
				error_msg(admin_url("users/roles_add"));
			}
		}
	}	
	function remove_role($id=NULL){
		$this->load->model("roles_model");
		if(!is_null($id)){
			$this->roles_model->remove($id);
			redirect($this->admin_url("users/roles"));
		}else{
			redirect($this->admin_url());
		}
	}
	
	function remove($id=NULL){
		if(!is_null($id)){
			$this->users_model->remove($id);
			redirect($this->admin_url("users"));
		}else{
			redirect($this->admin_url());
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */