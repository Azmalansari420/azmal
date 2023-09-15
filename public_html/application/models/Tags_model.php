<?php
class Tags_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('tags');
		$this->set_table_index('id');
	}
	
	function get_tags(){
		return $this->db->where("status", '1')->get($this->table_name())->result();
	}
		
		
	function save($id=false){
		foreach($_POST as $k => $v){
			$save[$k] = $v;
		}	
		
		if($id){//update
			$save['updated'] = date("Y-m-d H:i:s");
			
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		
		}else{//new insert
			$save['added_on'] = date("Y-m-d H:i:s");
			if($this->db->insert($this->table_name(), $save)){
				$id = $this->db->insert_id();
				return $id;
			}
			return false;
		}
	}
	
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
}
?>