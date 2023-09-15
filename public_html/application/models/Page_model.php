<?php
class Page_model extends WD_Model {

	private $gallery_table;
	function __construct(){
		$this->set_table_name('page');
		$this->set_table_index('id');

		$this->gallery_table = 'page_gallery';
	}
	
	function getPageById($id){
		return $this->db->where("id", $id)->get($this->table_name())->row();
	}
	
	function get_by_slug($slug){
		return $this->db->where("slug", trim($slug))->get($this->table_name())->row();
	}
	

	function gallery_images($page_id){
		return $this->db->where('page_id', $page_id)->get($this->gallery_table)->result();
	}
	
	function gallery_rand_images($page_id){
		return $this->db->where('page_id', $page_id)->order_by('id', 'RANDOM')->limit(1)->get($this->gallery_table)->row();
	}
	
	function save_gallery_images($page_id=false){
		
		//print_r($_POST);exit;
		$gallery_images = array();
		$n = 0;
		
		if(isset($_POST['delete'])){
			foreach($_POST['delete'] as $delete_id => $value){
				//remove gallery image
				$images = $this->db->where('id', $delete_id)->get($this->gallery_table)->row();
				unlink(abs_path('/media/uploads/').$_POST['delete_url'][$delete_id]);
				unlink(abs_path('/media/uploads/').$images->image);
				$this->db->where('id', $delete_id)->delete($this->gallery_table);
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
		$config['max_size']	= '1000000';
		$config['overwrite'] = false;
		$this->load->library('upload', $config);
		
		for($i=1; $i <= $_POST['gallery_count']; $i++){
			if($_FILES['gallery_'.$i]['tmp_name'] != ''){
				
				$n++;
				$image_name = pathinfo($_FILES['gallery_'.$i]['name']);
				$upload_path = "/page_images/";
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
					error_msg($error, admin_url("page"));

				}else{
					$data = $this->upload->data();
					$uploaded_image_name = pathinfo($data['file_name']);

					$image_abs_path = abs_path().$base_path.$dir_path.$data['file_name'];

					//$small_image_name = strtolower(clean_unique_code($uploaded_image_name['filename'], '-')."_sm.".$uploaded_image_name['extension']);
					
					//$small_image_abs_path = abs_path().$base_path.$dir_path.$small_image_name;
					//image_resize($image_abs_path, array('height'=>300, 'width'=>300), true, $small_image_abs_path);
					
					$gallery_images[$n]['image_url'] = $upload_path.$dir_path.$data['file_name'];
					//$gallery_images[$n]['thumb'] = $upload_path.$dir_path.$small_image_name;
					
					//$offer_id
				}	
			}
		}

		//delete all gallery images
		if(count($not_remove) > 0){
			$this->db->where('page_id', $page_id)->where_not_in('id', $not_remove)->delete($this->gallery_table);

		}else{
			$this->db->where('page_id', $page_id)->delete($this->gallery_table);
		}	
		
		//update gallery
		foreach($gallery_images as $images){
			$this->db->insert($this->gallery_table, array('page_id'=>$page_id, 'image_url'=>$images['image_url'], 'added_on'=>date("Y-m-d H:i:s") ));
		}
	}
	
	
	function save($id=false){		
		$save['type'] = $this->input->post('type', TRUE);
		$save['identifier'] = clean_unique_code($this->input->post('identifier', TRUE));

		if(!$id){
			$this->load->library('app');
			$slug = (isset($_POST['slug'])) ? $_POST['slug'] : $this->app->slug($_POST['title']);

			//Validate slug]
			$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
			$save['slug'] = $slug;
		}
		if(isset($_POST['slug']) && $id){
			$save['slug'] = $_POST['slug'];
		}
		
		//$save['slug'] = (isset($_POST['slug'])) ? clean_unique_code($this->input->post('slug', TRUE), '-') : clean_unique_code($this->input->post('title', TRUE), '-');
		
		$save['title'] = $this->db->escape_str($this->input->post('title', TRUE));
		
		$content = htmlspecialchars_decode(htmlentities($_POST['content'], ENT_QUOTES, 'UTF-8'));
		$save['content'] = $content;
		
		$save['template'] = $_POST['template'];
		
		$save['meta_title'] = clean_insert($this->input->post('meta_title', TRUE));
		$save['meta_description'] = clean_insert($this->input->post('meta_description', TRUE));
		$save['meta_keywords'] = clean_insert($this->input->post('meta_keywords', TRUE));
		$save['status'] = $this->input->post('status', TRUE);
		
		//append page- prefix
		//$save['slug'] = "page-".trim($save['slug']);
			
		if($id){//update
			$save['updated_at'] = date("Y-m-d H:i:s");
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		
		}else{//new insert
			$save['created_at'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			return $this->db->insert_id();
		}
	}
	
	
	function save_help_content($id=false){
		
		$save['type'] = 'help-support';
		//$save['identifier'] = clean_unique_code($this->input->post('identifier', TRUE));
		
		if(!$id){
		
			$this->load->library('app');
			$slug = (isset($_POST['slug'])) ? $_POST['slug'] : $this->app->slug($_POST['title']);

			$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
			$save['slug'] = $slug;
		}
		if(isset($_POST['slug']) && $id){
			$save['slug'] = $_POST['slug'];
		}
		
		//$save['slug'] = (isset($_POST['slug'])) ? clean_unique_code($this->input->post('slug', TRUE), '-') : clean_unique_code($this->input->post('title', TRUE), '-');
		$save['title'] = clean_insert($this->input->post('title', TRUE));
		$save['content'] = clean_insert($this->input->post('content', TRUE));
		//$save['template'] = $_POST['template'];
		
		$save['meta_title'] = clean_insert($this->input->post('meta_title', TRUE));
		$save['meta_description'] = clean_insert($this->input->post('meta_description', TRUE));
		$save['meta_keywords'] = clean_insert($this->input->post('meta_keywords', TRUE));
		$save['status'] = $this->input->post('status', TRUE);
		if(isset($_POST['sort_order']) && $_POST['sort_order'] != ''){
			$save['sort_order'] = $this->input->post('sort_order', TRUE);
		}	
		
		if($id){//update
		
			$save['updated_at'] = date("Y-m-d H:i:s");
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		
		}else{//new insert
		
			$save['created_at'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			return $this->db->insert_id();
		
		}
	}
	

	public function get_listing_content($identifier=false){
		return $this->db->where('identifier', $identifier)->where('type', 'business_h')->get($this->table_name())->row();
	}
	
	
	function saveHtmlBlock($id=false){
		
		$save['type'] = $this->input->post('type', TRUE);
		$save['identifier'] = clean_unique_code($this->input->post('identifier', TRUE));
		$save['content'] = clean_insert($this->input->post('content', TRUE));
		
		$save['status'] = $this->input->post('status', TRUE);
			
		if($id){//update
		
			$save['updated_at'] = date("Y-m-d H:i:s");
			$this->db->where("id", $id)->update($this->table_name(), $save);
			msg($save['identifier'] . " Html Block updated succussfully");
			return $id;
		
		}else{//new insert
		
			$save['created_at'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			msg($save['identifier'] . " Html Block saved succussfully");
			return $this->db->insert_id();
		}
	}
	

	function savePhpBlock($id=false){
		
		$save['type'] = $this->input->post('type', TRUE);
		$save['identifier'] = clean_unique_code($this->input->post('identifier', TRUE));
		$save['content'] = clean_insert($this->input->post('content', TRUE));
		$save['status'] = $this->input->post('status', TRUE);
			
		if($id){//update
			$save['updated_at'] = date("Y-m-d H:i:s");
			$this->db->where("id", $id)->update($this->table_name(), $save);
			msg($save['identifier'] . " Php Block updated succussfully");
			return $id;
		
		}else{//new insert
			$save['created_at'] = date("Y-m-d H:i:s");
			$this->db->insert($this->table_name(), $save);
			msg($save['identifier'] . " Php Block saved succussfully");
			return $this->db->insert_id();
		}
	}

	
	function slug($row){
		return $this->slug_trimmed($row->slug);
	}
	

	function slug_trimmed($slug){
		return ltrim(ltrim(trim($slug), 'page'), '-');
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
}
?>