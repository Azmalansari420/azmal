<?php
class Locality_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('locality');
		$this->set_table_index('id');
	}
	
	function get_locality($city=false){
		if($city && $city!=''){
			$this->db->where("city", $city);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("status", 1)->get($this->table_name())->result();
	}
		
		
	function save($id=false){
		foreach($_POST as $k => $v){
			$save[$k] = $v;
		}	
		
		if(!$id){
			$this->load->library('app');
			$slug = (isset($_POST['slug'])) ? $_POST['slug'] : $this->app->slug($_POST['name']);

			//Validate slug]
			$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
			//$this->set_post_data('slug', $slug
			$save['slug'] = $slug;
		}
		if(isset($_POST['slug']) && $id){
			//$this->post_data('slug', 'slug');
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
	
	function upnumber(){
		$save['mobile_no']			= trim($_POST['mobile_no']);
		$save['whatapp_no']			= trim($_POST['mobile_no']);
		
		if(isset($_POST['locality']) && $_POST['locality']!=''){
			return $this->db->where("name", $_POST['locality'])->update($this->table_name(), $save);	
		}
		
		return true;		
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
}
?>