<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  WD_Model Class
 *
 * @author        Amit Tanwar.
 */
class WD_Model extends CI_Model
{
	protected $_table_name = NULL;
	protected $_table_index = NULL;
	protected $_save = array();

	private $_post_data = array();
	
	function __construct(){
		parent::__construct();
	}
	
	//
	public function __set($property, $val){
		//prepare data for saving
		if(stristr($property, 'set_')){
			$this->_save[str_ireplace('set_', '', $property)] = clean_insert($val);
		}
	}
	
	/*public function __get($property){
		if(stristr($property, 'get_')){
			$this->_save[str_ireplace('set_', '', $property)] = clean_insert($val);
		}
	}*/

	public function post_data($key, $post_key){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST[$post_key])){
				$this->_post_data[$key] = clean_insert($this->input->post($post_key, TRUE));
			}	
		}
	}

	public function set_post_data($key=false, $value=false){
		$this->_post_data[$key] = clean_insert($value);
	}

	public function get_post_data($key=false){
		if($key && $key != ''){
			if(array_key_exists($key, $this->_post_data)){
				return $this->_post_data[$key];
			}
			return false;
		}else{
			return $this->_post_data;
		}
	}

	public function post_save($table=false){
		if(count($this->_post_data) == 0){
			die("<b>Save post error!!</b> Empty fields!!");
		}	

		$table_name = ($table && !empty($table)) ? $table : $this->table_name();

		$this->validate_post_fields($table_name);

		if($this->db->insert($table_name , $this->_post_data)){
			$this->_post_data = [];
			return $this->db->insert_id();
		}else{
			die("<b>Database Error!!</b><br />".$this->db_errors());
		}
	}

	public function post_update($id=false, $key=false, $table=false){
		if(!$id || $id ==''){
			die("<b>Value missing!!</b> id is missing!!");
		}

		$table_name = ($table && !empty($table)) ? $table : $this->table_name();

		$this->validate_post_fields($table_name);

		$update_key = ($key && !empty($key)) ? $key : $this->table_index($table_name);

		if($update_key && $update_key != ''){
			//check if key exists
			$fields = $this->db->list_fields($table_name);
			if(!in_array($update_key, $fields)){
				die("<b>Key missmatching!!</b><br /> {$update_key} is missing in table {$table_name}!!");
			}
		}

		if($this->db->where($update_key, $id)->update($table_name, $this->_post_data)){
			$this->_post_data = [];
			return $id;
		}else{
			die("<b>Database Error!!</b><br />".$this->db_errors());
		}
	}

	public function validate_post_fields($table_name){
		//validate fields
		$fields = $this->db->list_fields($table_name);

		$extra_fields = array();
		foreach($this->_post_data as $post_key => $post_value){
			if(!in_array($post_key, $fields)){
				$extra_fields[] = $post_key;
			}
		}

		if(count($extra_fields) > 0){
			die("<b>Table fields missmatching!!</b> <br />Following fields are missing in table: ".implode("<br />", $extra_fields));
		}
	}
	
	public function savePost($key=false){
		
		$save = array();
		$fields = $this->db->list_fields($this->table_name());
		foreach($fields as $field){
			if( (isset($_POST[$field])) && ($_POST[$field] != '')){
				$save[$field] = clean_insert($this->input->post($field, TRUE));
			}
		}
		
		if($key){
			$this->db->where($this->table_index(), $key)->update($this->table_name(), $save);
			$this->session->set_flashdata('msg', 'Data updated successfully.');
			return true;
		}else{
			if(in_array('added_on', $fields)){
				$save['added_on'] = date('Y-m-d H:i:s');
			}
			
			$this->db->insert($this->table_name(), $save);
			$this->session->set_flashdata('msg', 'Data saved successfully.');
			return $this->db->insert_id();
		}
		return false;
	} 
	
	//save 
	public function save_dep($key=NULL, $val=NULL){//deprecated due to compatibality issue
		
		try{
			//prepare table fields
			$fields = implode(', ', array_keys($this->_save));
			
			//prepare values
			$values = "'".implode("', '", $this->_save)."'";
			
			//prepare update data
			foreach($this->_save as $k => $d)
				$update[] = $k . "=values({$k}) ";
			
			$update = implode(',', $update);
			$query = "insert into ".$this->table_name()."(".$fields.") values(".$values.") ON DUPLICATE KEY UPDATE ".$update;
			
			return $this->db->query($query);
			/*try{
				if(!is_null($key) && !is_null($val)){//update
					$this->db->where($key, $val)->update($data_table_name, $this->_save);
					return true;
				}else{//insert
					$this->db->insert($this->table_name(), $this->_save);
					return $this->db->insert_id();
				}
			}catch(DEBUG_ERROR $e){
				log_message('error', $e->message);
				return false;
			}*/
		}catch(DEBUG_ERROR $e){
			log_message('error', $e->message);
			return false;
		}
	}
	
	//set the table name of the model   
	function set_table_name($table_name){
		$this->_table_name = $table_name;
	}
	
	function table_name(){
		return $this->_table_name;
	}
	
	function set_table_index($index_key){
		$this->_table_index = $index_key;
	}
	
	function table_index(){
		return $this->_table_index;
	}
	
	//check duplicate entry    
	function is_duplicate($field, $value){
		if(is_null($this->_table_name)){
			log_message('error', 'Table name not defined in model');
			return false;
		}
		
		$rows = $this->db->where($field, clean_insert(clean_unique_code($this->input->post($value, TRUE), "_")))->get($this->_table_name)->num_rows();
		return ($rows>0) ? true : false;
	}
	
	//return a complete result set
	//@parameter status [int 1,0]
	function fetch_data($status=NULL){
		if(!is_null($status)){
			$this->db->where("status", $status);
		}
		return $this->db->get($this->_table_name)->result();
	}
	
	//return a complete result object by field
	//@ parameter field, value, [like, where]
	function fetch_data_by_field($field, $value, $clause='like'){
		if($clause=='like'){
			return $this->db->like($field, $value)->get($this->_table_name)->result();
		}elseif($clause=='where'){
			return $this->db->where($field, $value)->get($this->_table_name)->result();
		}		
		return false;	
	}
	
	//return single row data object
	//@ parameter row id
	function fetch_row_by_id($id=NULL){
		if(!is_null($id)){
			return $this->db->where($this->_table_index, $id)->get($this->_table_name)->row();
		}	
		return false;	
	}
	
	//return first row data object by field
	//@ parameter field, value, [like, where]
	function fetch_row_by_field($field, $value, $clause='where'){
		if($clause=='like'){
			return $this->db->like($field, $value)->get($this->_table_name)->row();
		}elseif($clause=='where'){
			return $this->db->where($field, $value)->get($this->_table_name)->row();
		}		
		return false;	
	}
	
	function save_data($id=false, $data=false, $check_duplicate=false, $unique=false){
		
		if(!$data){
			$data = $_POST;
		}
		
		{//check duplicate entry
			if($check_duplicate && is_array($check_duplicate)){
				if(!$id){//only if fresh insertion
					//if multiple rows available than return false;
					foreach($check_duplicate as $field){
						$check = $this->db->where($field, $data[$field])->get($this->_table_name)->row();
						if($check){
							$this->session->set_flashdata('error_msg', "Duplicate entry for {$field}");
							return false;
						}
					}	
				}
			}
		}
		
		{//list table fields
			$table_fields = $this->db->list_fields($this->_table_name);
			if(!$id){
				if(in_array('created_at', $table_fields)){
					$data['created_at'] = date("Y-m-d h:i:s");
				}
			}	
		}
		
		{//prepare data fields
			foreach($_POST as $post_key => $post_value){
				//map the post data with table fields
				if(in_array($post_key, $table_fields)){
					if($unique && $unique==$post_key){
						$data[$unique] = clean_insert(clean_unique_code($this->input->post($unique, TRUE)));
					}else{
						$data[$post_key] = clean_insert($this->input->post($post_key, TRUE));
					}
				}
			}
		}
		
		{
			if(!$id){//insert
				
				if(in_array('added_on', $table_fields)){
					$data['added_on'] = date('Y-m-d H:i:s');
				}
				$insert = $this->db->insert($this->_table_name, $data);
				if($insert){
					$this->session->set_flashdata('msg', 'Data saved successfully.');
					return $this->db->insert_id();
				}
			}else{//update
				if(in_array('updated_at', $table_fields)){
					$data['updated_at'] = date("Y-m-d H:i:s");
				}
				
				$update = $this->db->where("id", $id)->update($this->_table_name, $data);
				if($update){
					$this->session->set_flashdata('msg', 'Data updated successfully.');
					return $id;
				}
			}
		}
		return false;
	}
	
	public function remove($id){
		return $removed = $this->db->where($this->_table_index, $id)->delete($this->_table_name);
	}

	public function db_errors(){
		$error_msg = '';
		$log_message = '';
		if($this->db->error() && is_array($this->db->error())){
			$errors = $this->db->error();
			foreach($errors as $code => $message){
				$error_msg .= "<b>".$code.":</b> ".$message."<br />";
				$log_message .= $code."::".$message;
			}
		}
		log_message('error', $log_message);
		return $error_msg;
	}
} 