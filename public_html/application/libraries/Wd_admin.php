<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wd_Admin
{
	var $ci;

	const USER_TYPE_ADMIN = 'admin';
	const USER_TYPE_USER = 'user';
	
	function __construct($params=array()){
		$this->ci =& get_instance();
	}

	public function current_segments_path(){

		$current_segments_path = '';

		if(
			($this->ci->uri->segment(2) != "login" && $this->ci->uri->segment(2) != "logout" && $this->ci->uri->segment(2) != "dashboard")
			&&
			$this->ci->uri->segment(3) != "alerts"
			// && !$this->ci->input->is_ajax_request()
		){
			
			// if($this->ci->uri->segment(2)){
			// 	$current_segments_path = $this->ci->uri->segment(2);
			// }

			// if($this->ci->uri->segment(3)){
			// 	$current_segments_path .= '/'.$this->ci->uri->segment(3);
			// }

			// if($this->ci->uri->segment(4) && !is_numeric($this->ci->uri->segment(4))){
			// 	$current_segments_path .= '/'.$this->ci->uri->segment(4);
			// }

			$config_admin_url = $this->ci->config->item('admin_url');
			$current_url_directory = rtrim($this->ci->router->directory, '/');
			$current_url_directory = ltrim(str_replace($config_admin_url, '', $current_url_directory), '/');

			$current_url_class = $this->ci->router->class;
			$current_url_method = $this->ci->router->method;
			
			$final_current_url = [];

			if($current_url_directory != ''){
				$final_current_url[] = $current_url_directory;	
			}

			if($current_url_class != '' && strtolower($current_url_class) != 'index'){
				$final_current_url[] = $current_url_class;	
			}

			if($current_url_method != '' && strtolower($current_url_method) != 'index'){
				$final_current_url[] = $current_url_method;	
			}
			$current_segments_path = implode('/', $final_current_url);
		}

		return $current_segments_path;
	}

	public function current_user_type(){
		$this->ci->load->model('users_model');

		$user_type = false;
		if($this->ci->session->userdata('admin')){
			$user = $this->ci->session->userdata('admin');
			if($user['user_type'] == $this->ci->users_model->super_admin_type()){
				
				$user_type = self::USER_TYPE_ADMIN;
			}else{
				
				$user_type = self::USER_TYPE_USER;
			}
		}
		return $user_type;
	}	
	
	public function extract_modules($modules, $is_user){
		$this->ci->load->library('session');
		$permissions = (array)$this->ci->session->userdata('admin');
		
		if($is_user){

			$roles = (array_key_exists('permissions', $permissions)) ? $permissions['permissions'] : false;
			$fields = (array_key_exists('fields', $permissions)) ? $permissions['fields'] : false;
			$actions = (array_key_exists('actions', $permissions)) ? $permissions['actions'] : false;

			$current_segments_path = $this->current_segments_path();

			if($current_segments_path != '' && !in_array($current_segments_path, $roles)){
				error_msg("Unauthorised Access!", admin_url("dashboard"));
			}

			$permitted_fields = [];

			//if(!$this->ci->input->is_ajax_request()){
			
				//Check field level permissions
				if($fields && is_array($fields)){
					if( ($current_segments_path != '') && array_key_exists($current_segments_path, $fields)){
						$permitted_fields = $fields[$current_segments_path];
					}
				}
			//}

			$permitted_actions = [];

			//Check field level permissions
			if($actions && is_array($actions)){
				if( ($current_segments_path != '') && array_key_exists($current_segments_path, $actions)){
					$permitted_actions = $actions[$current_segments_path];
				}
			}

			$menu = array();
			if($roles){
				foreach($modules as $key => $module){
					if(in_array($module['path'], $roles)){
						$_module = $module;
						unset($_module['childs']);
						unset($_module['permissions']);
						$menu[$key] = $_module;
					}
					
					if(isset($module['childs'])){
						$have_childs = false;

						foreach($module['childs'] as $child){
							if(array_key_exists('path', $child) && in_array($child['path'], $roles)){
								$menu[$key]['childs'][] = $child;
								$have_childs = true;
							}
						}

						// Re assign menu path, title and icon if have child but not parent.
						if( $have_childs ){
							//check if parent module included
							if( !array_key_exists('path', $menu[$key]) && !array_key_exists('title', $menu[$key]) && !array_key_exists('icon', $menu[$key]) ){
								$_childs = $menu[$key]['childs'];
								$_module = $module;
								unset($_module['childs']);
								unset($_module['permissions']);
								$menu[$key] = $_module;
								$menu[$key]['childs'] = $_childs;
							}
						}
					}
				}	
			}

			return array($menu, $permitted_fields, $permitted_actions);
		}else{
			return array($modules, false, false);
		}
	}

	public function extract_fields($modules){

		$exclude_default = [];

		foreach($modules as $key => $module){
			if(array_key_exists('permissions', $module)){
				foreach($module['permissions'] as $r => $permissions){
					if(array_key_exists('model', $permissions)){
						$model_name = trim($permissions['model']);
						
						//iniciate the module
						$this->ci->load->model($model_name);
						$table_name = $this->ci->$model_name->table_name();
						
						// Exclude table index by default
						// Consider again
						//$exclude_default[] = $this->ci->$model_name->table_index();
						
						//list table fields
						$fields = $this->ci->db->get($table_name)->list_fields();
						
						//remove default exclude fields
						$fields = array_diff($fields, $exclude_default);

						$fields = $this->fields_list($fields);
						$modules[$key]['permissions'][$r]['fields'] = $fields;
					}
				}
			}
		}
		return $modules;
	}

	public function extract_actions($modules){

		$actions = [];

		foreach($modules as $key => $module){
			if(array_key_exists('permissions', $module)){
				foreach($module['permissions'] as $r => $permissions){
					if(array_key_exists('actions', $permissions) && array_key_exists('path', $permissions)){
						
						$actions[$permissions['path']] = $permissions['actions'];
					}
				}
			}
		}
		return $actions;
	}

	public function fields_list($fields){
		$_fields = [];
		foreach($fields as $field){
			$_fields[$field] = ucwords(str_replace("_", " ", $field));
		}
		return $_fields;
	}

	public function can_action($action=false){
		if($action && $action != ''){
			$user_type = $this->ci->session->userdata('wd_admin_user_type');
			
			if($user_type && $user_type == self::USER_TYPE_USER){
				$current_segments_path = $this->current_segments_path();

				$permissions = (array)$this->ci->session->userdata('admin');
				$actions = (array_key_exists('actions', $permissions)) ? $permissions['actions'] : false;
				
				$modules = $this->ci->config->item('modules');
				$all_actions = $this->extract_actions($modules);
				
				$actions_list = $this->merge_actions($all_actions);
				
				if(count($actions_list) > 0){
					if($actions && is_array($actions)){
						if(array_key_exists($current_segments_path, $actions)){
							$current_action = $actions[$current_segments_path];
							if(in_array($action, $current_action)){
								return true;
							}
						}
					}
				}
				return false;
			}
		}
		return true;
	}

	public function merge_actions($actions=[]){
		$_actions = [];
		foreach($actions as $path => $action_list){
			$_actions = array_merge($_actions, array_values($action_list));
		}
		return $_actions;
	}

	public function extract_navigation_items(){
		$modules = $this->ci->config->item('modules');

		$nav_items = [];
		
		foreach($modules as $key => $module){
			if(array_key_exists('include_in_nav', $module)){
				$_module = $module;
				unset($_module['childs']);
				unset($_module['permissions']);

				//Initiate model
				if(array_key_exists('nav_model', $module)){
					$this->ci->load->model($module['nav_model']);
					$_module['fields'] = $this->ci->$module['nav_model']->_wd_navigation_fields();
				}
				$nav_items[$key] = $_module;
			}
		}

		return $nav_items;
	}
}