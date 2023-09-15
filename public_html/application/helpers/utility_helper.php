<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ci(){
	return $ci =& get_instance();
}

if(!function_exists("clean_text")){
	function clean_text($str = '', $replace='-'){
		return strtolower(preg_replace("/[^A-Za-z0-9]/", $replace, stripslashes($str)));
	}
}

if(!function_exists("clean_unique_code")){
	function clean_unique_code($str = '', $replace='_'){
		return clean_text(strtolower($str), $replace);
	}
}

if(!function_exists("clean_insert")){
	function clean_insert($str = ''){
		return (addslashes($str));
	}
}

if(!function_exists("clean_display")){
	function clean_display($str = ''){
		// return stripslashes(stripslashes(stripslashes(utf8_decode($str))));
		return (stripslashes(stripslashes($str)));
	}
}

if(!function_exists("msg")){
	function msg($msg=false, $path=false){
		$ci = ci();
		if(!$msg)
			$msg = "Success!";
		
		if(!$path){
			$path = base_url();
		}else{
			$path = base_url() . ltrim(str_replace(base_url(), '', $path), "/");
		}
			
		$ci->session->set_flashdata('msg', $msg);
		if($path) redirect($path);
	}
}

if(!function_exists("er_msg")){
	function error_msg($msg, $path=false){
		$ci = ci();
		if(!$msg)
			$msg = "There was an error!!";
		
		if(!$path){
			$path = base_url();
		}else{
			$path = base_url() . ltrim(str_replace(base_url(), '', $path), "/");
		}
		
		$ci->session->set_flashdata('error_msg', $msg);
		if($path) redirect($path);
	}
}

if(!function_exists('media_url')){
	function media_url(){
		$ci = ci();
		$ci->load->config('app_config');
		return base_url().$ci->config->item('media_url');
	}
}

if(!function_exists('element_types')){
	function element_types($key=false){
		$ci = ci();
		$element_types = $ci->config->item('element_types');
		if($key){//if key defined return type value
			return $element_types[$key];
		}
		return $element_types;
	}
}

if(!function_exists('email')){
	function email($from, $from_name, $to, $sub, $body, $html=false, $debug=false, $cc=false){
		$ci = ci();
		$ci->load->library('email');
		$ci->email->clear();
	
		if($html){
			$config['mailtype'] = 'html';
			$ci->email->initialize($config);
		}
		
		$ci->email->from($from, ucwords($from_name));
		$ci->email->to($to);
	
		if($cc && ($cc != '' || is_array($cc))){
			$ci->email->cc($cc);
		}
		//$ci->email->bcc('them@their-example.com');
		
		$ci->email->subject($sub);
		$ci->email->message($body);
		$ci->email->send();
		
		if($debug){
			return $ci->email->print_debugger();
		}else{
			return true;
		}
	}
}

if(!function_exists('email_attachment')){
	function email_attachment($from, $from_name, $to, $sub, $body, $html=false, $debug=false, $attachment=false, $cc=false){
		$ci = ci();
		$ci->load->library('email');
		$ci->load->helper('path');
		
		$ci->email->clear(TRUE);
		if($html){
			$config['mailtype'] = 'html';
			$ci->email->initialize($config);
		}
				
		$ci->email->from($from, ucwords($from_name));
		$ci->email->to($to);
		if($cc && ($cc != '' || is_array($cc))){
			$ci->email->cc($cc);
		}
		//$ci->email->bcc('them@their-example.com');
		
		$ci->email->subject($sub);
		$ci->email->message($body);
		
		if($attachment){
			$path = set_realpath('media/uploads/');
			$ci->email->attach($path . $attachment);  /* Enables you to send an attachment */
		}
		
		$ci->email->send();
		
		if($debug){
			return $ci->email->print_debugger();
		}else{
			return true;
		}
	}
}

