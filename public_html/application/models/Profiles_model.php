<?php
class Profiles_model extends WD_Model {
	
	function __construct(){
		$this->set_table_name('profiles');
		$this->set_table_index('id');
	}
	
	function gallery_table(){
		return 'profiles_gallery';
	}
	
	function save($id=false){
		$this->load->model('single_image_model');
		$single_image = $this->single_image_model->save_image('profiles');
		if(isset($single_image['image']) /*&& $single_image['image']!=''*/){
			$save['image'] = $single_image['image'];
			//$save['small_image'] = $single_image['small_image'];
		}
		
		
		foreach($_POST as $key => $post){
			$save[$key] = $this->input->post($key, TRUE);
		}
		
		
		if(isset($_POST['header_code']) && $_POST['header_code']!=''){
			$save['header_code'] = ($_POST['header_code']);
		}
		
		if(!$id){
			$this->load->library('app');
			$slug = $_POST['name'];
			$slug = (isset($_POST['slug'])) ? $_POST['slug'] : $this->app->slug($slug);
			//$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
			$save['slug'] = $slug;
		}

		
		unset($save['availability']);
		if(isset($_POST['availability'])){
			$save['availability'] = serialize($_POST['availability']);
		}
		
		unset($save['rate']);
		if(isset($_POST['rate'])){
			$save['rates'] = serialize($_POST['rate']);
		}
		
		unset($save['services']);
		if(isset($_POST['services'])){
			$save['services'] = implode('=', $_POST['services']);
		}
		
		if(isset($_POST['slug']) && $_POST['slug']!='' && $id){
			$save['slug'] = $_POST['slug'];
		}
		
		unset($save['sizes']);
		unset($save['gallery_count']);
		unset($save['delete']);
		unset($save['delete_url']);
		unset($save['saved_image']);
		unset($save['delete_image']);
		unset($save['url_image']);
		
		if($id){//update
			$save['updated'] = date("Y-m-d H:i:s");	
			$this->db->where("id", $id)->update($this->table_name(), $save);
			
			$this->save_gallery_images($id, $_POST['name']);
			return $id;
		}else{//new insert
			$save['added_on'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			$id = $this->db->insert_id();
			
			$this->save_gallery_images($id, $_POST['name']);
			
			if(!isset($save['sort_order'])){
				$save_u['sort_order'] = $id;
			}
			
			$save_u['slug'] = $save['slug'].'-'.($id+1000);
			$this->db->where("id", $id)->update($this->table_name(), $save_u);
			return $id;
		}
	}
	
	
	
	function save_gallery_images($profile_id=false, $name=false){
		//print_r($_POST);exit;
		$gallery_images = array();
		$n = 0;
		
		if(isset($_POST['delete'])){
			foreach($_POST['delete'] as $delete_id => $value){
				//remove gallery image
				$images = $this->db->where('id', $delete_id)->get($this->gallery_table())->row();
				unlink(abs_path('/media/uploads/').$_POST['delete_url'][$delete_id]);
				unlink(abs_path('/media/uploads/').$images->image);
				$this->db->where('id', $delete_id)->delete($this->gallery_table());
			}
		}	
		
		
		//Saved Images
		$not_remove = array();
		if(isset($_POST['saved_image'])){
			foreach($_POST['saved_image'] as $image_id => $value){
				$not_remove[] = $image_id;
			}
		}	
		
		$config['allowed_types'] = 'gif|jpg|jpeg|swf|png';
		$config['max_size']	= '100000000';
		$config['overwrite'] = false;
		$this->load->library('upload', $config);
		
		for($i=1; $i <= $_POST['gallery_count']; $i++){
			if($_FILES['gallery_'.$i]['tmp_name'] != ''){
				
				$n++;
				$image_name = pathinfo($_FILES['gallery_'.$i]['name']);
				$upload_path = "/profiles/";
				$base_path = "media/uploads".$upload_path;
				$dir_path = random_dir(abs_path($base_path))."/";

				$config['file_name'] = strtolower(clean_unique_code($image_name['filename'], '-').".".$image_name['extension']);
				$config['upload_path'] = $base_path.$dir_path;
				$config['allowed_types'] = 'gif|jpg|jpeg|swf|png';
				$config['max_size']	= '10000';
				$config['overwrite'] = false;
				
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('gallery_'.$i)){

					$error = $this->upload->display_errors();
					//$this->load->view('upload_form', $error);
					//error_msg($error, admin_url("products"));
					error_msg($error, $_SERVER['HTTP_REFERER']);
				}else{
					$data = $this->upload->data();
					$uploaded_image_name = pathinfo($data['file_name']);

					$image_abs_path = abs_path().$base_path.$dir_path.$data['file_name'];
					$gallery_images[$n]['image_url'] = $upload_path.$dir_path.$data['file_name'];
				}	
			}
		}

		//delete all gallery images
		if(count($not_remove) > 0){
			$this->db->where('profile_id', $profile_id)->where_not_in('id', $not_remove)->delete($this->gallery_table());
		}else{
			$this->db->where('profile_id', $profile_id)->delete($this->gallery_table());
		}	
		
		//update gallery
		foreach($gallery_images as $images){
			$this->db->insert($this->gallery_table(), array('profile_id'=>$profile_id, 'image_url'=>$images['image_url'], 'name'=>$name, 'status'=>1, 'added_on'=>date("Y-m-d H:i:s"),  'sort_order'=>'0', 'updated'=>date("Y-m-d H:i:s") ));
		}
	}
	
	
	function gallery_images($profile_id){
		return $this->db->where('profile_id', $profile_id)->get($this->gallery_table())->result();
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
		
		return true;
	}
		
	
	function get_cities($state=false){
		if($state && $state!=''){
			$this->db->where('state', $state);
		}
		
		return $this->db->order_by("sort_order", 'asc')->where("status", 1)->get($this->table_name())->result();
	}
	
