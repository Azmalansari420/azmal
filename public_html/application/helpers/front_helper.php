<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('ci')){
	function ci(){
		return $ci =& get_instance();
	}
}

if(!function_exists('home_url')){
	function home_url($path=false){
		$base_url = base_url();
		$base_url = rtrim($base_url, "/");

		if($path && $path != ''){
			$path = rtrim($path, "/");
			$base_url = $base_url . "/" . $path;
		}

		return $base_url;
	}
}

function front_theme_config(){
	$ci = ci();
	$ci->load->config('theme_config');
}

function assets_dir(){
	$ci = ci();
	front_theme_config();
	return $ci->config->item('assets_dir');
}

function js_url(){
	return base_url() . "assets/theme/" . assets_dir() . "/js/";
}

function css_url(){
	return base_url() . "assets/theme/" . assets_dir() . "/css/";
}

function theme_dir($path=false){
	$ci = ci();
	front_theme_config();
	$theme_dir = $ci->config->item('theme_dir');

	$theme_dir = "theme/".$theme_dir."/";

	if($path && $path != ''){
		$theme_dir = $theme_dir . ltrim($path, "/");
	}

	return $theme_dir;
}

function theme_url($template=false){
	return theme_dir($template);
}

function template_path($template=false){
	if($template && $template != ''){
		return APPPATH . "views/".theme_dir($template).".php";
	}
	return APPPATH . "views/".theme_dir();
}

function images_url($file=false){
	$url = base_url() . "assets/theme/" . assets_dir() . "/images/";
	if($file && $file != ''){
		$url = $url . $file;
	}
	return $url;
}

function front_js($js_files=false, $html=false, $ver=1){
	$ci = ci();

	//load app_config
	front_theme_config();
	$add_js = $ci->config->item('js');
	
	$base_js_url = js_url();
	
	//register js
	if($js_files && !$html){
		if(is_array($js_files)){
			foreach($js_files as $js){
				$add_js[] = rtrim(ltrim($js, "/"), "/");
			}
		}else{
			$add_js[] = $js_files;
		}
		$ci->config->set_item('js', $add_js);
	}
	
	$version = ($ver && is_numeric($ver)) ? $ver : 1;

	if((!$js_files && !$html) || (!$js_files && $html)){
		$js_html = '';
		foreach($add_js as $js){
			$js_html .= '<script type="text/javascript" src="'.$base_js_url.$js.'?v='.$version.'" async></script>'."\n";
		}
		return $js_html;
	}
}

function front_css($css_files=false, $html=false, $ver=1){
	$ci = ci();

	//load app_config
	front_theme_config();
	$add_css = $ci->config->item('css');
	
	$base_css_url = css_url();

	//register css
	if($css_files && !$html){
		if(is_array($css_files)){
			foreach($css_files as $css){
				$add_css[] = rtrim(ltrim($css, "/"), "/");
			}
		}else{
			$add_css[] = $css_files;
		}
		$ci->config->set_item('css', $add_css);
	}
	
	$version = ($ver && is_numeric($ver)) ? $ver : 1;

	if((!$css_files && !$html) || (!$css_files && $html)){
		$css_html = '';
		foreach($add_css as $css){
			$css_html .= "<link href='".$base_css_url.$css."?v={$version}' rel='stylesheet'>\n";
		}
		return $css_html;
	}
}

function get_section($section_name=false){
	if($section_name && $section_name != ''){

		$theme_path = "./application/views/".theme_dir();
		$cache_path = $theme_path . "cache/section/";

		$cache_file_name = $section_name.'.txt';

		if(file_exists($cache_path.$cache_file_name)){
			return file_get_contents($cache_path.$cache_file_name);
		}
	}
	return false;
}

function image($image=false){
	$path = base_url().'images/';
	if($image){
		$path = $path . ltrim($image, '/');
	}
	return $path;
}

