<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('t_to_h')){
	function t_to_h($hr){
		//check if its more than hrs
		$hr = number_format($hr, 2);
		if(strstr($hr, '.')){
			$t = explode('.', $hr);
			$s = (60 / 100) * $t[1];
			if($s >= 60){
				$td = $s - 60; 
				$ht = $t[0] + 1;
				
				if($td < 10){
					$td = "0".ltrim($td, '0');
				}
				if($ht < 10){
					$ht = "0".ltrim($ht, '0');
				}
				$ft = $ht.':'.$td;
			}else{
				$td = $s;
				if($s < 10){
					$td = "0".ltrim($s, '0');
				}
				$ht = $t[0];
				if($t[0] < 10){
					$ht = "0".ltrim($t[0], '0');
				}
				$ft = $ht.':'.$td;
			}
		}else{
			if($hr < 10){
				$hr = "0".ltrim($hr, '0');
			}
			$ft = $hr.":00";
		}
		return date('h:ia', strtotime($ft));
	}
}

if(!function_exists("timestamp")){

	function timestamp($time, $timeBase = false) {
    
		$time = strtotime($time);
		if (!$timeBase) {
    	    $timeBase = time();
    	}
	
	    if ($time <= time()) {
	        $dif = $timeBase - $time;
	
	        if ($dif < 60) {
	            if ($dif < 2) {
	                return "1 second ago";
	            }
	
	            return $dif." seconds ago";
	        }
	
	        if ($dif < 3600) {
	            if (floor($dif / 60) < 2) {
	                return "A minute ago";
	            }
	
	            return floor($dif / 60)." minutes ago";
	        }
	
	        if (date("d n Y", $timeBase) == date("d n Y", $time)) {
	            return "Today, ".date("g:i A", $time);
	        }
	
	        if (date("n Y", $timeBase) == date("n Y", $time) && date("d", $timeBase) - date("d", $time) == 1) {
	            return "Yesterday, ".date("g:i A", $time);
	        }
	
	        if (date("Y", $time) == date("Y", time())) {
	            return date("F, jS g:i A", $time);
	        }
	    } else {
	        $dif = $time - $timeBase;
	
	        if ($dif < 60) {
	            if ($dif < 2) {
	                return "1 second";
	            }
	
	            return $dif." seconds";
	        }
	
	        if ($dif < 3600) {
	            if (floor($dif / 60) < 2) {
	                return "Less than a minute";
	            }
	
	            return floor($dif / 60)." minutes";
	        }
	
	        if (date("d n Y", ($timeBase + 86400)) == date("d n Y", ($time))) {
	            return "Tomorrow, at ".date("g:i A", $time);
	        }
	    }
	
	    return date("F, jS g:i A Y", $time);
	}
}

