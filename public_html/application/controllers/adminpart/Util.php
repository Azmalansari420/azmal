<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util extends Admin_Controller {

	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Utility Controller";
		$this->breadcrumb();
	}
	
	public function validate_unique(){
		
		$return = array();
		if(!array_key_exists('table_name', $_POST) || $_POST['table_name'] == ''){
			$return['status'] = 'error';
			$return['message'] = 'Table name missing!!';
			die(json_encode($return));
		}
		if(!array_key_exists('field', $_POST) || $_POST['field'] == ''){
			$return['status'] = 'error';
			$return['message'] = 'Field name missing!!';
			die(json_encode($return));
		}
		if(!array_key_exists('value', $_POST) || $_POST['value'] == ''){
			$return['status'] = 'error';
			$return['message'] = 'Value missing!!';
			die(json_encode($return));
		}
		
		$table_name= trim($_POST['table_name']);
		$field = trim($_POST['field']);
		$value = trim($_POST['value']);
		
		$result = $this->db->where($field, $value)->get($table_name)->num_rows();
		if($result > 0){
			$return['status'] = 'error';
			$return['message'] = "{$value} already exists! Enter another value!";
		}else{
			$return['status'] = 'success';
			$return['message'] = "Unique entry";
		}
		die(json_encode($return));
	}
	
	public function inline_edit(){
		$return = array();
		
		if(!array_key_exists('table_name', $_POST) || $_POST['table_name'] == ''){
			$return['status'] = 'error';
			$return['message'] = 'Table name missing!!';
			die(json_encode($return));
		}
		if(!array_key_exists('field', $_POST) || $_POST['field'] == ''){
			$return['status'] = 'error';
			$return['message'] = 'Field name missing!!';
			die(json_encode($return));
		}
		
		$table_name= trim($_POST['table_name']);
		$field = trim($_POST['field']);
		$value = trim($_POST['value']);
		
		if(trim($_POST['validate_unique']) == 'yes'){
			if(!array_key_exists('row_id', $_POST) || $_POST['row_id'] == ''){
				$return['status'] = 'error';
				$return['message'] = 'row_id missing!!';
				die(json_encode($return));
			}
			if(!array_key_exists('table_index', $_POST) || $_POST['table_index'] == ''){
				$return['status'] = 'error';
				$return['message'] = 'table_index missing!!';
				die(json_encode($return));
			}
			$results = $this->db->where_not_in(trim($_POST['table_index']), array(trim($_POST['row_id'])))->where(trim($field), $value)->get($table_name)->num_rows();
			if($results > 0){
				$return['status'] = 'error';
				$return['message'] = "{$value} already exists! Enter another value!";
			}else{
				$this->db->where(trim($_POST['table_index']), trim($_POST['row_id']))->update($table_name, array($field=>$value));
				$return['status'] = 'success';
				$return['message'] = "Value updated successfully!";
			}
		}else{
			$this->db->where(trim($_POST['table_index']), array(trim($_POST['row_id'])))->update($table_name, array($field=>$value));
			$return['status'] = 'success';
			$return['message'] = "Value updated successfully!";
		}
		
		//$result = $this->db->where($field, $value)->get($table_name)->num_rows();
		//if($result > 0){
		//	$return['status'] = 'error';
		//	$return['message'] = "{$value} already exists! Enter another value!";
		//}else{
		//	$return['status'] = 'success';
		//	$return['message'] = "Unique entry";
		//}
		die(json_encode($return));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */