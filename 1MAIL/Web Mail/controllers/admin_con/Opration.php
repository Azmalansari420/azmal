<?php

class Opration extends CI_Controller
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

	//insert

	function add()
	{
		$this->chech_admin_login();
		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			if($_FILES['image']['name']!='')
			{
				$bimage = $_FILES['image']['name'];
				$path = 'media/uploads/opration/'.$bimage;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$bimage = "";
			}

			$data['image'] = $bimage;
			$data['name'] = $this->input->post('name');
			$data['position'] = $this->input->post('position');
			$data['content'] = $this->input->post('content');
			$data['status'] = $this->input->post('status');
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert('opration',$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully saved.</div>');
			
			redirect('admin_con/opration/listing');	
		}

		$this->load->view('admin/opration/add');
	}


	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('opration');
		$this->load->view('admin/opration/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('opration',array('id'=>$args[0]));
		$path = 'media/uploads/opration/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('opration',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/opration/listing');

	}


	//----------------edit

	function edit()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			if($_FILES['image']['name']!='')
			{
				$image = $_FILES['image']['name'];
				$path = 'media/uploads/opration/'.$image;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$image = $_POST['oldimage'];
			}

			$data['image'] = $image;
			$data['name'] = $this->input->post('name');
			$data['position'] = $this->input->post('position');
			$data['content'] = $this->input->post('content');
			$data['status'] = $this->input->post('status');
			
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('opration',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully Updated.</div>');
		    redirect('admin_con/opration/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'opration');
		$this->load->view('admin/opration/edit',$data);
	}


	public function statusupdate()
	{	
		//echo "string";

		$data['status'] = $_GET['l_status'];

		$this->crud->update('opration',$_GET['ld'],$data);

		$this->listing();

	}




	//----------------view

	// function view()
	// {
	// 	$this->chech_admin_login();
	// 	$args=func_get_args();

	// 	if(isset($_POST['submit']))
	// 	{
	// 		date_default_timezone_set('Asia/Kolkata');

	// 		if($_FILES['image']['name']!='')
	// 		{
	// 			$image = $_FILES['image']['name'];
	// 			$path = 'media/uploads/opration/'.$image;
	// 			move_uploaded_file($_FILES['image']['tmp_name'],$path);
	// 		}

	// 		else
	// 		{
	// 			$image = $_POST['oldimage'];
	// 		}

	// 		$data['image'] = $image;
	// 		$data['title'] = $this->input->post('title');
	// 		$data['sub_title'] = $this->input->post('sub_title');
	// 		$data['status'] = $this->input->post('status');
	// 		$data['content'] = $this->input->post('content');
			
	// 		$data['modifieddate'] = date('y-m-d h:i:sA');

	// 		$this->crud->update('opration',$args[0],$data);

	// 		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
	// 	    // redirect('admin_con/opration/listing');
	// 	}

	// 	$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'opration');
	// 	$this->load->view('admin/opration/view',$data);
	// }





}