if(!function_exists('random_dir')){
	function random_dir($dir_path=''){

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
}	

function abs_path($text=''){
	$abs_path = str_replace("system", "", BASEPATH);
	$abs_path = ltrim(rtrim($abs_path, "/"), '\'')."/";
	if($text != ''){
		$abs_path = $abs_path.ltrim(rtrim($text, "/"), "/")."/";
	}
	return $abs_path;
}

if(!function_exists('template_parser')){
	function template_parser($template=false, $variables=array()){
		if($template){
			//find all shortcodes
			preg_match_all('/{{(.*?)}}/', stripslashes($template), $shortcodes);
			$shortcodes = $shortcodes[1];
			
			if(count($shortcodes) > 0){
				foreach($shortcodes as $shortcode){
					if(array_key_exists($shortcode, $variables)){
						$template = str_replace("{{".$shortcode."}}", $variables[$shortcode], $template);
					}
				}
			}
			return urlencode($template);
		}
		return false;	
	}
}


function templates($name=false, $type='cms_page'){
	$ci = ci();
	
	$template_found = false;
	
	$ci->load->helper('directory');
	$templates = directory_map('./application/views/theme/s_listing/' . $type);
	$return = array();
	if(is_array($templates) && count($templates) > 0){
		foreach($templates as $template){
			$template_name = pathinfo($template);
			$return[] = array('file'=>$template, 'name'=>$template_name['filename']);
			
			if($name){
				if(strtolower($template_name['filename']) == strtolower($name)){
					$template_found = $template;
				}		
			}
		}
	}
	
	if($name){
		return $template_found;
	}else{
		return $return;
	}	
}

if(!function_exists('get_setting')){
	function get_setting($key=false){
		$ci = ci();
		$ci->load->model('settings_model');
		return $ci->settings_model->get_value_by_key($key);
	}
}

if(!function_exists('field_validations')){
	function field_validations($_attributes = array()){
		
		if(isset($_attributes['validation']) && $_attributes['validation'] != ''){
			$field_validations = explode("|", $_attributes['validation']);
			$_validations = array();
			foreach($field_validations as $validation){
				if($validation == "required"){
					$_validations['required'] = 'true';
				
				}elseif(stristr($validation, 'matches')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					$_validations['equalTo'] = "'#{$_field}'";
				
				}elseif(stristr($validation, 'min_length')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					$_validations['minlength'] = "{$_field}";
					
				}elseif(stristr($validation, 'max_length')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					$_validations['maxlength'] = "{$_field}";
					
				}elseif(stristr($validation, 'exact_length')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					$_validations['rangelength'] = "[{$_field},{$_field}]";
					
				}elseif(stristr($validation, 'greater_than')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					//echo "is_integer($_field)";exit;
					if(ctype_digit($_field) == true){
						$_validations['min'] = "{$_field}"+1;
					}elseif(is_numeric($_field) == true){
						$_validations['min'] = "{$_field}"+0.1;
					}
					
				}elseif(stristr($validation, 'less_than')){
					$find_matches = explode("[", $validation);
					$_field = rtrim($find_matches[1], "]");
					if(ctype_digit($_field) == true){
						$_validations['max'] = "{$_field}"-1;
					}elseif(is_numeric($_field) == true){
						$_validations['max'] = round("{$_field}");
					}
					//$_validations['max'] = "{$_field}"-1;		// round("{$_field}");
					
				}elseif($validation=='valid_email'){
					$_validations['email'] = "true";
					
				}elseif($validation=='numeric'){
					$_validations['number'] = "true";
					
				}elseif($validation=='alpha_numeric'){
					$_validations['alpha_numeric'] = "true";
					
				}elseif($validation=='integer'){
					$_validations['digits'] = "true";
					
				}elseif($validation=='alpha_dash'){
					$_validations['alpha_dash'] = "true";
				
				}elseif($validation=='alpha_underscore'){
					$_validations['alpha_underscore'] = "true";
					
				}elseif($validation=='alphabet'){
					$_validations['alphabet'] = "true";
					
				}elseif($validation=='alpha_space'){
					$_validations['alpha_space'] = "true";
					
				}elseif($validation=='alpha_numeric_space'){
					$_validations['alpha_numeric_space'] = "true";
					
				}elseif($validation=='url'){
					$_validations['url'] = "true";
					
				}
			}
			
			if(count($_validations) > 0){
				return json_encode($_validations);
			}
			return false;
		}
		
	}
}

if(!function_exists('validation_classes')){
	function validation_classes($_attributes=array()){
		$validations_classes = array("required"=>"required-entry", "valid_email"=>"validate-email", "numeric"=>"validate-number", "alpha_space"=>"validate-alpha-space", "alphabet"=>"validate-alphabet", "alpha_dash"=>"validate-alpha-dash", "alpha_underscore"=>"validate-alpha-underscore", "alpha_numeric"=>"validate-alpha-numeric", "alpha_numeric_space"=>"validate-alpha-numeric-space", "url"=>"validate-url", "is_unique"=>"validate-unique");
		
		$_classes = array();
		
		if(isset($_attributes['validation']) && $_attributes['validation'] != ''){
			$field_validations = explode("|", $_attributes['validation']);

			foreach($field_validations as $validation){
				
				if(stristr($validation, "is_unique")){
					$validation = "is_unique";
				}
				
				if(array_key_exists($validation, $validations_classes)){
					$_classes[] = $validations_classes[$validation];
				}
			}
		}	
		return $_classes;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

if(!function_exists('front_root_categories')){
	function front_root_categories(){
		$ci = ci();
		$ci->load->model('category_model');
		
		return $ci->category_model->front_root_categories();
	}
}

if(!function_exists('random_string_generator')){
	function random_string_generator($length = 10, $type = ''){
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		if($type == 'numeric'){

			$characters = '012345678901234567890123456789012345678901234567890123456789';
		}

		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

if(!function_exists('time_elapsed_string')){	//shows time in ago format
	function time_elapsed_string($datetime, $full = false){
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}

if(!function_exists("validate_date")){
	function validate_date($date=false){
		if($date && $date != ''){
			if(strtotime($date)){
				$date_m = (int)date("m", strtotime($date));
				$date_d = (int)date("d", strtotime($date));
				$date_y = (int)date("Y", strtotime($date));
				
				if( ($date_m != '' && ($date_m >= 1) && ($date_m <= 12)) && ($date_d != '' && ($date_d >= 1) && ($date_d <= 31)) && ($date_y != '' && ($date_y >= 1700) && ($date_y <= 3000)) ){
					if(checkdate($date_m, $date_d, $date_y)){
						return true;
					}
				}
			}
		}
		return false;
	}
}

if(!function_exists('limit_words')){
	function limit_words($text, $limit) {
		$text = strip_tags($text);
		if (str_word_count($text, 0) > $limit) {
			//$text = strip_tags($text);
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = substr(strip_tags($text), 0, $pos[$limit]) . '...';
		}
		return $text;
	}
}

if(!function_exists('currency_format')){
	function currency_format($amount = 0){

		$number_of_digits = strlen($amount);

		$ext = "";
		
		if($number_of_digits > 3){
			if($number_of_digits%2 != 0){
				$divider = currency_divider($number_of_digits - 1);
			}else{
				$divider = currency_divider($number_of_digits);
			}	
		}else{
			$divider = 1;
		}

		$fraction = $amount / $divider;
		$fraction = number_format($fraction, 2);
		
		if($number_of_digits == 4 || $number_of_digits == 5)
			$ext = "k";

		if($number_of_digits == 6 || $number_of_digits == 7)
			$ext = "Lac";

		if($number_of_digits == 8 || $number_of_digits == 9)
			$ext = "Cr";
		return ($fraction + 0) . " " . $ext;
	}
}

if(!function_exists('get_currency')){
	function get_currency(){
		return '<svg class="rupee-icon" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
		<path fill="currentColor" stroke="currentColor" d="M51.473,13.283h15.73l5.666-8.5H15.122l-5.667,8.5h15.884c1.64,0.073,13.11,0.887,17.224,9.346H15  l-5.667,8.5h34.623c-0.012,0.161-0.023,0.32-0.039,0.483c0,0-0.163,18.603-31.375,15.38V51l0.032,4.387l33.683,39.832h12.794v-1.771  L26.759,53.95v-0.567c0,0,25.414,0.405,29.622-22.094c0,0,0.008-0.056,0.02-0.16h10.68l5.668-8.5H55.943  C55.305,19.506,54.011,16.069,51.473,13.283z"></path></svg>';
	}
}

if(!function_exists('price_format')){
	function price_format($amount = 0, $decimal_place=2){

		return '<div class="price-box">' . get_currency()." ".number_format($amount, $decimal_place) . '</div>';
	}
}

function currency_divider($number_of_digits=0){

	$tens = "1";
	
	if($number_of_digits > 8)
		return 10000000;
	
	while(($number_of_digits - 1) > 0){
		$tens .= "0";
		$number_of_digits--;
	}
	return $tens;
}

if(!function_exists('price_html')){
	function price_html($product_id=false){
		if($product_id){
			$ci = ci();
			$ci->load->model('catalog/products_model');
			$product = $ci->products_model->fetch_row_by_field('id', $product_id);
			
			if($product->special_price != 0){
				$price_html = '<span class="price">'.price_format($product->special_price).'</span><span class="original-price">'.price_format($product->price).'</span>';
			}else{
				$price_html = '<span class="price">'.price_format($product->price).'</span>';
			}
			
			return $price_html;
		}
	}
}

// if(!function_exists('price_html')){
// 	function price_html($price=0){
// 		$price_html = '$'.currency_format($price);
// 		return $price_html;
// 	}
// }


if(!function_exists("generate_slug")){
	function generate_slug($name){
		$ci = ci();
		$ci->load->library('app');
		return $ci->app->slug($name);
	}
}	


if(!function_exists("get_type")){
	function get_type($no=false){
		if($no!=''){
			return array('call-girls'=>"Call Girls",  'male-escorts'=>"Male Escorts");
		}else{
			return array(''=>"Select", 'call-girls'=>"Call Girls", 'male-escorts'=>"Male Escorts");
		}
	}
}

if(!function_exists("get_states")){
	function get_states($no=false){
		if($no!=''){
			return array('Andhra Pradesh'=>"Andhra Pradesh", 'Assam'=>"Assam", 'Bihar'=>"Bihar", 'Chhattisgarh'=>"Chhattisgarh", 'Delhi'=>"Delhi", 'Goa'=>"Goa", 'Gujarat'=>"Gujarat", 'Haryana'=>"Haryana", 'Himachal Pradesh'=>"Himachal Pradesh", 'Jharkhand'=>"Jharkhand", 'Karnataka'=>"Karnataka", 'Kerala'=>"Kerala", 'Madhya Pradesh'=>"Madhya Pradesh", 'Maharashtra'=>"Maharashtra", 'Odisha'=>"Odisha", 'Punjab'=>"Punjab", 'Rajasthan'=>"Rajasthan", 'Tamil Nadu'=>"Tamil Nadu", 'Tripura'=>"Tripura", 'Uttar Pradesh'=>"Uttar Pradesh", 'Uttarakhand'=>"Uttarakhand", 'West Bengal'=>"West Bengal");
		}else{
			return array(''=>"Select", 'Andhra Pradesh'=>"Andhra Pradesh", 'Assam'=>"Assam", 'Bihar'=>"Bihar", 'Chhattisgarh'=>"Chhattisgarh", 'Delhi'=>"Delhi", 'Goa'=>"Goa", 'Gujarat'=>"Gujarat", 'Haryana'=>"Haryana", 'Himachal Pradesh'=>"Himachal Pradesh", 'Jharkhand'=>"Jharkhand", 'Karnataka'=>"Karnataka", 'Kerala'=>"Kerala", 'Madhya Pradesh'=>"Madhya Pradesh", 'Maharashtra'=>"Maharashtra", 'Odisha'=>"Odisha", 'Punjab'=>"Punjab", 'Rajasthan'=>"Rajasthan", 'Tamil Nadu'=>"Tamil Nadu", 'Tripura'=>"Tripura", 'Uttar Pradesh'=>"Uttar Pradesh", 'Uttarakhand'=>"Uttarakhand", 'West Bengal'=>"West Bengal");
		}
	}
}


if(!function_exists("get_all_cities")){
	function get_all_cities(){
		$ci = ci();
		$ci->load->model("location/cities_model");
		$get_cities	= $ci->db->order_by('name', 'desc')->get($ci->cities_model->table_name())->result();
		
		$cities		= array();
		foreach($get_cities as $c){
			$cities[]	= $c->name;
		}
		
		return $cities;
	}
}


if(!function_exists("get_cities")){
	function get_cities($state=false){
		$ci = ci();
		$ci->load->model("location/cities_model");
		return $ci->cities_model->get_cities($state);
	}
}

if(!function_exists("get_cities_footer")){
	function get_cities_footer($state=false){
		$ci = ci();
		$ci->load->model("location/cities_model");
		return $ci->cities_model->get_cities_footer($state);
	}
}


if(!function_exists("get_cities_home_view")){
	function get_cities_home_view($state=false){
		$ci = ci();
		$ci->load->model("location/cities_model");
		return $ci->cities_model->get_cities_home_view($state);
	}
}


if(!function_exists("get_cities_most_searched")){
	function get_cities_most_searched($state=false){
		$ci = ci();
		$ci->load->model("location/cities_model");
		return $ci->cities_model->get_cities_most_searched($state);
	}
}


if(!function_exists("get_locality")){
	function get_locality($city=false){
		$ci = ci();
		$ci->load->model("location/locality_model");
		return $ci->locality_model->get_locality($city);
	}
}


if(!function_exists("get_all_locality")){
	function get_all_locality(){
		$ci = ci();
		$ci->load->model("location/locality_model");
		$get_locality	= $ci->db->order_by('name', 'desc')->get($ci->locality_model->table_name())->result();
		
		$locality		= array();
		foreach($get_locality as $c){
			$locality[]		= $c->name;
		}
		
		return $locality;
	}
}


if(!function_exists("get_keywords")){
	function get_keywords($city=false){
		$ci = ci();
		$ci->load->model("keywords_model");
		return $ci->keywords_model->get_keywords($city);
	}
}

if(!function_exists("get_keywords_by_state")){
	function get_keywords_by_state($state=false){
		$ci = ci();
		$ci->load->model("keywords_model");
		return $ci->keywords_model->get_keywords_by_state($state);
	}
}

if(!function_exists("get_tags")){
	function get_tags(){
		$ci = ci();
		$ci->load->model("tags_model");
		return $ci->tags_model->get_tags();
	}
}


if(!function_exists("get_services")){
	function get_services($no=false){
		if($no!=''){
			return array('69 Position'=>"69 Position", 'French Kissing'=>"French Kissing", 'Kissing'=>"Kissing", 'Kissing if good chemistry'=>"Kissing if good chemistry", 'Erotic massage'=>"Erotic massage", 'Girlfriend Experience (GFE)'=>"Girlfriend Experience (GFE)", 'Blowjob without Condom'=>"Blowjob without Condom", 'Cumshot on body (COB)'=>"Cumshot on body (COB)", 'Anal Sex'=>"Anal Sex", 'Blowjob with Condom'=>"Blowjob with Condom", 'Sex in Different Positions'=>"Sex in Different Positions");
		}else{
			return array(''=>"Select", '69 Position'=>"69 Position", 'French Kissing'=>"French Kissing", 'Kissing'=>"Kissing", 'Kissing if good chemistry'=>"Kissing if good chemistry", 'Erotic massage'=>"Erotic massage", 'Girlfriend Experience (GFE)'=>"Girlfriend Experience (GFE)", 'Blowjob without Condom'=>"Blowjob without Condom", 'Cumshot on body (COB)'=>"Cumshot on body (COB)", 'Anal Sex'=>"Anal Sex", 'Blowjob with Condom'=>"Blowjob with Condom", 'Sex in Different Positions'=>"Sex in Different Positions");
		}
	}
}


if(!function_exists('file_type_mime_details')){
	function file_type_mime_details($extenstions){
		$extenstions = explode(",", $extenstions);
		$mime_types = get_mimes();
		$allow_types = [];

		$mime_types['webp'] = ['image/webp'];

		foreach($extenstions as $extenstion){
			if(array_key_exists(trim($extenstion), $mime_types)){

				if(is_array($mime_types[trim($extenstion)])){
					foreach($mime_types[trim($extenstion)] as $mime_type){
						$allow_types[] = $mime_type;
					}
				}else{
					$allow_types[] = $mime_types[trim($extenstion)];
				}		
			}
		}
		return $allow_types;
	}
}