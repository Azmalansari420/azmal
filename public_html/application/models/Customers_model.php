<?php
class Customers_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('customers');
		$this->set_table_index('id');
	}
	
	function get_customers(){
		return $this->db->where("status", '1')->get($this->table_name())->result();
	}
		
		
	function save($id=false){
		
		foreach($_POST as $key => $post){
			$save[$key] = $this->input->post($key, TRUE);
		}
		
		if($id){//update		
			$save['updated'] = date('Y-m-d H:i:s');
			//remove password field
			if(isset($save['password']) && $save['password'] == ''){
				unset($save['password']);
			}
			
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		}else{//new insert
			
			if(!isset($save['password']) || $save['password'] == ''){
				$save['password'] = (date('dHms'));
			}
			
			$vefiry_code 			= md5(date("hYimsd"));
			$save['vefiry_code']	= $vefiry_code;
			
			
			$save['added_on'] = date('Y-m-d H:i:s');
			$this->db->insert($this->table_name(), $save);
			$id = $this->db->insert_id();
			
			if(isset($save['add_by']) && $save['add_by']=='User'){
				$this->load->model('email_model');
				$vefiry_url = base_url("account/c/u/" . $id . "/ic/" . $vefiry_code . "/gc/" . md5("iyhd")); 
				$this->email_model->new_register_email($id, $vefiry_url);
			}
			
			return $id;
		}
	}
	
	public function validate_confirmation_code($id, $confirmation_code){
		$row = $this->db->where("id", $id)->where("vefiry_code", $confirmation_code)->get($this->table_name())->row();	
		if($row) return $row;
		return false;	
	}
	
	public function verify($save=array()){
		if(isset($save['id']) && $save['id'] != ''){
			$this->db->where('id', $save['id'])->update($this->table_name(), array('status'=>1, 'vefiry_code'=>'', 'updated'=>date('Y-m-d H:i:s')));
		}
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
		return true;
	}
	
	function total(){
		return $this->db->get($this->table_name())->num_rows();
	}
	
	function more_button($row){
		$status_options = array(1=>"Approved", 0=>"Unapproved", 2=>"Pending", 3=>"Rejected");
		$data = '<div>'.$status_options[$row->status].'</div>';
		$data .= '<a href="'.admin_url().'vcard/add/'.$row->id.'" class="btn btn-primary btn-mini btn-xs">Add VCard</a><br /><br />';
		return $data;
	}
	
	/////////front end functions
	function check_user_status($email_id=false){
		if($email_id){
			$check = $this->db->where('email_id' , $email_id)->get($this->table_name())->row();
			if(!$check){
				$check = $this->db->where('mobile_no' , $email_id)->get($this->table_name())->row();
			}
			return $check;
		}
		return false;
	}
	
	
	function user_login(){
		$email_id 				= trim($_POST['email_id']);
		$password 				= trim(($_POST['password']));	

		$check = (array)$this->db->where('email_id' , $email_id)->where('password', $password)->where('status', 1)->get($this->table_name())->row();		
		
		if(!$check){
			$check = (array)$this->db->where('mobile_no' , $email_id)->where('password', $password)->where('status', 1)->get($this->table_name())->row();		
		}
				
		if($check){			
			//check status
			$c_userdata = array(
	 			'id'  => $check['id'],
	   			'username' => $check['name'],
	   			'email_id' => $check['email_id'],
   			);
			$this->session->set_userdata('customer', $c_userdata);
			return $check;
		}else{
			return false;
		}
	}
	
	
	function forgot_password(){
		$email_id 	= trim($_POST['email_id']);
		$check = (array)$this->db->where('email_id' , $email_id)->get($this->table_name())->row();
		
		if(!$check){
			$check = (array)$this->db->where('mobile_no' , $email_id)->get($this->table_name())->row();
		}
		
		
		if($check){
			return $check['id'];
		}else{
			return false;
		}
	}
	
	
	function reset_password($id){
	
		$reset_password 			= ($id.'_'.date('dmYhms'));
		$reset_password 			= substr($reset_password,0,100);
		$_user['reset_password']	= $reset_password;
		$_user['reset_datetime']	= date('Y-m-d H:i:s');
			
		$this->db->where("id", $id)->update($this->customers_model->table_name(), $_user);	
		
		$this->load->model("email_model");	
		$mail = $this->email_model->customer_reset_password($id);
		return $mail;
	}
	
	function new_password($id){
		$save['password']			= trim($_POST['new_password']);
		if($id){
			//$save['password_readable'] = $save['password'];
			$save['password'] = ($save['password']);
			$save['reset_password'] = '';
			$save['reset_datetime'] = '';
			
			return $this->db->where("id", $id)->update($this->table_name(), $save);
		}else{
			return false;
		}
	}
	
	function get_customer_by_id($id=false){
		return $this->db->where('id', $id)->where('status', '1')->get($this->table_name())->row();
	}
	
	function get_customers_by_email_id($email_id=false){
		return $this->db->where('email_id', $email_id)->where('status', '1')->get($this->table_name())->result();
	}
	
	
	function change_password($id){
		$old_password 				= (trim($_POST['old_password']));
		$save['password']			= trim($_POST['new_password']);
		
		$check = $this->db->where('id',$id)->where('password',$old_password)->get($this->table_name())->row();
		
		if($check){
			if(isset($save['password']) && $save['password'] != ''){
				$save['password'] = ($save['password']);
			}
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $check;
		}else{
			return false;
		}
	}
}
?>