if(!function_exists('validate_url')){
	function validate_url($url=false){
		$ci = ci();
		if($url){
			if(in_array(parse_url($url, PHP_URL_SCHEME),array('http','https'))){
				if(!filter_var($url, FILTER_VALIDATE_URL)) { 
					return false;
				}else{
					if(substr_count($url, '.') >= 2){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
		return false;
	}
}

/* Image from video code */
//https://img.youtube.com/vi/CSOOEn-y3Zo/0.jpg
//https://img.youtube.com/vi/CSOOEn-y3Zo/1.jpg
//https://img.youtube.com/vi/<insert-youtube-video-id-here>/2.jpg
//https://img.youtube.com/vi/<insert-youtube-video-id-here>/3.jpg

if(!function_exists('youtube_id')){
	function youtube_id($link=false){
		$youtubeid = false;
		if( stristr($link, "/v/") ){// checking for this format - http://www.youtube.com/v/[videoid]
			$brk = explode("/v/", $link);
			$brk1 = explode("&", $brk[1]);
			$youtubeid = $brk1[0];
				
		}elseif(stristr($link, "watch?v")){ // checking for this format - http://www.youtube.com/watch?v=[videoid]
			parse_str( parse_url($link, PHP_URL_QUERY), $result);
			if($result['v'] != ''){
				$youtubeid = $result['v']; 
			}
		}elseif(stristr($link, "youtu.be/")){ // sharelink video - http://youtu.be/kwkOgzwjo_c
			$brk = explode("youtu.be/", $link);
			$youtubeid = $brk[1];
		}else{//http://www.youtube.com/watch?feature=player_embedded&v=9DefTq84_sw
			parse_str( parse_url($link, PHP_URL_QUERY), $result);
			if($result['v'] != ''){
				$youtubeid = $result['v']; 
			}
		}
		return $youtubeid;
	}
}

function findSharp($orig, $final){// function from Ryan Rud (http://adryrun.com)
	$final	= $final * (750.0 / $orig);
	$a		= 52;
	$b		= -0.27810650887573124;
	$c		= .00047337278106508946;
	
	$result = $a + $b * $final + $c * $final * $final;
	
	return max(round($result), 0);
}

if(!function_exists('image_resize')){
	function image_resize($filename, $sizes, $crop=false, $output=false, $do_sharp=true, $display=false){
	
		$size = getimagesize($filename);
		$width = $size[0];
		$height = $size[1];
		$type = $size['mime'];
		
		//If original dism lesser than desired dims
		{
			if(isset($sizes['width']) && isset($sizes['height'])){
				if( ($sizes['width'] >= $width) && ($sizes['height'] >= $height) ){
					if($output){
						$save = $output;
						copy($filename, $output);
						return;
					}else{
						$save = null;
						header('Content-Type: '.$type);
						echo file_get_contents($filename);
						return;
					}	
				}
			}
		}
		
		$ratioComputed		= $width / $height;
		$cropRatioComputed	= (float) 1 / 1;
		
		$offsetX = 0;
		$offsetY = 0;
		
		if($crop){
			if ($ratioComputed < $cropRatioComputed){ // Image is too tall so we will crop the top and bottom
				$origHeight	= $height;
				$height		= $width / $cropRatioComputed;
				$offsetY	= ($origHeight - $height) / 2;
			}else if ($ratioComputed > $cropRatioComputed){ // Image is too wide so we will crop off the left and right sides
				$origWidth	= $width;
				$width		= $height * $cropRatioComputed;
				$offsetX	= ($origWidth - $width) / 2;
			}
		}	
		
		$newwidth = $sizes['width'];
		
		$percentChange = $newwidth / $width;
		$newheight = round(($percentChange*$height));
		$_n_height = $newheight;
		
		if(!$crop){
			if(isset($sizes['height']) && $sizes['height'] != ''){
				if($newheight < $sizes['height']){
					$_n_height = $newheight;
				}else{
					$_n_height = $sizes['height'];
				}
			}
			
			if($width > $height && $newheight < $height){
    			$newheight = $height / ($width / $newwidth);
			}elseif($width < $height && $newwidth < $width){
    			$newwidth = $width / ($height / $newheight);
			}else{
				$newwidth = $width;
				$newheight = $height;
			}
		}else{
			$newwidth = $sizes['width'];
			$newheight = $sizes['height'];
			$_n_height = $newheight;
		}	
		
		$image_f = "imagejpeg";
		if($type == 'image/jpg' || $type == 'image/jpeg'){
			$source = imagecreatefromjpeg($filename);
			$image_f = "imagejpeg";
			$quality = 100;
				
		}elseif($type == 'image/png'){
			$source = imagecreatefrompng($filename);
			$image_f = "imagepng";
			$quality = 9;
			
		}elseif($type == 'image/gif'){
			$source = imagecreatefromgif($filename);
			$image_f = "imagepng";
			$quality = 8;
		}
		
		$thumb = imagecreatetruecolor($newwidth, $_n_height);
		
		if($type == 'image/png'){

			imagealphablending($thumb, false);
			imagesavealpha($thumb, true);

			$transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			imagefill( $thumb, 0, 0, $transparent );
			
		}else{
			//Fill white background
			$white = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb, 0, 0, $white);
		}

		//imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		
		imagecopyresampled($thumb, $source, 0, 0, $offsetX, $offsetY, $newwidth, $newheight, $width, $height);
	
		if($do_sharp){
			$sharpenMatrix = array( array(-0,-0,-0), array(-1,16,-1), array(-1,-1,-1));
			
			$divisor		= array_sum(array_map('array_sum', $sharpenMatrix));  //$sharpness;
			$offset			= 0;
			imageconvolution($thumb, $sharpenMatrix, $divisor, $offset);
		}
	
		if($output){
			$save = $output;
		}else{
			$save = null;
			header('Content-Type: '.$type);
		}
		
		if($type == 'image/png'){
		
			$image_f($thumb, $save, $quality, PNG_ALL_FILTERS);
		}else{

			$image_f($thumb, $save, $quality);
		}
		imagedestroy($thumb);
	}
}

if(!function_exists('upload')){
	function upload($field=false, $path="", $_config=array()){
		
		$allowed_types = ($_config['allowed_types'] && $_config['allowed_types'] != '') ? : 'gif|jpg|jpeg|png|pdf|xls|docs';
		$max_size = ($_config['max_size'] && $_config['max_size'] != '') ? : '10000';
		
		$config['allowed_types'] = $allowed_types;
		$config['max_size']	= $max_size;
		$config['overwrite'] = false;
		
		if(!$field){
			return false;
		}

		$ci = ci();
		
		$ci->load->library('upload', $config);
		
		//check if single file or multple
		$post_files = array();
		if(is_array($_FILES[$field]['name'])){//multiple files
			//re arrange files
			$post_fields = $_FILES[$field];
			unset($_FILES[$field]);
			foreach($post_fields as $key => $all ){
   				foreach( $all as $i => $val ){
    		   		$_FILES[$field][$i][$key] = $val;   
   				}   
			}
			$r = 0;
			foreach($_FILES[$field] as $_fields){
				$r++;
				$_FILES[$field."_".$r] = $_fields;
			}
		}else{
			$r = 1;
			$_FILES[$field."_".$r] = $_FILES[$field];
		}
		
		$result['error'] = false;
		unset($_FILES[$field]);
		
		$n = 0;
		foreach($_FILES as $_field_name => $post_file){
			$n++;
			if($post_file['tmp_name'] != '' && ($_field_name==$field."_".$n)){
				
				$image_name = pathinfo($post_file['name']);
				$upload_path = $path;
				$base_path = "media/uploads".$upload_path;
				$dir_path = random_dir(abs_path($base_path))."/";

				$config['file_name'] = strtolower(clean_unique_code($image_name['filename'], '-').".".$image_name['extension']);
				$config['upload_path'] = $base_path.$dir_path;
				$config['allowed_types'] = $allowed_types;
				$config['max_size']	= $max_size;
				$config['overwrite'] = false;
				
				$ci->upload->initialize($config);
				
				if(!$ci->upload->do_upload($_field_name)){

					$result['error'] = $ci->upload->display_errors();

				}else{

					$data = $ci->upload->data();
					$uploaded_image_name = pathinfo($data['file_name']);
					
					$result['file_name'][] = $dir_path.$uploaded_image_name['filename'].".".$uploaded_image_name['extension'];
					//$image_abs_path = abs_path().$base_path.$dir_path.$data['file_name'];

					//$small_image_name = strtolower(clean_unique_code($uploaded_image_name['filename'], '-')."_sm.".$uploaded_image_name['extension']);
					
					//$small_image_abs_path = abs_path().$base_path.$dir_path.$small_image_name;
					//image_resize($image_abs_path, array('height'=>300, 'width'=>300), true, $small_image_abs_path);
					
					//$gallery_images[$n]['image'] = $upload_path.$dir_path.$data['file_name'];
					//$gallery_images[$n]['thumb'] = $upload_path.$dir_path.$small_image_name;
					
				}
			}
		}
		return $result;
	}
}

if(!function_exists('filters_query_string')){
	function filters_query_string($attributes, $attr_val=false, $current_attr=false, $prefix='&'){
		
		/*
		@$attributes = array of attributes
		eg. array('features'=>'multiple', 'rating'=>'single');
		
		@$attr_val = string - value of current attribute || empty
		
		@current_attr = atring - Code of current attribute
		*/
		
		$return_str = array();
		
		foreach($attributes as $attr_code => $attr_type){
			
			if($attr_type == 'multiple'){
				
				if(isset($_GET[$attr_code])){
					
					$select_values_str = array();
					$get_selected_values = explode("|", $_GET[$attr_code]);
					foreach($get_selected_values as $get_selected_value){
						if($get_selected_value != ''){
							if($attr_val && $attr_val == $get_selected_value){
									
							}else{
								$select_values_str[$get_selected_value] = $get_selected_value;
							}	
						}	
					}
					
					if($current_attr && $current_attr == $attr_code){
						if($attr_val && !in_array($attr_val, $get_selected_values)){
							$select_values_str[$attr_val] = $attr_val;
						}
					}	
					$_f_str = "";
					if(count($select_values_str) > 0){
						if( count($select_values_str) == 1 ){
							$_f_str = implode("", $select_values_str);
						}else{
							$_f_str = implode("|", $select_values_str);
						}
						$return_str[] = "{$attr_code}=".$_f_str;
					}
				}else{
					if( $current_attr && $current_attr == $attr_code ){
						if($attr_val){
							$return_str[] = "{$attr_code}=".$attr_val;
						}	
					}
				}
				
			}elseif($attr_type == 'single'){
				
				if(isset($_GET[$attr_code]) && $attr_val == $_GET[$attr_code]){
					//empty for current attribute
				}else{
					if($current_attr && $current_attr == $attr_code){
						if($attr_val){
							$return_str[] = "{$attr_code}=".$attr_val;
						}	
					}else{	
						if(isset($_GET[$attr_code])){
							$return_str[] = "{$attr_code}=".$_GET[$attr_code];
						}
					}
				}	
			}	
		}
		
		if(count($return_str) > 0){
			return $prefix.implode("&", $return_str);
		}else{
			return "";
		}							
	}
}