<?php
class Banner_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('banners');
		$this->set_table_index('id');
	}
	
	function get_banners(){
		return $this->db->where("status", '1')->get($this->table_name())->result();
	}
		
		
	function save($id=false){
		if(isset($_POST['delete_image']) && $_POST['delete_image']==1){

			$_POST['image'] = '';
			if(isset($id)){
				$banner = $this->fetch_row_by_id($id);
				unlink(abs_path('/media/uploads/').$banner->image);
			}
		}
	
		if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
			$file_name = pathinfo($_FILES['image']['name']);
			
			$upload_path = "/banners/";
			$base_path = "media/uploads".$upload_path;
			$dir_path = random_dir(abs_path($base_path))."/";

			$config['file_name'] = strtolower(clean_unique_code($file_name['filename'], '-').".".$file_name['extension']);
			//$config['file_name'] = strtolower($id.'-'.'photograph'.'.'.strtolower($file_name['extension']));
			$config['upload_path'] = $base_path.$dir_path;
			$config['allowed_types'] = 'gif|jpg|jpeg|bmp|png';
			$config['max_size']	= '150000';
			$config['overwrite'] = false;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('image')){

				$error = $this->upload->display_errors();
				error_msg($error, admin_url("banners"));
				

			}else{
				$data = $this->upload->data();
				$file_name = pathinfo($data['file_name']);

				$_POST['image'] = $upload_path.$dir_path.$data['file_name'];
			}
		}
		unset($_POST['delete_image']);
		unset($_POST['url_image']);
		
		foreach($_POST as $k => $v){
			$save[$k] = $v;
		}		
		if($id){//update
			$save['updated_on'] = date("Y-m-d H:i:s");
			
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