	function total(){
		return $this->db->get($this->table_name())->num_rows();
	}
	
	function image_view($row){
		$data = '';
		if($row->image!=''){
			$url = str_replace('orderadmin/', '', base_url());
			$data = '<img src="'.$url.'media/uploads'.$row->image.'" style="width:100px;">';
		}
		return $data;
	}
	
	
	
	function upnumber(){
		$save['mobile_no']			= trim($_POST['mobile_no']);
		$save['whatapp_no']			= trim($_POST['mobile_no']);
		
		if(isset($_POST['locality']) && $_POST['locality']!=''){
			return $this->db->where("locality", $_POST['locality'])->update($this->table_name(), $save);	
		}elseif(isset($_POST['city']) && $_POST['city']!=''){
			return $this->db->where("city", $_POST['city'])->update($this->table_name(), $save);	
		}
		
		return true;		
	}
	
	function up_numbers($type, $value){
		$save			= array();
		if(isset($_POST['p_mobile_no']) && $_POST['p_mobile_no']!=''){
			$save['mobile_no']			= trim($_POST['p_mobile_no']);
		}
		
		if(isset($_POST['p_whatapp_no']) && $_POST['p_whatapp_no']!=''){
			$save['whatapp_no']			= trim($_POST['p_whatapp_no']);
		}
		
		if(count($save)!=0){
			return $this->db->where($type, $value)->update($this->table_name(), $save);	
		}
		return true;		
	}

	
	
	function listing($filters=false, $per_page=10, $page=1, $total=false){
		
		if(isset($filters['state']) && $filters['state'] != ''){
			$this->db->where('state', $filters['state']);
		}
		
		if(isset($filters['city']) && $filters['city'] != ''){
			$this->db->where('city', $filters['city']);
		}
		
		if(isset($filters['locality']) && $filters['locality'] != ''){
			$this->db->where('locality', $filters['locality']);
		}
		
		if(isset($filters['gender']) && $filters['gender'] != ''){
			$this->db->where('gender', $filters['gender']);
		}
		
		if(isset($filters['type']) && $filters['type'] != ''){
			$this->db->where('type', $filters['type']);
		}
		
		if(isset($filters['age']) && $filters['age'] != ''){
			//$this->db->where('age', $filters['age']);
			$age_e		= explode('-', $filters['age']);
			$this->db->where('age >=',$age_e[0])->where('age >=',$age_e[1]);
		}
		
		
		if(isset($filters['short']) &&  $filters['short']!=''){
			$short		= $filters['short'];
			if($short!='views'){
				$this->db->order_by("id", $short);	
			}else{
				$this->db->order_by("viewed", 'desc');
			}
		}else{
			//$this->db->order_by("sort_order", 'asc');
			$this->db->order_by("id", 'random');
		}
		
		if(isset($total) && $total!=''){
			$listing = $this->db->where('status', '1')->get($this->table_name())->num_rows();
		}else{
			$from = ($per_page * $page) - $per_page;
			$to = ($per_page * $page);
			$this->db->limit($per_page, $from);
			
			$listing = $this->db->where('status', '1')->get($this->table_name())->result();
			
		}
		return $listing;
	}
}
?>