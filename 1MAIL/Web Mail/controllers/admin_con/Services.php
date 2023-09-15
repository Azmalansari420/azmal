<?php

class Services extends CI_Controller
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
				$path = 'media/uploads/services/'.$bimage;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$bimage = "";
			}

			$data['image'] = $bimage;
			
			$data['name'] = $this->input->post('name');			
			$data['slug'] = $this->input->post('slug');
			if(empty($data['slug']))
			{
				$slug = slug($data['name']);
			}			
			else{
				$slug = slug($data['slug']);
			}	
			$data['slug'] = $slug;	
			$data['status'] = $this->input->post('status');			
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert('services',$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully saved.</div>');
			
			redirect('admin_con/services/listing');	
		}

		$this->load->view('admin/services/add');
	}


	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('services');
		$this->load->view('admin/services/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('services',array('id'=>$args[0]));
		$path = 'media/uploads/services/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('services',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/services/listing');

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
				$path = 'media/uploads/services/'.$image;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$image = $_POST['oldimage'];
			}

			$data['image'] = $image;
			$data['name'] = $this->input->post('name');						
			$data['slug'] = $this->input->post('slug');
			if(empty($data['slug']))
			{
				$slug = slug($data['name']);
			}			
			else{
				$slug = slug($data['slug']);
			}	
			$data['slug'] = $slug;		
			$data['status'] = $this->input->post('status');						
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('services',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully Updated.</div>');
		    redirect('admin_con/services/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'services');
		$this->load->view('admin/services/edit',$data);
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
	// 			$path = 'media/uploads/services/'.$image;
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

	// 		$this->crud->update('services',$args[0],$data);

	// 		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
	// 	    // redirect('admin_con/services/listing');
	// 	}

	// 	$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'services');
	// 	$this->load->view('admin/services/view',$data);
	// }



	public function statusupdate()
	{	
		//echo "string";

		$data['status'] = $_GET['l_status'];

		$this->crud->update('services',$_GET['ld'],$data);

		$this->listing();

	}



}