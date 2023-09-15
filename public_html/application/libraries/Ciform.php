<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ciform
{
	var $ci;
	
	//form elements
	public $data;
	public $elements;
	public $form_data;
	public $append_form_body;
	public $before_buttons;

	public $popup_form;

	var $controller_name;

	var $pop_form;
	var $pop_form_title;
	var $form_data_url;
	
	function __construct(){
		$this->ci =& get_instance();

		$this->ci->load->helper('url');
		$url = implode("/", $this->ci->uri->segment_array());
		$this->controller_name = $url;

		$this->pop_form = ($this->pop_form) ? true : false;
		$this->pop_form_title = ($this->pop_form_title && $this->pop_form_title != '') ? $this->pop_form_title : '';

		$this->permitted_fields = $this->ci->config->item('permitted_fields');
	}
	
	/*
	create form
	@input			form_title string
	@input			method string
	
	@return			form view
	*/
	public function create_form($form_title='', $method='POST'){
		
		{
			$this->ci->load->helper('form');
			$this->ci->load->library('form_validation');

			$tiny_mce = false;
			$calendar = false;
			$rating = false;
			$tag_manager = false;
			$single_file_uploader = false;
			$gallery_uploader = false;
			
			//form sections
			$sections = array();
			foreach($this->sections as $section => $elements){
				
				$_elements = array();
				foreach($elements as $e => $element){
					
					if($element){
						
						//find if multicol row
						if(array_key_exists('fields', $element)){
							
							$field_class = '';
							if(array_key_exists('field_class', $element)){
								$field_class = $element['field_class'];
								unset($element['field_class']);
							}
							
							$_fields = array();
							//validate each element
							foreach($element as $_e){

								//check user level field permission
								if( $this->permitted_fields && is_array($this->permitted_fields) ){
									if( array_key_exists('name', $_e) && !in_array($_e['name'], $this->permitted_fields) ){
										continue;
									}
								}
								
								$_fields[] = $_e;
								
								if(isset($_e["type"]) && $_e["type"]=='texteditor'){
									$tiny_mce = true;
								}
								if(isset($_e["calendar"])){
									$calendar = true;
								}
								if(isset($_e["type"]) && $_e["type"]=='tags'){
									$tag_manager = true;
								}
								if(isset($_e["type"]) && $_e["type"]=='rating'){
									$rating = true;
								}
								if(isset($_e["type"]) && $_e["type"]=='image'){
									if(array_key_exists("ajax", $_e)){
										$single_file_uploader = true;
									}	
								}
								if(isset($_e['validation']) && !isset($_e['disabled'])){
									//disable validation if edit
									if(isset($this->form_data[$_e['name']])){
										$validations = $this->break_validation($_e['validation']);
										if($_e['type'] == 'file'){
											if(empty($_FILES[$_e['name']]['name'])){
												$this->ci->form_validation->set_rules($_e['name'], $_e['label'], $validations);
											}
										}else{
											if($_e["type"] != 'view'){
												$this->ci->form_validation->set_rules($_e['name'], $_e['label'], $validations);
											}		
										}	
										
									}else{
										
										if($_e['type'] == 'file'){
											if(empty($_FILES[$_e['name']]['name'])){
												$this->ci->form_validation->set_rules($_e['name'], $_e['label'], $_e['validation']);
											}
										}else{
											if($_e["type"] != 'view'){
												$this->ci->form_validation->set_rules($_e['name'], $_e['label'], $_e['validation']);
											}
										}		
									}
								}
								
							}
							//
							$_elements[] = array('fields' => $_fields, 'field_class'=>$field_class);
						}else{

							//check user level field permission
							if( $this->permitted_fields && is_array($this->permitted_fields) && $element['type'] != 'fieldset'){
								if( array_key_exists('name', $element) && !in_array($element['name'], $this->permitted_fields) ){
									continue;
								}
							}

							$_elements[] = $element;
						
							if(isset($element["type"]) && $element["type"]=='texteditor'){
								$tiny_mce = true;
							}
							if(isset($element["calendar"])){
								$calendar = true;
							}
							if(isset($element["type"]) && $element["type"]=='rating'){
								$rating = true;
							}
							if(isset($element["type"]) && $element["type"]=='tags'){
								$tag_manager = true;
							}
							if(isset($element["type"]) && $element["type"]=='image'){
								if(array_key_exists("ajax", $element)){
									$single_file_uploader = true;
								}								
							}
							if(isset($element['validation']) && !isset($element['disabled'])){
								//disable validation if edit
								if(isset($this->form_data[$element['name']])){
									$validations = $this->break_validation($element['validation']);
									if($element['type'] == 'file'){
										if(empty($_FILES[$element['name']]['name'])){
											$this->ci->form_validation->set_rules($element['name'], $element['label'], $validations);
										}
									}else{
										$_ele_name = ($element['type'] == 'multiselect') ? $element['name']."[]" : $element['name'];
										
										if($element["type"] != 'view'){
											$this->ci->form_validation->set_rules($_ele_name, $element['label'], $validations);
										}
									}	
									
								}else{
									
									if($element['type'] == 'file'){
										if(empty($_FILES[$element['name']]['name'])){
											$this->ci->form_validation->set_rules($element['name'], $element['label'], $element['validation']);
										}
									}else{
										if($element["type"] != 'view'){
											$this->ci->form_validation->set_rules($element['name'], $element['label'], $element['validation']);
										}
									}		
								}
							}
						}	
					}
				}
				
				$data['form_sections'][$section] = $_elements;
			}
			
			//form elements are stored as array in elements object inside controller before calling the createForm function.
			$data['form_data'] 					= $this->form_data;
			$data['append_form_body']			= $this->append_form_body;
			
			$data['form_title'] = $form_title;
			$data['form_method'] = $method;
			
			$data['remove_index'] = isset($this->remove_index) ? $this->remove_index : "" ;
			$data['remove_link'] = isset($this->remove_link) ? $this->remove_link : "" ;
			$data['remove_js_callback'] = isset($this->remove_js_callback) ? $this->remove_js_callback : "" ;
			
			$data['cancel_link'] = isset($this->cancel_link) ? $this->cancel_link : "";
			$data['cancel_title'] = isset($this->cancel_title) ? $this->cancel_title : "Back";
			
			$data['before_buttons'] = isset($this->before_buttons) ? $this->before_buttons : '';
			
			$data['buttons'] = isset($this->buttons) ? $this->buttons : "";
			
			//save button update
			$data['save_title'] = (isset($this->save_title)) ? $this->save_title : 'Save';
			$data['save_link'] = (isset($this->save_link)) ? $this->save_link : true;
			
			$data['update_title'] = (isset($this->update_title)) ? $this->update_title : 'Update';

			//Popup form
			$data['popup_form'] = (isset($this->popup_form) && $this->popup_form == true) ? $this->popup_form : false;

			$data['post_url'] = base_url().$this->controller_name;

			$data['pop_form'] = $this->pop_form;
			$data['pop_form_title'] = $this->pop_form_title;
			$data['form_data_url'] = $this->form_data_url;
		}
		
		{//prepare tinymce
			if($tiny_mce){
				add_js(array("libs/tiny_mce/tinymce.min.js", "libs/tiny_mce_setup.js"));
				//add_js(array("libs/ckeditor/ckeditor.js", "libs/ckeditor_setup.js"));
			}
			if($calendar){
				//add_js(array('base/calendar.js'));
				//add_css(array('calendar.css'));
				add_js(array('base/moment.min.js', 'base/datetimepicker.min.js'));
				add_css(array('glyphicons.css', 'datetimepicker.min.css'));
			}
			if($rating){
				add_js(array('rateyo.js'));
			}
			if($tag_manager){
				add_js(array('base/tagmanager.js'));
			}
			
			//$single_file_uploader
			
			if($gallery_uploader){
				add_css(array('file-upload/jquery.fileupload.css'));
				add_js(array('file-upload/load-image.all.min.js', 'file-upload/canvas-to-blob.min.js', 'file-upload/vendor/jquery.ui.widget.js', 'file-upload/jquery.iframe-transport.js', 'file-upload/jquery.fileupload.js', 'file-upload/jquery.fileupload-process.js', 'file-upload/jquery.fileupload-image.js', 'file-upload/jquery.fileupload-validate.js'));
			}

			if($this->pop_form){
				add_js(array('wd-form.js'));
			}
		}
		
		if ($this->ci->form_validation->run() === FALSE){
			//assign form to config var
			$this->ci->load->config('app_config');
			$this->ci->config->set_item('pop_form', $this->pop_form);
			$this->ci->config->set_item('pop_form_title', $this->pop_form_title);
			$this->ci->config->set_item('ci_form', $this->ci->load->view("core/form/form", $data, TRUE));
			return true;
		}else{
			$form = false;
		}
	}
	
	function break_validation($validation){
		$_v = explode('|', $validation);
		$validations = array();
		foreach($_v as $v){
			if(!stristr($v, 'is_unique')){
				$validations[] = $v;
			}
		}
		return implode('|', $validations);
	}
}