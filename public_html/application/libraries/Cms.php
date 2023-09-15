<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms
{
	var $ci;
	
	function __construct(){
		$this->ci =& get_instance();
	}

	public function cache_section($cms_data=array(), $do_cache=true){

		$this->ci->load->config('theme_config');
		$this->ci->load->helper('path');

		$theme_dir = $this->ci->config->item('theme_dir');

		$cache_file_name = $cms_data['identifier'] . '.txt';

		$theme_path = APPPATH . "views/theme/{$theme_dir}/";
		
		$cache_path = $theme_path . "cache/section/";

		$content = $cms_data['content'];

		//Process template
		if($cms_data['template'] != ''){

			$template = $cms_data['template'];

			//Process includes
			$data = $this->process_includes((object)$cms_data);

			$template_content = $this->ci->load->view("theme/{$theme_dir}/cms_section/" . $template, $data, true);

			if(array_key_exists('content_position', $cms_data) && $cms_data['content_position'] != ''){
				if('a' == $cms_data['content_position']){
					$content .= $template_content;
				}else{
					$content = $template_content . $content;
				}
			}else{
				$content .= $template_content;
			}
		}

		if($do_cache){
			$this->process_cache($cache_path.$cache_file_name, $content);
		}else{
			return $content;
		}
	}

	public function process_cache($file=false, $content=false){
		if($file && $file != '' && $content){

			try{

				file_put_contents($file, $content);
			}catch (\Exception $e){
		
				die($e->getMessage());
			}
		}	
	}
	
	public function process_includes($object){
		
		$return = [];

		if($object->includes != ''){

			$includes = $object->includes;

			$args = array("type", "name", "method", "var");
			$callbacks = [];

			//find all blocks
			preg_match_all('/\[(.*?)\]/i', stripslashes($includes), $blocks);

			foreach($blocks[1] as $block){
				$breaks = explode(",", $block);

				$callback = [];
				foreach($breaks as $break){

					foreach($args as $arg){
						if(stristr($break, $arg)){
							$callback[$arg] = $this->trim_var($break);
						}
					}
				}
				$callbacks[] = $callback;
			}

			if( count($callbacks) > 0){
				foreach($callbacks as $_callback){

					$name = $_callback['name'];
					$type = $_callback['type'];
					
					$this->ci->load->$type($name);

					if(array_key_exists('method', $_callback)){

						$method = $_callback['method'];

						$var = (array_key_exists('var', $_callback)) ? $_callback['var'] : $_callback['method'];

						if($_callback['type'] == 'helper'){

							$return[$var] = $_callback['method']($object);
						}else{
							
							$return[$var] = $this->ci->$name->$method($object);
						}
					}	
				}
			}
		}

		return $return;
	}

	public function trim_var($break){
		$vars = explode("=", $break);
		$var = trim($vars[1]);
		return str_replace(array('"', "'"), "", $var);
	}
}