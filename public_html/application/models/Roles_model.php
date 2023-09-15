<?php
class Roles_model extends WD_model {
	
	function __construct(){
		$this->set_table_name("users_roles");
		$this->set_table_index("id");
	}
	
	function getDataById($id){
		return $this->db->where("id", $id)->get($this->table_name())->row();
	}
	
	function fetch_row_by_key($role_key=false){
		if($role_key){
			$role_details = $this->db->where("role_key", $role_key)->get($this->table_name());
			if($role_details->num_rows() > 0){
				return $role_details->row();
			}
		}
		return false;
	}
	
	function save($id=false){

		$this->post_data('role_title', 'role_title');
		$this->post_data('role_key', 'role_key');
		$this->_post_data['permissions'] = json_encode($_POST['permissions']);
		$this->_post_data['fields'] = json_encode($_POST['fields']);
		$this->_post_data['actions'] = json_encode($_POST['actions']);
		
		if($id){//update
			$this->post_update($id, 'id');
			
			$act = array();
			$act["module"] = "roles";
			$act["act_type"] = "role_update";
			$act["act"] = "roles_details_update";
			$act["act_action"] = json_encode($save);
			$act["act_status"] = "update";
			$act["act_key"] = "id";
			$act["act_value"] = trim($id);
			
			//$this->acts_model->save_act($act);
			
			return $id;
		}else{//new insert
			$id = $this->post_save();
			//$id = $this->db->insert_id();
			
			$act = array();
			$act["module"] = "roles";
			$act["act_type"] = "role_create";
			$act["act"] = "new_role_created";
			$act["act_action"] = json_encode($save);
			$act["act_status"] = "new";
			$act["act_key"] = "id";
			$act["act_value"] = trim($id);
			
			//$this->acts_model->save_act($act);
			
			return $id;
		}
	}

	//This function checks which parts user can access
	function get_user_permissions($roles=false){
		$permissions = (array)$this->db->select('permissions')->where('role_key', $roles)->get($this->table_name())->row();
		$modules = json_decode($permissions['permissions'], true);
		return $modules;
	}

	function get_user_fields($roles=false){
		$fields = (array)$this->db->select('fields')->where('role_key', $roles)->get($this->table_name())->row();
		$fields = json_decode($fields['fields'], true);
		return $fields;
	}

	function get_user_actions($roles=false){
		$actions = (array)$this->db->select('actions')->where('role_key', $roles)->get($this->table_name())->row();
		$actions = json_decode($actions['actions'], true);
		return $actions;
	}
	
	function permissions($row){
		$permissions = '';
		if($row->permissions != '' && $row->permissions != 'null'){
			$_permissions = json_decode($row->permissions, true);
			foreach($_permissions as $_permission){
				if(is_array($_permission)){
					$t = '';
					foreach($_permission as $p){
						$t[] = ucwords($p);
					}	
					$permissions .= implode(", ", $t)."<br />";
				}else{
					$permissions .= ucwords($_permission)."<br />";
				}	
			}
		}	
		return $permissions;
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
	
	function logout(){
		$this->load->library('session');
		
		$this->session->unset_userdata('teacher');
	}
}
?>