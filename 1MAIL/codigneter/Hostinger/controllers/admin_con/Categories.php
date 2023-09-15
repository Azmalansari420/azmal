<?php

class Categories extends CI_Controller
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

	//------------insert

	function add()
	{
		$this->chech_admin_login();
		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			
			$data['name'] = $this->input->post('name');
			$data['status'] = $this->input->post('status');			
			// $data['status'] = $this->input->post('status');	
			if(empty($data['slug']))
			{
				$slug = slug($data['name']);
			}
			else
			{
				$slug = slug($data['slug']);
			}
			$data['slug'] = $slug;		
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert('categories',$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully saved.</div>');
			
			redirect('admin_con/categories/listing');	
		}

		$this->load->view('admin/categories/add');
	}


	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('categories');
		$this->load->view('admin/categories/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('categories',array('id'=>$args[0]));
		$path = 'media/uploads/categories/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('categories',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/categories/listing');

	}


	//----------------edit

	function edit()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			$data['name'] = $this->input->post('name');
			$data['status'] = $this->input->post('status');
			if(empty($data['slug']))
			{
				$slug = slug($data['name']);
			}
			else
			{
				$slug = slug($data['slug']);
			}
			$data['slug'] = $slug;
			
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('categories',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		    redirect('admin_con/categories/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'categories	');
		$this->load->view('admin/categories/edit',$data);
	}









}