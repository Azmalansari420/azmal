<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme
{
	var $ci;

	protected $theme;
	protected $theme_dir;
	protected $assets_dir;

	protected $global_js;
	protected $global_css;
	
	protected $site_title;

	protected $social_card_image;

	protected $js_files;
	protected $css_files;

	//Mobile theme setup
	protected $mobile_theme_dir = false;
	protected $mobile_assets_dir = false;
	protected $mobile_global_js = [];
	protected $mobile_global_css = [];

	// Merge / Compress js or css
	protected $merge_js = false;
	protected $merge_css = false;

	function __construct(){
		$this->ci =& get_instance();

		$this->ci->load->config('theme_config');

		// Theme Name
		$this->set_theme($this->ci->config->item('theme'));

		// Theme Directory
		$this->set_theme_dir($this->ci->config->item('theme_dir'));

		// Theme Assets Directory
		$this->set_assets_dir($this->ci->config->item('assets_dir'));

		//Global js array
		$this->global_js = $this->ci->config->item('js');
		$this->js_files = $this->global_js;

		//Global css array
		$this->global_css = $this->ci->config->item('css');
		$this->css_files = $this->global_css;

		$this->site_title = $this->ci->config->item('site_title');

		$this->social_card_image = $this->ci->config->item('social_card_image');

		//Mobile setup
		if($this->ci->config->item('mobile_theme_dir')){
			$this->set_mobile_theme_dir($this->ci->config->item('mobile_theme_dir'));
		}

		if($this->ci->config->item('mobile_assets_dir')){
			$this->set_mobile_assets_dir($this->ci->config->item('mobile_assets_dir'));
		}

		if($this->ci->config->item('mobile_js')){
			$this->mobile_global_js = $this->ci->config->item('mobile_js');
		}

		if($this->ci->config->item('mobile_assets_dir')){
			$this->mobile_css = $this->ci->config->item('mobile_css');
		}

		//
		if(ENVIRONMENT == 'production'){
			$this->merge_js = true;
			$this->merge_css = true;
		}	
	}

	public function set_theme($theme_name){
		$this->theme = $theme_name;
	}

	public function get_theme(){
		return $this->theme;
	}

	public function set_theme_dir($theme_dir){
		$this->theme_dir = $theme_dir;
	}

	public function get_theme_dir(){
		return $this->theme_dir;
	}

	public function set_assets_dir($assets_dir){
		$this->assets_dir = $assets_dir;
	}

	public function get_assets_dir(){
		return $this->assets_dir;
	}

	public function get_site_title(){
		return $this->site_title;
	}

	public function get_social_card_image(){
		return $this->social_card_image;
	}

	//Mobile setup
	public function set_mobile_theme_dir($mobile_theme_dir){
		 $this->mobile_theme_dir = $mobile_theme_dir;
	}

	public function get_mobile_theme_dir(){
		return $this->mobile_theme_dir;
	}

	public function set_mobile_assets_dir($mobile_assets_dir){
		$this->mobile_assets_dir = $mobile_assets_dir;
   	}

	public function get_mobile_assets_dir(){
		return $this->mobile_assets_dir;
	}

   	public function is_mobile_theme(){
		if($this->get_mobile_theme_dir() !== false){
			return true;
		}
		return false;
   	}

	/*
	* Returns file path
	*/
	public function get_file($path){

	}

	/*
	* returns template absolute path
	*/
	public function get_template($template_path=false){

		if($template_path && $template_path != ''){
			return APPPATH . "views/".$this->theme_dir($template_path).".php";
		}
		return APPPATH . "views/".$this->theme_dir();
	}

	/*
	* returns template relative theme path
	*/
	public function get_view($template_path=false){

		if($template_path && $template_path != ''){
			return $this->theme_dir($template_path);
		}
		return false;
	}

	// Not in use, need to work on this function.
	/*
	* returns include template
	*/
	public function include_template($template_path=false){

		if($template_path && $template_path != ''){
			include $this->get_template($template_path);
		}
		return false;
	}

	/*
	* returns relative path to the theme.
	* Automatically detects if mobile theme enabled and try to find the same file in the mobile theme.
	*/
	function theme_dir($path=false){

		// Mobile first approach
		// Find mobile file first if exists

		// Check if mobile theme enabled
		if($this->is_mobile_theme()){

			// Check if mobile request
			if($this->is_mobile()){
				$theme_dir = "theme/".$this->get_mobile_theme_dir()."/";

				if($path && $path != ''){
					
					// File path suffixed .php
					if(is_file(APPPATH . 'views/' . $theme_dir . ltrim($path, "/") . '.php')){
						return $theme_dir . ltrim($path, "/");
					}else{

						// Return theme file if mobile file not exists
						$theme_dir = "theme/".$this->get_theme_dir()."/";
						return $theme_dir . ltrim($path, "/");
					}
				}
				return $theme_dir;	
			}
		}

		$theme_dir = "theme/".$this->get_theme_dir()."/";
	
		if($path && $path != ''){
			$theme_dir = $theme_dir . ltrim($path, "/");
		}
	
		return $theme_dir;
	}

	public function assets_dir(){

		// Check if mobile theme enabled
		if($this->is_mobile_theme()){

			// Check if mobile request
			if($this->is_mobile()){
				return "assets/theme/" . $this->get_mobile_assets_dir() . "/";
			}
		}

		return "assets/theme/" . $this->get_assets_dir() . "/";
	}

	function images_url($file=false){
		$url = base_url() . $this->assets_dir() . "images/";
		if($file && $file != ''){
			$url = $url . ltrim($file, "/");
		}
		return $url;
	}

	function js_url($file=false){
		$js_url = base_url() . $this->assets_dir() . "js/";
		if($file && $file != ''){
			$js_url = $js_url . ltrim($file, "/");
		}
		return $js_url;
	}
	
	function css_url($file=false){
		$css_url = base_url() . $this->assets_dir() . "css/";
		if($file && $file != ''){
			$css_url = $css_url . ltrim($file, "/");
		}
		return $css_url;
	}

	public function process_includes($includes){
		if($includes && count($includes) > 0){
			if(array_key_exists('js', $includes)){

				$include_js = [];

				foreach($includes['js'] as $js){
					$include_js[] = $js['name'];
				}

				$this->add_js($include_js);
			}

			if(array_key_exists('css', $includes)){

				$include_css = [];

				foreach($includes['css'] as $css){
					$include_css[] = $css['name'];
				}

				$this->add_css($include_css);
			}
		}
	}

	/*
	Add Mobile only js
	*/
	public function mobile_js($files=[]){

		// Check if mobile theme enabled
		if($this->is_mobile_theme()){

			// Check if mobile request
			if($this->is_mobile()){
				
				// Add only if mobile theme
				$this->add_js($files);
			}
		}
	}

	/*
	Add Mobile only css
	*/
	public function mobile_css($files=[]){

		// Check if mobile theme enabled
		if($this->is_mobile_theme()){

			// Check if mobile request
			if($this->is_mobile()){
				
				// Add only if mobile theme
				$this->add_css($files);
			}
		}
	}

	/*
	* Add js files
	*/
	public function add_js($js_files=false, $html=false, $ver=4.1){
		
		//register js
		if($js_files && !$html){
			if(is_array($js_files)){
				foreach($js_files as $js){

					$this->js_files[] = rtrim(ltrim($js, "/"), "/");
				}
			}else{
				$this->js_files[] = rtrim(ltrim($js, "/"), "/");
			}
		}

		$version = ($ver && is_numeric($ver)) ? $ver : 1;
	
		if((!$js_files && !$html) || (!$js_files && $html)){

			//ENVIRONMENT
			if($this->merge_js == true){
				
				$js_base_path = "./" . $this->assets_dir() . "js/";

				$file_name = 0;
				foreach($this->js_files as $js){
					$file_hash = preg_replace("/[^0-9]/", "", hash('fnv132', $js));
					$mod_time = filectime($js_base_path . $js);
					$file_name = $file_name + ($file_hash + $mod_time);
				}
				if(file_exists($js_base_path . $file_name . ".js")){
					return '<script type="text/javascript" src="'.$this->js_url($file_name . ".js").'?v='.$version.'"></script>';
				}else{

					$merged_js = $this->compress_js($this->js_files);
					
					file_put_contents($js_base_path . $file_name . ".js", $merged_js);

					if(file_exists($js_base_path . $file_name . ".js")){
						return '<script type="text/javascript" src="'.$this->js_url($file_name . ".js").'?v='.$version.'"></script>';
					}
				}
			}	

			$js_html = [];
			foreach($this->js_files as $js){
				$js_html[] = '<script type="text/javascript" src="'.$this->js_url($js).'?v='.$version.'"></script>';
			}
			return implode("\n", $js_html);
		}
	}
	
	/*
	* Add css files
	*/
	function add_css($css_files=false, $html=false, $ver=4.1){

		//register css
		if($css_files && !$html){
			if(is_array($css_files)){
				foreach($css_files as $css){
					$this->css_files[] = rtrim(ltrim($css, "/"), "/");
				}
			}else{
				$this->css_files[] = $css_files;
			}
		}
		
		$version = ($ver && is_numeric($ver)) ? $ver : 1;
	
		if((!$css_files && !$html) || (!$css_files && $html)){

			//ENVIRONMENT
			if($this->merge_css == true){
				
				$css_base_path = "./" . $this->assets_dir() . "css/";

				$file_name = 0;
				foreach($this->css_files as $css){
					$file_hash = preg_replace("/[^0-9]/", "", hash('fnv132', $css));
					$mod_time = filectime($css_base_path . $css);
					$file_name = $file_name + ($file_hash + $mod_time);
				}
				if(file_exists($css_base_path . $file_name . ".css")){
					return "<link href='".$this->css_url($file_name).".css?v={$version}' rel='stylesheet'>";
				}else{

					$merged_css = $this->compress_css($this->css_files);
					
					file_put_contents($css_base_path . $file_name . ".css", $merged_css);

					if(file_exists($css_base_path . $file_name . ".css")){
						return "<link href='".$this->css_url($file_name).".css?v={$version}' rel='stylesheet'>";
					}
				}
			}	

			$css_html = [];
			foreach($this->css_files as $css){
				$css_html[] = "<link href='".$this->css_url($css)."?v={$version}' rel='stylesheet'>";
			}
			return implode("\n", $css_html);
		}
	}

	function get_section($section_name=false){
		if($section_name && $section_name != ''){
	
			$theme_path = APPPATH . "views/theme/".$this->get_theme_dir();
			$cache_path = $theme_path . "/cache/section/";
	
			$cache_file_name = $section_name.'.txt';

			if(file_exists($cache_path.$cache_file_name)){
				return file_get_contents($cache_path.$cache_file_name);
			}
		}
		return false;
	}

	function is_mobile(){
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
			return true;
		}
		return false;
	}

	public function compress_css($files=[]){

		$merged_content = "";
		foreach($files as $file){
			$merged_content .= file_get_contents("./" . $this->assets_dir() . "css/" . $file);
		}

		// Remove comments
		$merged_content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $merged_content);

		// Remove space after colons
		$merged_content = str_replace(': ', ':', $merged_content);

		// Remove whitespace
		$merged_content = str_replace(array("\n", "\t", '  ', '    ', '    '), '', $merged_content);

		return $merged_content;
	}

	public function compress_js($files=[]){

		$merged_content = "";
		foreach($files as $file){
			$merged_content .= file_get_contents("./" . $this->assets_dir() . "js/" . $file);
		}

		// Remove comments
		$merged_content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $merged_content);

		// Remove whitespace
		$merged_content = str_replace(array("\t", '  ', '    ', '    '), '', $merged_content);

		return $merged_content;
	}
}