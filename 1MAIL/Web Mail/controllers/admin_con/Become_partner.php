<?php

class Become_partner extends CI_Controller
{
	///------author for login--

	function chech_admin_login()
	{
		$ci = & get_instance();
	    if(empty($ci->session->userdata('id')))
	    {
	      redirect(base_url().'admin');
	    }
	}

	
	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('become_partner');
		$this->load->view('admin/become_partner/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('become_partner',array('id'=>$args[0]));
		// $path = 'media/uploads/become_partner/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('become_partner',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/become_partner/listing');

	}


	function view()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['mobile'] = $this->input->post('mobile');
			$data['subject'] = $this->input->post('subject');
			$data['regarding'] = $this->input->post('regarding');
			$data['message'] = $this->input->post('message');
			
			$this->crud->update('become_partner',$args[0],$data);

		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'become_partner');
		$this->load->view('admin/become_partner/view',$data);
	}




}