<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Account_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
		$this->load->model('customers_model');
		$this->load->model('profiles_model');
		$this->load->model('profile_enquiries_model');
		
		
		$customer_data 	= $this->session->userdata('customer');
		if($customer_data){
			$customer_id 	= $customer_data['id'];
			$this->data['customer_d'] = (array)$this->customers_model->fetch_row_by_field("id", $customer_id);
		}
	}
	
	
	public function check_login(){
		$customer_data = $this->session->userdata('customer');
		if($customer_data == ''){
			redirect(base_url('account/login'));
			exit;
		}
	}
	
	
	public function index(){
		$customer_data = $this->session->userdata('customer');
		if($customer_data){
			redirect(base_url('account/dashboard'));
		}else{
			redirect(base_url('account/login'));
		}
	}
	
	
	
	public function Login($id=false){
		//exit;
	
		$customer_data = $this->session->userdata('customer');
		
		if($customer_data!=''){
			redirect(base_url("account/dashboard"));
		}

		$page = $this->page_model->fetch_row_by_field('slug', 'log-in');
		if($page){
			$this->data['id'] = $page->id;
			$this->data['page'] = $page->slug;
			$this->data['title'] = $page->title;
			$this->data['description'] = $page->content;
			$this->data['meta_title'] = $page->meta_title;
			$this->data['meta_description'] = $page->meta_description;
			$this->data['meta_keywords'] = $page->meta_keywords;
			
			$this->data['data'] = $page;
		}
		
		$this->breadcrumb(array('account/login'=>$page->title));
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_id', 'Email ID', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');
		
		$this->theme->add_js(array("validate.js"));

		if ($this->form_validation->run() == FALSE){
			$this->page('account/login');
		}else{
			$post_data = $_POST;
			if(!$post_data==''){

				$user_info = $this->customers_model->check_user_status(trim($_POST['email_id']));
				//print_r($user_info);exit;
				
				if(/*!isset($user_info) || !is_array($user_info) || */empty($user_info)){
					error_msg('Login Details Incorrect.', base_url("account/login"));
				}

				if($user_info->status==2){
					error_msg("Your account has not been activated yet. Please wait, we will get back to you as soon as possible.", base_url("account/login"));

				}elseif($user_info->status==0){
					error_msg("Your account has been disabled by the admin.", base_url("account/login"));
				}else{
					$login_user = $this->customers_model->user_login();
					if($login_user){
						msg('Login successfully.', base_url('account/dashboard'));
					}else{
						error_msg('Login Details Incorrect.', base_url("account/login"));
					}
				}
			}
		}
	}
	
	
	public function register($id=false){
		
		$customer_data = $this->session->userdata('customer');
		if($customer_data != ''){
			redirect(base_url("account/dashboard"));
		}
		
		$page = $this->page_model->fetch_row_by_field('slug', 'register');
		if($page){
			$this->data['id'] = $page->id;
			$this->data['page'] = $page->slug;
			$this->data['title'] = $page->title;
			$this->data['description'] = $page->content;
			$this->data['meta_title'] = $page->meta_title;
			$this->data['meta_description'] = $page->meta_description;
			$this->data['meta_keywords'] = $page->meta_keywords;
			
			$this->data['data'] = $page;
		}
		
		$this->breadcrumb(array('account/register'=>$page->title));
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		//$this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required|regex_match[/^[0-9]{10}$/]|is_unique['.$this->customers_model->table_name().'.mobile_no]');
		$this->form_validation->set_rules('email_id', 'Email', 'required|valid_email|is_unique['.$this->customers_model->table_name().'.email_id]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('cpassword', 'Confirm password', 'required|trim');

		//$this->theme->add_js(array("validate.js"));
		
		if($this->form_validation->run() == FALSE){
			$this->page('account/register');
		}else{
			
				unset($_POST['terms_check']);
				unset($_POST['cpassword']);
				$_POST['add_by'] = 'User';
				$_POST['status'] = '2';	
			
			$saved = $this->customers_model->save();
			if($saved){
				msg('Your Account has been created successfully. An email has been sent to your account. Please check and verify your account.', base_url("account/login"));
			}else{
				error_msg("There was an error!! Please try again.", base_url("account/register"));
			}
		}
	}
	
	
	function c($id, $realid, $code, $realcode, $gc, $realgc){
		
		$this->breadcrumb(array('account/login'=>'login'));
		
		
		if($id == '' || $realid == '' || $code == '' || $realcode == '' || $gc == '' || $realgc == ''){
			error_msg("Bad request", base_url());
		}
		
		if($realid != '' && $realcode != ''){
			
			$crow = $this->customers_model->validate_confirmation_code($realid, $realcode);
			if(!$crow){
			
				error_msg("Bad request", base_url());
			
			}else{
				
				$save["id"] = $realid;
				$save["status"] = 1;
				
				$id = $this->customers_model->verify($save);
						
				//$this->session->set_flashdata('redirect', 'secure/profile');
				msg('Thanks for confirmation. Your acount has been activated. Please login in the below form using your credentials.', base_url("account/login"));
			}
			error_msg("Bad request", base_url());
		}else{
			error_msg("Bad request", base_url());
		}
		
	}
	
	
	public function forgot_password($id=false){
		
		$customer_data = $this->session->userdata('customer');
		if($customer_data != ''){
			redirect(base_url("account/dashboard"));
		}
		
		$this->data['title'] = "Forgot your Password?";
		$this->data['page'] = "forgot";
		$this->data['no_header'] = true;
		
		
		
		$this->breadcrumb(array('account/forgot_password'=>'Forgot your Password'));
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_id', 'Email ID', 'required|valid_email');
		
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');

		$this->theme->add_js(array("validate.js"));
		
		if($this->form_validation->run() == FALSE){
			$this->page('account/forgot_password');
		}else{
			$customers_data = $this->customers_model->forgot_password();
			if($customers_data){
				//$this->data['customers_data'] = $customers_data; 
				//$this->page('account/forgot_password');
				//msg('Send Password Your Email-Id Successfully.', base_url("account/login"));
				redirect(base_url("account/reset_password/".$customers_data));
			}else{
				error_msg("Incorrect Email-Id", base_url("account/forgot_password"));
			}
		}
	}
	
	
	public function reset_password($id=false){
		
		$customer_data = $this->session->userdata('customer');
		if($customer_data != ''){
			redirect(base_url("account/dashboard"));
		}
		
		if($id== ''){
			redirect(base_url("account/login"));
		}
		
		$this->data['title'] = "Reset Password?";
		$this->data['page'] = "reset";
		
		$this->breadcrumb(array('account/reset_password'=>'Reset Password'));
		
		$this->theme->add_js(array("validate.js"));
			
		$reset_user = $this->customers_model->reset_password($id);
		
		if($reset_user){
			msg('Reset Your Password Sent Your Email-Id Successfully.', base_url("account/login"));
		}else{
			error_msg("Incorrect Email-Id", base_url("account/forgot_password"));
		}

	}
	
	
	public function new_password(){
		$customer_data = $this->session->userdata('customer');
		if($customer_data != ''){
			redirect(base_url("account/dashboard"));
		}
		
		$this->data['title'] = "Reset your password";
		$this->data['page_name'] = "Reset your password";
		$this->data['page'] = "new_password";
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|new_password');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');
		
		$this->theme->add_js(array("validate.js"));
		
		$this->breadcrumb(array('account/new_password'=>'Reset your password'));
		
		if(isset($_GET['d']) && isset($_GET['c'])){
			$id = $_GET['d'];
			$c 	= $_GET['c'];
		}else{
			redirect(base_url("account/login"));
		}
					
		$check1 = $this->db->where('id', $id)->get($this->customers_model->table_name())->row();
		if(!$check1){
			redirect(base_url("account/login"));
		}
		
		
		$customer = $this->db->where('id', $id)->where('reset_password', $c)->get($this->customers_model->table_name())->row();
		if($customer){
			$this->data['customer'] = $customer;
		}
		
		if($this->form_validation->run() == FALSE){
			$this->page('account/new_password');
		}else{
			$new_password = $this->customers_model->new_password($customer->id);
			if($new_password){
				msg('Updated Your Password successfully.', base_url("account/login"));
			}else{
				error_msg('Error! There was an error! Please try again.' , base_url("account/login"));
			}
		}
	
	}
	
	
	public function dashboard(){
		$this->check_login();
		
		$customer_data = $this->session->userdata('customer');
		
		$this->data['title'] = "Dashboard";
		$this->data['page'] = "dashboard";
		
		$customer_id 		= $customer_data['id'];
		
		//print_r($customer_data);
		
		$this->breadcrumb(array('account/dashboard'=>'Dashboard'));
		
		$this->data['total_enquiries'] 			= $this->db->where('customer_id', $customer_id)->get($this->profile_enquiries_model->table_name())->num_rows();
		$this->data['total_profiles'] 			= $this->db->where('customer_id', $customer_id)->get($this->profiles_model->table_name())->num_rows();
		
		$this->page('account/dashboard');
	
	}
	
	public function profile($id=false){
		
		$customer_data 	= $this->session->userdata('customer');
		$customer_id 	= $customer_data['id'];
		
		if($customer_data == ''){
			redirect(base_url("account/login"));
		}
		
		$this->data['title'] = "Profile";
		$this->data['page'] = "profile";
		
		$this->breadcrumb(array('account/profile'=>'Profile'));
		
		
		$this->data['customer_d'] = (array)$this->customers_model->fetch_row_by_field("id", $customer_id);
		
		$this->theme->add_js(array("validate.js"));
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email_id', 'E-Mail Address', 'required|valid_email|callback_mailid_unique');
		$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'required|numeric|callback_mobile_unique');
		
		if($this->form_validation->run() == FALSE){
			$this->page('account/profile');
		}else{
			
			unset($_POST['status']);
			unset($_POST['email_id']);
			
			$saved = $this->customers_model->save($customer_id);
			if($saved){
				msg('Updated Your Profile Successfully.', base_url("account/profile"));
			}else{
				error_msg('Error.', base_url("account/profile"));
			}
		}
	}
		
		
		
	public function mobile_unique($mobileno=false){
		$customer_data = $this->session->userdata('customer');
		$customere_id 	= $customer_data['id'];
		if($mobileno){
			$check_no = $this->db->where("mobile_no", $mobileno)->get($this->customers_model->table_name())->row();
			if($check_no->id!='' && $check_no->id != $customere_id){
				$this->form_validation->set_message('mobile_unique', 'This Mobile No. is already used!');
				return FALSE;
			}else{
				return true;
			}
			return true;
		}
		return false;
	}	
	
	
	public function mailid_unique($mail_id=false){
		$customer_data = $this->session->userdata('customer');
		$customere_id 	= $customer_data['id'];
		
		if($mail_id){
			$check_mail = $this->db->where("email_id", $mail_id)->get($this->customers_model->table_name())->row();
			if($check_mail->id!=''  && $check_mail->id != $customere_id){
				$this->form_validation->set_message('mailid_unique', 'This Email id is already used!');
				return FALSE;
			}else{
				return true;
			}
			return true;
		}
		return false;
	}	
	
	public function change_password(){
		$this->check_login();
		
		$this->data['breadcrumbs']	= array('account/change_password/'=> 'Change Password');
		
		$customer_data = $this->session->userdata('customer');
		$customere_id 	= $customer_data['id'];
		
		$this->data['title'] = "Change Password";
		$this->data['page'] 	  = 'change_password';
		$this->data['title'] 	  = 'Change Password';
		
		$this->breadcrumb(array('account/change_password'=>'Change Password'));
		
		$this->theme->add_js(array("validate.js"));
		
		//$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('old_password', 'Old Password', 'required|callback_oldpassword_check');
		$this->form_validation->set_rules('new_password', 'Password', 'required|matches[c_password]');
		$this->form_validation->set_rules('c_password', 'Password Confirmation', 'required');
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->page('account/change_password');
		}else{
			if($_POST!=''){
				$update_password = $this->customers_model->change_password($customere_id);
				
				if($update_password){
					msg('Updated Your Password Successfully.', base_url("account/change_password"));
				}else{
					error_msg('Error! Password does not match' , base_url("account/change_password/"));
				}
			}
		}
		
	}
		
	public function oldpassword_check($old_password){
		$customer_data = $this->session->userdata('customer');
		$customere_id 	= $customer_data['id'];
		
	   $old_password_hash = ($old_password);
	   $old_password_db_hash = $this->customers_model->fetch_row_by_id($customere_id);
	   if($old_password_hash != $old_password_db_hash->password) {
		  $this->form_validation->set_message('oldpassword_check', 'Old Password not match');
		  return FALSE;
	   } 
	   return TRUE;
	}	
	
	
	
	public function listing(){
		$this->check_login();
		
		$customer_data = $this->session->userdata('customer');
		$customer_id 		= $customer_data['id'];
		
		$this->data['title'] = "Your Listing";
		$this->data['page'] = "listing";
		
		$this->breadcrumb(array('account/listing'=>'Your Listing'));
		
		$this->data['status_options'] 	= array(1=>"Approved", 2=>"Unapproved", 0=>"Disable");
		$this->data['listing'] 		= $this->db->where('customer_id', $customer_id)->get($this->profiles_model->table_name())->result();
		
		$this->page('account/listing');
	}
	
	
	
	public function listing_add($id=false){
		$this->check_login();
		
		$customer_data = $this->session->userdata('customer');
		$customer_id 		= $customer_data['id'];
		
		$this->data['title'] 	= "Add Listing";
		$this->data['page_name'] 	= "Add Listing";
		$this->data['page'] 		= "listing_add";
		$this->data['title'] 		= 'Add Listing';
		
		
		$this->breadcrumb(array('account/listing_add'=>'Add Listing'));
		
		$this->data['customer_d'] = (array)$this->customers_model->fetch_row_by_field("id", $customer_id);
		
		$listing 					= $this->profiles_model->fetch_row_by_field("id", $id);
		if($id){
			if(!isset($listing->id) || $listing->customer_id !=  $customer_id){
				redirect(base_url('account/listing'));
			}
			$this->data['title'] 		= 'Edit Listing';
		}
		
		$this->data['gallery_images'] 		= $this->profiles_model->gallery_images($id);
		
		$this->data['listing'] 			= $listing;
		$this->data['get_services'] 	= get_services('no');
		//$this->data['status_options']	= array(1=>"In Stock", 0=>"Out Of Stock");
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('mobile_no', 'Mobile No', 'required|trim');
		$this->form_validation->set_rules('email_id', 'Email ID', 'required|trim');
		$this->form_validation->set_rules('type', 'Type', 'required|trim');
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim');
		$this->form_validation->set_rules('age', 'Age', 'required|trim');
		
		//$this->form_validation->set_rules('eye_color', 'Eye color', 'required|trim');
		//$this->form_validation->set_rules('hair_color', 'Hair color', 'required|trim');
		//$this->form_validation->set_rules('height', 'Height', 'required|trim');
		//$this->form_validation->set_rules('weight', 'Weight', 'required|trim');
		//$this->form_validation->set_rules('language', 'Language', 'required|trim');
		//$this->form_validation->set_rules('ethnicity', 'Ethnicity', 'required|trim');
		//$this->form_validation->set_rules('nationality', 'Nationality', 'required|trim');
		$this->form_validation->set_rules('about_us', 'About Us', 'required|trim');
		
		$this->form_validation->set_error_delimiters('<span class="help-block help-block-pop">', '</span>');
		
		if ($this->form_validation->run() == FALSE){
			$this->page('account/listing_add');
		}else{
			$_POST['customer_id']	= $customer_id;
			if($id==''){
				$_POST['status']	= 2;
			}
			
			$save_user = $this->profiles_model->save($id);
			if($save_user){
				msg('Updated Your Listing Successfully.', base_url("account/listing"));
			}else{
				error_msg('Error' , base_url("account/listing/"));
			}
		}
		
		
		
	}
	
	public function enquiries(){
		$this->check_login();
		
		$customer_data = $this->session->userdata('customer');
		$this->data['title'] = "Enquiries";
		$this->data['page'] = "enquiries";
		
		$this->breadcrumb(array('account/enquiries'=>'Enquiries'));
		
		$customer_id 		= $customer_data['id'];
		
		$this->data['enquiries'] 		= $this->db->where('customer_id', $customer_id)->get($this->profile_enquiries_model->table_name())->result();
		
		$this->page('account/enquiries');
	
	}
	

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */