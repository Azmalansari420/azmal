<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App
{
	var $ci;
	
	function __construct($params=array()){
		$this->ci =& get_instance();
		
		//download dir
		$this->download_dir = 'downloads';
		
		//eg. 'downloads/'
		$this->download_dir_rel_path = $this->download_dir.'/';
		
		//eg. './downloads/'
		$this->download_dir_doc_path = './'.$this->download_dir.'/';
		
		//medium thumb dims
		$this->medium_thumb_dims = "&width=274&height=196&cropratio=4:2";
		
		//small thumb dims
		$this->small_thumb_dims = "&width=178&height=178&cropratio=1:1";
		
		//valid image formats
		$this->image_formats = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff');
	}
	
	/*
	* bad special characters filter from string
	*/
	public function bad_chars($string){
		$bad_characters = '����������������������������������������������������';
		$good_characters = 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy';
		return strtr($string, $bad_characters, $good_characters);
	}
	
	/*
	create a new dir under main donwloads dir
	store images in different directories
	*/
	public function random_dir($dir_path=''){
		//generate a random dirctory name
		$dir = chr(rand(97,122));
		
		//add trailing slash
		if($dir_path != '')
			$dir_path = rtrim($dir_path, '/').'/';
		
		//dir path
		$dirpath = $dir_path . $dir;
		
		//check if dir exists
		if( !is_dir($dirpath) )

		//if dir not exists, create a new dir
			mkdir( $dirpath, 0777 );
		
		return $dir;
	}
	
	/*
	download image from the link
	@string - image url
	@string - image name
	
	@return downloaded image path
	*/
	function download_image($img, $name){
		
		$download_location = './'.$this->config->item('media_url').'downloads/'.$name; 
		
		$ch = curl_init ($img);
    	
		curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
    	$rawdata=curl_exec($ch);
    	curl_close ($ch);

    	$fp = fopen($fullpath,'x');
    	if(fwrite($fp, $rawdata)){
			fclose($fp);
			return base_url().$this->config->item('media_url').'downloads/'.$name;
		}else{
			return false;
		}
	}
	
	/*
	generate slug
	*/
	public function slug($string, $replace='-'){
		//convert string to lower case
			$string = strtolower($string);
			
			$string = trim($string);
			$string = rtrim($string, '-');

		//remove unwanted unwanted characters
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//remove multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//convert whitespaces and underscore to $replace
		$string = preg_replace("/[\s_]/", $replace, $string);
	   
		$string = trim($string);
		$string = rtrim($string, '-');

 	   	return $string;
	}	
	
	/*
	generate slug
	*/
	public function slug_non_english($string, $replace='-'){
		//convert string to lower case
			$string = strtolower($string);
			
			$string = trim($string);
			$string = rtrim($string, '-');

		//remove unwanted unwanted characters
		// $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//remove multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//convert whitespaces and underscore to $replace
		$string = preg_replace("/[\s_]/", $replace, $string);
	   
		$string = trim($string);
		$string = rtrim($string, '-');

 	   	return $string;
	}

	public function validate_slug($slug, $column, $table_name){

		$_slug = $slug;
		$slug_nums = array();

		$slugs = $this->ci->db->select($column)->like($column, addslashes($slug))->get($table_name);

		if($slugs->num_rows() > 0){

			if($slugs->num_rows()==1){
				return $slug . "-1";
			}else{
				$results = $slugs->result_array();
				$max_occurence = 1;
				foreach($results as $result){
					$__slug = explode("-", $result[$column]);
					$num = $__slug[(count($__slug)-1)];
					if(is_numeric($num)){
						$num = (int)$num;
						if($num >= $max_occurence){
							$max_occurence = $num;
						}
					}
				}
				$slug_suffix = $max_occurence + 1;
				return $slug . "-" . $slug_suffix;
			}

		}else{//very first slug
			return substr($_slug, 0, 120);
		}
		if(count($slug_nums)>0){
			$_slug = $_slug . "-" . ($slug_nums[(count($slug_nums)-1)] + 1);
			return substr($_slug, 0, 120);
		}else{
			return false;
		}	
	}
	

	public function extract_tags($title){
		$title = " ".strtolower($title)." "; 
	
		$words_to_filter3 = array_unique(explode("\n",file_get_contents(APPPATH."/helpers/wordfilter.txt")));
 	
		foreach($words_to_filter3 as $wtf){
			$title = str_ireplace (" ".trim($wtf)." ", " " , $title);
		}
		
		//Clean Up
		str_ireplace ("  ", " " , $title);
		
		//Clean Up  [Numbers and Special Chars]
		$words_to_filter1 = explode(" ", $this->ci->config->item("words_to_filter1"));
		$title = str_ireplace ($words_to_filter1, " " , $title); 
		
		//Clean Up [Single Chars]
		$words_to_filter2 = explode(" ", $this->ci->config->item("words_to_filter2"));
		foreach($words_to_filter2 as $wtf){		 
			$title = str_ireplace (" ".$wtf." ", " " , $title);
		}
		 
		$tags = explode(" ",strtolower(trim($title)));
		$newtags = array();;
		foreach($tags as $t){
			if(trim($t)!=""){
				$newtags[] = strtolower(trim($t));
			}
		};
		$newtags = array_unique($newtags);
		return $newtags;
	}
	

	function valid_image_format($ext){
		foreach($this->image_formats as $image_format){
			if(stristr($ext, $image_format)){
				return $image_format;
			}
		}
		//default image format
		return 'jpg';
	}
}