<?php

class Contact extends CI_Controller
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
		$data['ALLDATA'] = $this->crud->get_data('contact');
		$this->load->view('admin/contact/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('contact',array('id'=>$args[0]));
		// $path = 'media/uploads/contact/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('contact',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/contact/listing');

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
			$data['message'] = $this->input->post('message');
			$data['father_name'] = $this->input->post('father_name');
			$data['age'] = $this->input->post('age');
			$data['qualification'] = $this->input->post('qualification');
			$data['address'] = $this->input->post('address');
			$data['subject'] = $this->input->post('subject');
			
			$this->crud->update('contact',$args[0],$data);

		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'contact');
		$this->load->view('admin/contact/view',$data);
	}




}