<?php
class Cities_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('cities');
		$this->set_table_index('id');
	}
	
	function get_cities($state=false){
		if($state && $state!=''){
			$this->db->where('state', $state);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("status", 1)->get($this->table_name())->result();
	}
	
	function get_cities_home_view($state=false){
		if($state && $state!=''){
			$this->db->where('state', $state);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("home_view", 1)->where("status", 1)->get($this->table_name())->result();
	}
	
	function get_cities_most_searched($state=false){
		if($state && $state!=''){
			$this->db->where('state', $state);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("most_searched", 1)->where("status", 1)->get($this->table_name())->result();
	}
	
	function get_cities_footer($state=false){
		if($state && $state!=''){
			$this->db->where('state', $state);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("status", 1)->where("footer", 1)->get($this->table_name())->result();
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
			//$this->set_post_data('slug', $slug);
			
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
	
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
	
	function upnumber(){
		$save['mobile_no']			= trim($_POST['mobile_no']);
		$save['whatapp_no']			= trim($_POST['mobile_no']);
		
		if(isset($_POST['city']) && $_POST['city']!=''){
			return $this->db->where("name", $_POST['city'])->update($this->table_name(), $save);	
		}
		
		return true;		
	}
}
?>