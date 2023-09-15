<?php 

class Contact extends CI_Controller
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

	//--------listing

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		
		$data['ALLDATA'] = $this->crud->get_data('contact');

		$this->load->view('admin/contact/list',$data);
	}

	//delete


	function delete()
	{
		$args=func_get_args();
		$this->chech_admin_login();

		$data = $this->crud->selectDataByMultipleWhere('contact',array('id'=>$args[0]));

		$this->crud->delete('contact',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/contact/listing');
	}
}