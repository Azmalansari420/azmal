<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists("ci")){
	function ci(){
		return $ci =& get_instance();
	}
}	
if(!function_exists("admin_url")){
	function admin_url($path=false){
		$ci = ci();
		//load app_config
		$ci->load->config('app_config');
		$admin_url	= $ci->config->item('admin_url');
		$url = rtrim(base_url(), "/") . "/".$admin_url."/";
		if($path){
			$url .= rtrim(ltrim($path, "/"), "/") . "/";
		}
		return $url;
	}
}

function add_js($js_files=false, $html=false){
	$url = base_url("assets/admin/js");
	$ci = ci();

	//load app_config
	$ci->load->config('app_config');
	$add_js = $ci->config->item('add_js');
	
	//register js
	if($js_files && !$html){
		if(is_array($js_files)){
			foreach($js_files as $js){
				$add_js[] = rtrim(ltrim($js, "/"), "/");
			}
		}else{
			$add_js[] = $js_files;
		}
		$ci->config->set_item('add_js', $add_js);
	}
	
	if((!$js_files && !$html) || (!$js_files && $html)){
		$js_html = '';
		foreach($add_js as $js){
			$js_html .= '<script type="text/javascript" src="'.$url."/".$js.'?v=4.4"></script>'."\n";
		}
		return $js_html;
	}
}

function add_css($css_files=false, $html=false){
	$url = base_url("assets/admin/style");
	$ci = ci();

	//load app_config
	$ci->load->config('app_config');
	$add_css = $ci->config->item('add_css');
	
	//register css
	if($css_files && !$html){
		if(is_array($css_files)){
			foreach($css_files as $css){
				$add_css[] = rtrim(ltrim($css, "/"), "/");
			}
		}else{
			$add_css[] = $css_files;
		}
		$ci->config->set_item('add_css', $add_css);
	}
	
	if((!$css_files && !$html) || (!$css_files && $html)){
		$css_html = '';
		foreach($add_css as $css){
			$css_html .= "<link href='".$url.'/'.$css."?v=4' rel='stylesheet'>\n";
		}
		return $css_html;
	}
}

function add_admin_css($css_files=false, $html=false){
	$url = base_url("style/admin");
	$ci = ci();

	//load app_config
	$ci->load->config('app_config');
	$add_css = $ci->config->item('add_css');
	
	//register css
	if($css_files && !$html){
		if(is_array($css_files)){
			foreach($css_files as $css){
				$add_css[] = rtrim(ltrim($css, "/"), "/");
			}
		}else{
			$add_css[] = $css_files;
		}
		$ci->config->set_item('add_css', $add_css);
	}
	
	if((!$css_files && !$html) || (!$css_files && $html)){
		$css_html = '';
		foreach($add_css as $css){
			$css_html .= "<link href='".$url.'/'.$css."' rel='stylesheet'>\n";
		}
		return $css_html;
	}
}

function admin_view($path=false){
	if(!$path) return false;
	
	$ci = ci();
	//load app_config
	$ci->load->config('app_config');
	$admin_dir	= $ci->config->item('admin_dir');
	
	return $admin_dir."/" . rtrim(ltrim($path, "/"), "/");
}

function admin_header(){
	return admin_view("common/header");
}

function admin_footer(){
	return admin_view("common/footer");
}

if(!function_exists('category_dropdown')){
	function category_dropdown($selected=false){
		$ci = ci();
		$ci->load->model('category_model');
		return $category_dropdown = $ci->category_model->category_dropdown($selected);
	}
}

if(!function_exists('user_details')){
	function user_details($field=false){
		$ci = ci();
		if($ci->session->userdata('admin')){
			$session = $ci->session->userdata('admin');
			if($field){
				if(array_key_exists($field, $session)){
					return $session[$field];
				}
			}else{
				return $session;
			}	
		}
		return false;
	}
}

if(!function_exists('have_permission')){
	function have_permission($permission_key = false){
		$ci = ci();
		if($ci->session->userdata('admin')){
			$session = $ci->session->userdata('admin');
			
			//Check user type
			$ci->load->model('users_model');
			
			if($ci->users_model->super_admin_type() == $session['user_type']){
				//Super admin have all permissions
				return true;
			}else{
				$permissions = $session['permissions'];
				
				if($permission_key && $permission_key != '' && in_array($permission_key, $permissions)){
					return true;
				}
			}
		}
		return false;
	}
}

if(!function_exists('email_signature')){
	function email_signature(){
		
		$user_name = user_details('first_name');
		if(user_details('last_name') && user_details('last_name') != ''){
			$user_name .= " ".user_details('last_name');
		}
		
		$user_name = ucwords($user_name);
		
		//signature
		
		$signature = "<br /><br /><br /><strong>{$user_name}</strong><br />";
		
		$signature .= "<p><strong>".trim(get_setting('email-signature-company-name'))."</strong></p>";
		$signature .= "<p>".nl2br(trim(get_setting('email-signature-address')))."</p>";
		$signature .= "<strong>Web:</strong> ".trim(get_setting('email-signature-url'))."<br />";
		$signature .= "<strong>Emali:</strong> ".trim(get_setting('email-signature-email'))."<br />";
		$signature .= "<p>".nl2br(trim(get_setting('email-signature-contact')))."</p>";
		
		return $signature;
	}
}	
////////////////////////////////////////////////////////////////////////////////
/////////////    ADMIN MENU     ////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
