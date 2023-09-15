<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function mpl_findSharp($orig, $final){// function from Ryan Rud (http://adryrun.com)
	$final	= $final * (750.0 / $orig);
	$a		= 52;
	$b		= -0.27810650887573124;
	$c		= .00047337278106508946;
	
	$result = $a + $b * $final + $c * $final * $final;
	
	return max(round($result), 0);
}

if(!function_exists('mpl_image_resize')){
	function mpl_image_resize($filename, $sizes, $crop=false, $output=false, $do_sharp=true, $display=false){
		
		$size = getimagesize($filename);
		$width = $size[0];
		$height = $size[1];
		$type = $size['mime'];
		
		$newwidth = $sizes['width'];
		
		$percentChange = $newwidth / $width;
		$newheight = round(($percentChange*$height));
		
		$_n_height = $newheight;
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
		
		$image_f = "imagejpeg";
		if($type == 'image/jpg' || $type == 'image/jpeg'){
			$source = imagecreatefromjpeg($filename);
			$image_f = "imagejpeg";
			$quality = 100;
				
		}elseif($type == 'image/png'){
			$source = imagecreatefrompng($filename);
			$image_f = "imagepng";
			$quality = 8;
			
		}elseif($type == 'image/gif'){
			$source = imagecreatefromgif($filename);
			$image_f = "imagepng";
			$quality = 8;
		}
		
		$thumb = imagecreatetruecolor($newwidth, $_n_height);
	
		//imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	
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
		
		$image_f($thumb, $save, $quality);
		imagedestroy($thumb);
	}
}

function image_colors($image){
	
	$PREVIEW_WIDTH    = 50;  //WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
	$PREVIEW_HEIGHT   = 50;
	$size = GetImageSize($image);
	$scale=1;
	if ($size[0]>0)
		$scale = min($PREVIEW_WIDTH/$size[0], $PREVIEW_HEIGHT/$size[1]);
	
	if ($scale < 1){
		$width = floor($scale*$size[0]);
		$height = floor($scale*$size[1]);
	}else{
		$width = $size[0];
		$height = $size[1];
	}

	$image_resized = imagecreatetruecolor($width, $height);
	
	$image_resized = imagecreatetruecolor($width, $height);
	if ($size[2]==1)
		$image_orig=imagecreatefromgif($image);

	if ($size[2]==2)
		$image_orig=imagecreatefromjpeg($image);
	
	if ($size[2]==3)
		$image_orig=imagecreatefrompng($image);
	
	imagecopyresampled($image_resized, $image_orig, 0, 0, 0, 0, $width, $height, $size[0], $size[1]); //WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
	$im = $image_resized;
	$imgWidth = imagesx($im);
	$imgHeight = imagesy($im);

	for ($y=0; $y < $imgHeight; $y++){
		for ($x=0; $x < $imgWidth; $x++){
			$index = imagecolorat($im,$x,$y);
			$Colors = imagecolorsforindex($im,$index);
			$Colors['red']=intval((($Colors['red'])+15)/32)*32;    //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
			$Colors['green']=intval((($Colors['green'])+15)/32)*32;
			$Colors['blue']=intval((($Colors['blue'])+15)/32)*32;

			if ($Colors['red']>=256)
				$Colors['red']=240;

			if ($Colors['green']>=256)
				$Colors['green']=240;

			if ($Colors['blue']>=256)
				$Colors['blue']=240;
			$hexarray[]=substr("0".dechex($Colors['red']),-2).substr("0".dechex($Colors['green']),-2).substr("0".dechex($Colors['blue']),-2);
		}
	}
	$hexarray=array_count_values($hexarray);
	natsort($hexarray);
	$hexarray=array_reverse($hexarray,true);
	return $hexarray;
}

if(!function_exists('upload_page_dir')){
	function upload_page_dir(){
	
		$dir = chr(rand(97,122));//generate a random dirctory name
		$dirpath = "./media/uploads/pages/" . $dir;
				
		if( !file_exists($dirpath) ){
			mkdir( $dirpath, 0777 );
		}
		return array('path'=>$dirpath, 'dir'=>$dir);
	}
}

if(!function_exists('upload_favicon_dir')){
	function upload_favicon_dir(){
	
		$dir = chr(rand(97,122));//generate a random dirctory name
		$dirpath = $_SERVER['DOCUMENT_ROOT']."/mpl/wp-content/uploads/favicons/" . $dir;
				
		if( !file_exists($dirpath) ){
			mkdir( $dirpath, 0777 );
		}
		return array('path'=>$dirpath, 'dir'=>$dir);
	}
}

if(!function_exists('mpl_upload_file')){
	function mpl_upload_file($file, $id=false){
		
		$path_data = upload_page_dir();
		$dirpath = $path_data['path'];
		$dir = $path_data['dir'];
		
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$file_name = $id."_O_".date("his").".".$ext;
		
		if(move_uploaded_file($file['tmp_name'], $dirpath."/".$file_name)){
			
			$original_path = "uploads/pages/" . $dir . "/" . $file_name;
			$size = getimagesize(base_url()."/media/".$original_path);
			$image_size = $size[0]."-".$size[1];
			
			//variable size image
			$n_d = upload_page_dir();
			$var_file_name = $id."_V_".date("his").".".$ext;
			$variable_file_path = $n_d['path']."/".$var_file_name;
			mpl_image_resize($dirpath."/".$file_name, array('width'=>360, 'height'=>600), true, $variable_file_path);
			
			$variable_original_path = "uploads/pages/" . $n_d['dir'] . "/" . $var_file_name;
			$var_size = getimagesize($variable_file_path);
			$var_image_size = $var_size[0]."-".$var_size[1];
			unset($n_d);
			
			//original fix size image
			$n_d = upload_page_dir();
			$fix_file_name = $id."_F_".date("his").".".$ext;
			$fix_file_path = $n_d['path']."/".$fix_file_name;
			mpl_image_resize($dirpath."/".$file_name, array('width'=>250, 'height'=>375), true, $fix_file_path);
			
			$fix_original_path = "uploads/pages/" . $n_d['dir'] . "/" . $fix_file_name;
			$fix_size = getimagesize($fix_file_path);
			$fix_image_size = $fix_size[0]."-".$fix_size[1];
			unset($n_d);
			
			$colors = image_colors($dirpath."/".$file_name);
			$return = array(
							'file_name'=>$file_name,
							'path'=>$dirpath,
							'image_path'=>$original_path,
							'image_size'=>$image_size,
							'colors'=>$colors,
							'status'=>'success',
							'main_image'=>$variable_original_path,
							'main_image_size'=>$var_image_size,
							'fix_image'=>$fix_original_path,
							'fix_image_size'=>$fix_image_size
						);
		}else{
			$return = array('file_name'=>'', 'path'=>'', 'full_path'=>'', 'colors'=>'', 'status'=>'error');
		}
		return $return;
	}
}