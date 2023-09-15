<?php

class Edit_profile extends CI_Controller
{
	///------author for login--

	function chech_admin_login()
	{
		$ci = & get_instance();
		$USERID      = $ci->session->userdata('USERID');	
		$USERNAME      = $ci->session->userdata('USERNAME');	
		$logged_in      = $ci->session->userdata('logged_in');	
		if($USERID=="" && $USERNAME=="")
		{
			redirect('index.php/admin/index');
		}
	}


	//---edit function in left menu

	function edit()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			$data['username'] = $this->input->post('username');			
			$data['password'] = $this->input->post('password');				

			$this->crud->update('tbl_admin',$this->session->userdata("id"),$data);
			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully updated.</div>');

			redirect('admin/dashboard');	

		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($this->session->userdata("id"),'tbl_admin');
		$this->load->view('admin/edit_profile',$data);
	}


}