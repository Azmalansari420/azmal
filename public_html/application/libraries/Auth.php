<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	var $CI;
	
	//this is the expiration for a non-remember session
	var $session_expire	= 6000;

	function __construct()
	{
		$this->CI =& get_instance();
		// $this->CI->load->library('encrypt');
		$this->CI->load->library('session');
		//$admin_session_config = array('sess_cookie_name' => 'admin_session_config','sess_expiration' => 0);
		//$this->CI->load->library('session', $admin_session_config, 'admin_session');
		
		$this->CI->load->helper('url');
	}
	
	function check_access($access, $default_redirect=false, $redirect = false)
	{
		/*
		we could store this in the session, but by accessing it this way
		if an admin's access level gets changed while they're logged in
		the system will act accordingly.
		*/
		
		$admin = $this->CI->admin_session->userdata('admin');
		
		$this->CI->db->select('access');
		$this->CI->db->where('id', $admin['id']);
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('admin');
		$result	= $result->row();
		
		//result should be an object I was getting odd errors in relation to the object.
		//if $result is an array then the problem is present.
		if(!$result || is_array($result))
		{
			$this->logout();
			return false;
		}
	//	echo $result->access;
		if ($access)
		{
			if ($access == $result->access)
			{
				return true;
			}
			else
			{
				if ($redirect)
				{
					redirect($redirect);
				}
				elseif($default_redirect)
				{
					redirect(admin_url('dashboard'));
				}
				else
				{
					return false;
				}
			}
			
		}
	}
	
        /*
	this checks to see if the admin is logged in
	we can provide a link to redirect to, and for the login page, we have $default_redirect,
	this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
	*/
	
	//function check_user($user_name){
		
	//}
	
	function is_admin_logged_in($redirect = false, $default_redirect = true)
	{
	
		//var_dump($this->CI->admin_session->userdata('session_id'));

		//$redirect allows us to choose where a customer will get redirected to after they login
		//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.

		$admin = $this->CI->session->userdata('admin');
		
		if (!$admin){//only super user is allowed to attempt as admin
			if ($redirect){
				$this->CI->session->set_flashdata('redirect', $redirect);
			}
			
			if($default_redirect){
				redirect(admin_url('login'));
			}
			
			return false;
		}
		else
		{
			
			//check if the session is expired if not reset the timer
			if($admin['expire'] && $admin['expire'] < time()){// && $admin['user_type']=="super"
				
				$this->logout();
				if($redirect)
				{
					$this->CI->admin_session->set_flashdata('redirect', $redirect);
				}

				if($default_redirect)
				{
					redirect(admin_url('login'));
				}

				return false;
			}
			else
			{

				//update the session expiration to last more time if they are not remembered
				if($admin['expire'])
				{
					$admin['expire'] = time()+$this->session_expire;
					$this->CI->session->set_userdata(array('admin'=>$admin));
				}

			}
			return true;
		}
	}
	/*
	this function does the logging in.
	*/
	function login_admin($username, $password, $remember=false)
	{
		
		//$this->logout();
		$this->CI->db->select('*');
		$this->CI->db->where('username', $username);
		$this->CI->db->where('password', md5($password));
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('users');
		$result	= $result->row_array();
		
		if (sizeof($result) > 0)
		{
			$admin = array();
			$admin['admin']						= array();
			$admin['admin']['id']				= $result['id'];
			$admin['admin']['roles'] 			= $result['roles'];
			$admin['admin']['username'] 		= $result['username'];
			$admin['admin']['useremail'] 		= $result['useremail'];
			$admin['admin']['first_name']		= $result['first_name'];
			$admin['admin']['last_name']		= $result['last_name'];
			$admin['admin']['contact_number']	= $result['contact_number'];
			$admin['admin']['user_type']		= "super";
			$admin['admin']['job_profile']		= $result['job_profile'];
			$admin['admin']['roles']		= $result['roles'];
			$admin['admin']['profile_thumb']	= $result['profile_thumb'];
			$admin['admin']['profile_pic']		= $result['profile_pic'];
			
			
			
			if(!$remember)
			{
				$admin['admin']['expire'] = time()+$this->session_expire;
			}
			else
			{
				$admin['admin']['expire'] = false;
			}

			$this->CI->session->set_userdata($admin);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function is_user_logged_in($redirect = false, $default_redirect = true)
	{
	
		//var_dump($this->CI->admin_session->userdata('session_id'));

		//$redirect allows us to choose where a customer will get redirected to after they login
		//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.
		
		$admin = $this->CI->session->userdata('admin');
		
		if (!$admin){//only super user is allowed to attempt as admin
			if ($redirect){
				$this->CI->session->set_flashdata('redirect', $redirect);
			}
			
			if($default_redirect){
				redirect(base_url());
			}
			
			return false;
		}
		else
		{
			
			//check if the session is expired if not reset the timer
			if($admin['expire'] && $admin['expire'] < time()){// && $admin['user_type']=="super"
				
				$this->logout();
				if($redirect)
				{
					$this->CI->admin_session->set_flashdata('redirect', $redirect);
				}

				if($default_redirect)
				{
					redirect(base_url());
				}

				return false;
			}
			else
			{

				//update the session expiration to last more time if they are not remembered
				if($admin['expire'])
				{
					$admin['expire'] = time()+$this->session_expire;
					$this->CI->session->set_userdata(array('admin'=>$admin));
				}

			}
			return true;
		}
	}
	/*
	this function does the logging in.
	*/
	function login_user($username, $password, $remember=false)
	{
		$this->logout();
		$this->CI->db->select('*');
		//login by email if username is email id 
		if( stristr($username, "@") ){
			$this->CI->db->where('useremail', $username);
		}else{
			$this->CI->db->where('username', $username);
		}
		
		$this->CI->db->where('password', md5($password));
		$this->CI->db->where('user_type', "user");
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('users');
		$result	= $result->row_array();
		
		if (sizeof($result) > 0)
		{
			$admin = array();
			$admin['user']						= array();
			$admin['user']['id']				= $result['id'];
			$admin['user']['roles'] 			= $result['roles'];
			$admin['user']['first_name']		= $result['first_name'];
			$admin['user']['last_name']			= $result['last_name'];
			$admin['user']['contact_number']	= $result['contact_number'];
			$admin['user']['user_type']			= "user";
			
			//$this->CI->session->set_userdata(array("test"=>"ok"));
			
			if(!$remember)
			{
				$admin['user']['expire'] = time()+$this->session_expire;
			}
			else
			{
				$admin['user']['expire'] = false;
			}
			$this->CI->session->set_userdata($admin);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	this function does the logging out
	*/
	function logout(){
	
		$this->CI->session->unset_userdata('user');
		$this->CI->session->unset_userdata('admin');
		$this->CI->session->sess_destroy();
	}

	/*
	This function resets the admins password and emails them a copy
	*/
	function reset_password($email)
	{
		$admin = $this->get_admin_by_email($email);
		if ($admin)
		{
			$this->CI->load->helper('string');
			$this->CI->load->library('email');
			
			$new_password		= random_string('alnum', 8);
			$admin['password']	= sha1($new_password);
			$this->save_admin($admin);
			
			$this->CI->email->from($this->CI->config->item('email'), $this->CI->config->item('site_name'));
			$this->CI->email->to($email);
			$this->CI->email->subject($this->CI->config->item('site_name').': Admin Password Reset');
			$this->CI->email->message('Your password has been reset to '. $new_password .'.');
			$this->CI->email->send();
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	This function gets the admin by their email address and returns the values in an array
	it is not intended to be called outside this class
	*/
	private function get_admin_by_email($email)
	{
		$this->CI->db->select('*');
		$this->CI->db->where('email', $email);
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('admin');
		$result = $result->row_array();

		if (sizeof($result) > 0)
		{
			return $result;	
		}
		else
		{
			return false;
		}
	}
	
	/*
	This function takes admin array and inserts/updates it to the database
	*/
	function save($admin)
	{
		if ($admin['id'])
		{
			$this->CI->db->where('id', $admin['id']);
			$this->CI->db->update('admin', $admin);
		}
		else
		{
			$this->CI->db->insert('admin', $admin);
		}
	}
	
	
	/*
	This function gets a complete list of all admin
	*/
	function get_admin_list()
	{
		$this->CI->db->select('*');
		$this->CI->db->order_by('lastname', 'ASC');
		$this->CI->db->order_by('firstname', 'ASC');
		$this->CI->db->order_by('email', 'ASC');
		$result = $this->CI->db->get('admin');
		$result	= $result->result();
		
		return $result;
	}

	/*
	This function gets an individual admin
	*/
	function get_admin($id)
	{
		$this->CI->db->select('*');
		$this->CI->db->where('id', $id);
		$result	= $this->CI->db->get('admin');
		$result	= $result->row();

		return $result;
	}		
	
	function check_id($str)
	{
		$this->CI->db->select('id');
		$this->CI->db->from('admin');
		$this->CI->db->where('id', $str);
		$count = $this->CI->db->count_all_results();
		
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	
	function check_email($str, $id=false)
	{
		$this->CI->db->select('email');
		$this->CI->db->from('admin');
		$this->CI->db->where('email', $str);
		if ($id)
		{
			$this->CI->db->where('id !=', $id);
		}
		$count = $this->CI->db->count_all_results();
		
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function delete($id)
	{
		if ($this->check_id($id))
		{
			$admin	= $this->get_admin($id);
			$this->CI->db->where('id', $id);
			$this->CI->db->limit(1);
			$this->CI->db->delete('admin');

			return $admin->firstname.' '.$admin->lastname.' has been removed.';
		}
		else
		{
			return 'The admin could not be found.';
		}
	}
}