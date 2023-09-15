<?php
class Users_model extends WD_model {

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	function __construct(){
		$this->set_table_name("users");
		$this->set_table_index("id");
	}

	public function status_options(){
		return array(self::STATUS_ACTIVE=>"Active", self::STATUS_INACTIVE=>"Inactive");
	}

	public function super_admin_type(){
		return "super";
	}

	public function super_admin_job_profile(){
		return "super_admin";
	}

	function getDataById($id){
		return $this->db->where("id", $id)->get($this->table_name())->row();
	}

	public function get_data_by_username($username, $password){
		return $this->db->where('username', $username)->where('password', md5(trim($password)))->get($this->table_name())->row();
	}

	public function get_by_username($username=false){
		if($username && $username !=''){

			//check for super admin first
			$user_nums = $this->db->where('username', $username)->get($this->table_name())->num_rows();
			if($user_nums > 0){
				return $this->db->where('username', $username)->get($this->table_name())->row();

			}else{
				//Check for users
				$user_nums = $this->db->where('useremail', $username)->get($this->table_name())->num_rows();

				if($user_nums > 0){
					return $this->db->where('useremail', $username)->get($this->table_name())->row();
				}
			}
		}
		return false;
	}

	public function auth($username='', $password=''){
		if($username != '' || $password != ''){
			$data = $this->db->where("username", $username)->where('password', md5($password))->where('user_type', 'teacher')->get($this->table_name())->row();
			if($data){
				$this->load->library('session');
				$user_data = array();
				$user_data['user_id'] = $data->id;
				$user_data['first_name'] = $data->first_name;
				$user_data['last_name'] = $data->last_name;
				$user_data['email'] = $data->useremail;
				
				$this->session->set_userdata('teacher', $user_data);
				return true;
			}
		}
		return false;
	}

	function save($id=false){

		$save = array();

		$this->load->model("roles_model");
		$role_key = trim($_POST['job_profile']);

		if($role_key != ''){
			if($role_key == $this->super_admin_job_profile()){
				$save['job_profile'] = $role_key;
				$save['roles'] = '';

			}else{
				$roles_data = $this->roles_model->fetch_row_by_key($role_key);
				$save['job_profile'] = $roles_data->role_key;
				$save['roles'] = $roles_data->role_title;

			}	
		}else{
			return false;
		}

		if(array_key_exists('username', $_POST) && $_POST['username'] != ''){
			$save['username'] = trim($_POST['username']);
		}

		$save['user_type'] = trim($_POST['user_type']);
		$save['first_name'] = trim($_POST['first_name']);
		$save['last_name'] = trim($_POST['last_name']);
		$save['contact_number'] = trim($_POST['contact_number']);
		$save['status'] = trim($_POST['status']);

		if(array_key_exists('useremail', $_POST) && $_POST['useremail'] != ''){
			$save['useremail'] = trim($_POST['useremail']);
		}

		if(array_key_exists('password', $_POST) && $_POST['password'] != ''){
			$save['password'] = md5($_POST['password']);
		}

		if($id){//update
			$save['updated_at'] = date('Y-m-d H:i:s');
			$this->db->where("id", $id)->update($this->table_name(), $save);

			$act = array();
			$act["module"] = "users";
			$act["act_type"] = "user_update";
			$act["act"] = "user_details_update";
			$act["act_action"] = json_encode($save);
			$act["act_status"] = "update";
			$act["act_key"] = "user_id";
			$act["act_value"] = trim($id);

			$this->acts_model->save_act($act);

			return $id;

		}else{//new insert
			$save['created_at'] = date('Y-m-d H:i:s');
			$this->db->insert($this->table_name(), $save);

			$id = $this->db->insert_id();

			$act = array();
			$act["module"] = "users";
			$act["act_type"] = "user_create";
			$act["act"] = "new_user_create";
			$act["act_action"] = json_encode($save);
			$act["act_status"] = "new";
			$act["act_key"] = "user_id";
			$act["act_value"] = trim($id);

			$this->acts_model->save_act($act);

			return $id;
		}
	}

	public function update_admin(){

		if(isset($_POST['id']) && $_POST['id'] != ''){

			$save['first_name'] = $this->input->post('first_name', TRUE);
			$save['last_name'] = $this->input->post('last_name', TRUE);
			$save['contact_number'] = $this->input->post('contact_number', TRUE);
			$save['updated_at'] = date('Y-m-d H:i:s');

			$this->db->where("id", $_POST['id'])->update($this->table_name(), $save);
			return true;

		}else{
			return false;
		}
	}

	public function update_password(){

		if(isset($_POST['id']) && $_POST['id'] != ''){

			$check_password = false;

			if(isset($_POST['old_password']) && $_POST['old_password'] != ''){

				$check_password = $this->db->where('useremail', $_POST['email'])->where('id', $_POST['id'])->where('password', md5($_POST['old_password']))->get($this->table_name())->row();

				if($check_password){
					$save['password'] = md5($this->input->post('new_password', TRUE));
					$save['updated_at'] = date('Y-m-d H:i:s');

					$this->db->where("id", $_POST['id'])->update($this->table_name(), $save);
					return true;
				}
			}
			return false;
		}else{
			return false;
		}
	}

	public function reset_user_password(){

		if(isset($_POST['id']) && $_POST['id'] != ''){

			$check_password = false;

			if(isset($_POST['password']) && $_POST['password'] != ''){
				$save['password'] = md5($this->input->post('password', TRUE));
				$save['updated_at'] = date('Y-m-d H:i:s');
				$this->db->where("id", trim($_POST['id']))->update($this->table_name(), $save);

				return true;
			}
			return false;
		}else{
			return false;
		}
	}

	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}

	function logout(){
		$this->load->library('session');
		$this->session->unset_userdata('teacher');
	}
}

?>