if(!function_exists('parse_content')){
	function parse_content($content){
		
		$introduction = stripslashes($content);

		//find all shortcodes
		preg_match_all('/{{(.*?)}}/', stripslashes($introduction), $matches);
		if($matches){
			foreach($matches[0] as $match){
				
				//check shapes
				if(strpos($match, 'triangle') || strpos($match, 'rectangle') || strpos($match, 'square') || strpos($match, 'circle') || strpos($match, 'oval') || strpos($match, 'pentagon') || strpos($match, 'star')){
					$shape = strip_tags(trim(str_replace(array('{{', '}}'), array('', ''), $match)));
					$title = '';
					$align = '';
					if(stristr($shape, ',')){
						$attrs = explode(",", $shape);
						foreach($attrs as $attr){
							$attr = strtolower($attr);
							if(stristr($attr, 'title')){
								$title = trim(str_replace(array("'", '"', 'title', '='), array('', '', '', ''), $attr));
							}elseif(stristr($attr, 'align')){
								$align = trim(str_replace(array("'", '"', 'align', '='), array('', '', '', ''), $attr));
							}else{
								$img = trim($attr);
							}
						}
					}else{
						$img = $shape;
					}	
					$title = ($title != '') ? '<span class="caption">'.$title.'</span>' : '';
					$string = '<div class="shape '.$align.'"><img src="'.base_url().'media/shapes/'.$img.'.png" />'.$title.'</div>';
					$introduction = str_replace($match, $string, $introduction);
					$string = '';
				}else{
				
					$youtube_link = str_replace(array('{{', '}}'), array("", ""), $match);
					$youtube_link = trim($youtube_link);
					
					//find alignment
					$alignment = "left";
					if(strpos($youtube_link, '(left)')){
						$alignment = "left";
						$youtube_link = str_replace("(left)", "", $youtube_link);
					}
					if(strpos($youtube_link, '(right)')){
						$alignment = "right";
						$youtube_link = str_replace("(right)", "", $youtube_link);
					}
					
					$string = '<div class="video '.$alignment.'"><iframe class="youtube-player" type="text/html" id="player" width="500" height="300" src="http://www.youtube.com/embed/'.youtube_id(trim($youtube_link)).'?autoplay=0" frameborder="0"></iframe></div>';
					$introduction = str_replace($match, $string, $introduction);
					$string = '';
				}
			}
		}
		return nl2br($introduction);
	}
}


if(!function_exists("google_auth")){
	function google_auth(){	
		require_once(APPPATH.'/libraries/googleAuth/apiClient.php');
		require_once(APPPATH.'/libraries/googleAuth/contrib/apiOauth2Service.php');		
		
		$client = new apiClient();
		$client->setApplicationName("Apnailakaa");
		
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
		$client->setClientId('325642432914-5lmmupsbihifc6iecb4c8l990ltkm8a2.apps.googleusercontent.com');
		$client->setClientSecret('OJrz-YZKv7gQ7JwSad139qE_');
		$client->setRedirectUri('http://www.apnailakaa.com/secure/ga/gauth');
		//$client->setDeveloperKey(Mage::getStoreConfig("loginbox/googleconfig/developerkey"));
		$oauth2 = new apiOauth2Service($client);
		
		return array($client, $oauth2);
	}
}

if(!function_exists("gauth_url")){
	function gauth_url(){
		list($client, $oauth2) = google_auth();
		return $authUrl = $client->createAuthUrl();
	}
}

if(!function_exists('category_url')){
	
	function category_url($path = false, $relative = false){

		$sub_url = "shop/" . $path;

		if($relative){

			return $sub_url;
		}

		$category_url = home_url($sub_url);

		return $category_url;
	}
}

if(!function_exists('product_url')){
	function product_url($url = false, $relative = false){

		if(!$url || $url == ''){

			return '';
		}

		$url = ltrim(rtrim($url, "/"), "/");

		$sub_url = "{$url}-p";

		if($relative){

			return $sub_url;
		}

		$product_url = home_url($sub_url);

		return $product_url;
	}
}

if(!function_exists('cart_url')){
	function cart_url($path=false){
		return home_url('cart');
	}
}

if(!function_exists('checkout_url')){
	function checkout_url($path=false){
		return home_url('cart/checkout');
	}
}

if(!function_exists('account_url')){
	function account_url($path=false){
		$account_url = home_url('account');

		if($path && $path != ''){
			$path = ltrim(rtrim($path, "/"), "/");
			$account_url = home_url("account/{$path}");
		}

		return $account_url;
	}
}

if(!function_exists('account_details')){
	function account_details($field=false){
		$ci = ci();
		if($ci->session->userdata('account')){
			$session = $ci->session->userdata('account');
			if($field){
				if(array_key_exists($field, $session)){
					return $session[$field];
				}else{
					return false;
				}
			}else{
				return $session;
			}	
		}
		return false;
	}
}

if(!function_exists('decorate_price')){
	function decorate_price($price=false){

		$html = '<div class="price-block">';

			$html .= '<span class="icon"><svg class="rupee-icon" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve"><path fill="currentColor" stroke="currentColor" d="M51.473,13.283h15.73l5.666-8.5H15.122l-5.667,8.5h15.884c1.64,0.073,13.11,0.887,17.224,9.346H15  l-5.667,8.5h34.623c-0.012,0.161-0.023,0.32-0.039,0.483c0,0-0.163,18.603-31.375,15.38V51l0.032,4.387l33.683,39.832h12.794v-1.771  L26.759,53.95v-0.567c0,0,25.414,0.405,29.622-22.094c0,0,0.008-0.056,0.02-0.16h10.68l5.668-8.5H55.943  C55.305,19.506,54.011,16.069,51.473,13.283z"></path></svg></span>';
			$html .= '<span class="price">' . $price . '</span>';
		$html .= '</div>';

		return $html;
	}
}
