<?php
class Settings_model extends WD_model {
	
	function __construct(){
		$this->set_table_name("settings");
		$this->set_table_index('id');
	}
	
	public function get_by_id($id=false){
		if($id){
			return $this->db->where("id", $id)->get($this->table_name())->row();
		}
		return false;
	}
	
	public function get_by_key($key=false){
		if($key){
			$query = $this->db->where("key", $key)->get($this->table_name());
			
			//if($query->num_rows() > 0){
				return $query->row();
			//}	
		}
		return false;
	}
	
	public function get_value_by_key($key=false){
		if($key){
			$data = $this->get_by_key($key);
			if($data){
				return $data->value;
			}	
		}
		return false;
	}
	
	function save($id=false){
		//$save['adv_id'] = $_POST['adv_id'];
		$save['title'] = clean_insert($this->input->post('title', TRUE));
		$save['setting_type'] = $_POST['setting_type'];
		//$save['value'] = addslashes($_POST['value']);
		$save['value'] = ($_POST['value']);
			
		if($id){//update
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		
		}else{//new insert
			$save['key'] = $_POST['key'];
			$save['added_on'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			return $this->db->insert_id();
		}
	}

	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
}
?>