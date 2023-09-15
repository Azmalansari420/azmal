<?php
class Keywords_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('keywords');
		$this->set_table_index('id');
	}
	
	function get_keywords($city=false){
		if($city && $city!=''){
			$this->db->where('city', $city);
		}
		return $this->db->order_by("id", 'desc')->where("status", 1)->get($this->table_name())->result();
	}
	
	function get_keywords_by_state($state){
		return $this->db->order_by("id", 'desc')->where('state', $state)->where("status", 1)->get($this->table_name())->result();
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