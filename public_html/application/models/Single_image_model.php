<?php
class Single_image_model extends WD_model {
	function __construct(){

	}
	
	function save_image($image_to=false, $gallery=false){
		$image_path = array();
		$config['allowed_types'] = 'gif|jpg|jpeg|swf|png|mp4|MP4';
		$config['max_size']	= '1000000000000';
		$config['overwrite'] = false;
		$this->load->library('upload', $config);
		
		if(isset($_POST['delete_image']) && $_POST['delete_image']=='1'){ //sent only when deleting image
			unlink(abs_path('/media/uploads/').$_POST['url_image']);	//"url_image_url"= url_"sent variable" and deleting image
			$save['image'] = '';
			$save['small_image'] = '';	
		}
		
		if(isset($_POST['delete_image']) && $_POST['delete_image']=='1' && $_FILES['image']['tmp_name'] == ''){
			$save['image'] = '';
			$save['small_image'] = '';	
			unset($_POST['delete_image']);
			unset($_POST['url_image']);
			return $save;
		}
		
			
		unset($_POST['delete_image']);
		unset($_POST['url_image']);
		if($_FILES['image']['tmp_name'] != ''){
		//$n++;
			$image_name = pathinfo($_FILES['image']['name']);
			$upload_path = "/".$image_to.'/';
			$base_path = "media/uploads".$upload_path;
			$dir_path = random_dir(abs_path($base_path))."/";

			$config['file_name'] = strtolower(clean_unique_code($image_name['filename'], '-').".".$image_name['extension']);
			$config['upload_path'] = $base_path.$dir_path;
			$config['allowed_types'] = 'gif|jpg|jpeg|swf|png|mp4|MP4';
			$config['max_size']	= '1000000000000';
			$config['overwrite'] = false;
			
			$this->upload->initialize($config);

			if(!$this->upload->do_upload('image')){

				$error = $this->upload->display_errors();
				//$this->load->view('upload_form', $error);
				error_msg($error, $_SERVER['HTTP_REFERER']);

			}else{
				if($gallery && $gallery!=''){
					$data = $this->upload->data();
					$uploaded_image_name = pathinfo($data['file_name']);
					$image_abs_path = abs_path().$base_path.$dir_path.$data['file_name'];

					$gallery_images['image'] = $upload_path.$dir_path.$data['file_name'];
					
					$uploaded_image_info = pathinfo($data['file_name']);

					//resize thumb
					/*$thumb_file_name = clean_unique_code(trim($uploaded_image_info['filename']), '-')."-thumb".".".$uploaded_image_info['extension'];
					$thumb_image_abs_path = abs_path().$base_path.$dir_path.$thumb_file_name;
					image_resize($image_abs_path, array('height'=>260, 'width'=>730), false, $thumb_image_abs_path);
					
					$gallery_images['thumb'] = $upload_path.$dir_path.$thumb_file_name;
					
					//resize sm
					$sm_file_name = clean_unique_code(trim($uploaded_image_info['filename']), '-')."-sm".".".$uploaded_image_info['extension'];
					$sm_image_abs_path = abs_path().$base_path.$dir_path.$sm_file_name;
					image_resize($image_abs_path, array('height'=>150, 'width'=>150), true, $sm_image_abs_path);
					
					$gallery_images['small_image'] = $upload_path.$dir_path.$sm_file_name;*/
					
					return $gallery_images;
					
				}else{
					
					
					$data = $this->upload->data();
					$uploaded_image_name = pathinfo($data['file_name']);
					$image_abs_path = abs_path().$base_path.$dir_path.$data['file_name'];
					
					//small_image_name
					/*$small_image_name = strtolower(clean_unique_code($uploaded_image_name['filename'], '-')."_sm.".$uploaded_image_name['extension']);
					
					//small image
					//image_resize($image_abs_path, array('height'=>445, 'width'=>458), true, $image_abs_path);
					$small_image_abs_path = abs_path().$base_path.$dir_path.$small_image_name;
					image_resize($image_abs_path, array('height'=>300, 'width'=>400), true, $small_image_abs_path);
					
	
					//$image_path[$i]['image'] = $upload_path.$dir_path.$data['file_name'];
					
					$save['small_image'] = $upload_path.$dir_path.$small_image_name;*/
					
					
					$save['image'] = $upload_path.$dir_path.$data['file_name'];
				
				
					return $save;
					
				
				}
				
			}	
		}else{
		
		
			return true;
		}
	
	
	}
	
	
}
?>