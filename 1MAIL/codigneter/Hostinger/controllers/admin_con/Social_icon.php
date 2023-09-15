<?php

class Social_icon extends CI_Controller
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

	function edit($id)
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			$data['facebook'] = $this->input->post('facebook');
			$data['twitter'] = $this->input->post('twitter');
			// $data['instra'] = $this->input->post('instra');			
			$data['google'] = $this->input->post('google');			
			

			$this->crud->update('social_icon',$args[0],$data);
			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully updated.</div>');

		}

		$data['SOCIAL'] = $this->crud->fetchdatabyid($args[0],'social_icon');
		$this->load->view('admin/social_icon/edit',$data);
	}


}