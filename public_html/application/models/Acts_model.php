<?php
class Acts_model extends WD_model{	

	function __construct(){
		$this->set_table_name("activities");
		$this->set_table_index("id");
	}
	
	public function act_statuses(){
		$statuses = array('activate'=>'Activate', 'add'=>'Add', 'cancel'=>'Cancel', 'delete'=>'Delete', 'login'=>'Login', 'logout'=>'Logout', 'new'=>'New', 'send'=>'Send', 'update'=>'Update');
		
	}
	
	public function act_status_string($key=false){
		
		$statuses = array('activate'=>'Activated', 'add'=>'Added New', 'cancel'=>'Cancelled', 'delete'=>'Deleted', 'login'=>'Login', 'logout'=>'Logout', 'new'=>'New', 'send'=>'Sent', 'update'=>'Updated');
		
		if($key && $key != '' && (array_key_exists($key, $statuses) && $statuses[$key] != '')){
			return $statuses[$key];
		}
	}
	
	function save_act($save=array()){
		
		$save['act_by_name'] = user_details('first_name');
		$save['act_user_email'] = user_details('useremail');
		$save['act_user_type'] = user_details('user_type');
		$save['act_user_id'] = user_details('id');
		
		$save['user_ip'] = $this->input->ip_address();
		
		$this->load->library('user_agent');
		if($this->agent->browser() != ''){
			$save['user_browser'] = $this->agent->browser();
		}
		if($this->agent->version() != ''){
			$save['user_browser_ver'] = $this->agent->version();
		}
		if($this->agent->platform() != ''){
			$save['user_os'] = $this->agent->platform();
		}
		
		$save['added_on'] 	= date('Y-m-d H:i:s');
		
		$this->db->insert($this->table_name(), $save);
		$id = $this->db->insert_id();
		return $id;
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
	
}
?>