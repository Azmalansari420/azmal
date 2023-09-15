<?php

class Verti extends CI_Controller
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

			if($_FILES['image']['name']!='')
			{
				$bimage = $_FILES['image']['name'];
				$path = 'media/uploads/slider/'.$bimage;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$bimage = "";
			}

			$data['image'] = $bimage;
			$data['title'] = $this->input->post('title');
			$data['sub_title'] = $this->input->post('sub_title');

			$data['content'] = $this->input->post('content');
			$data['status'] = $this->input->post('status');
			$data['uid'] = rand(10000, 99999);
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert('slider',$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully saved.</div>');
			
			redirect('admin_con/verti/listing');	
		}

		$this->load->view('admin/verti/add');
	}


	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('slider');
		$this->load->view('admin/verti/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('slider',array('id'=>$args[0]));
		$path = 'media/uploads/slider/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('slider',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/verti/listing');

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
				$path = 'media/uploads/slider/'.$image;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$image = $_POST['oldimage'];
			}

			$data['image'] = $image;
			$data['title'] = $this->input->post('title');
			$data['sub_title'] = $this->input->post('sub_title');
			$data['status'] = $this->input->post('status');
			$data['content'] = $this->input->post('content');
			
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('slider',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		    redirect('admin_con/verti/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'slider');
		$this->load->view('admin/verti/edit',$data);
	}





	//----------------view

	function view()
	{
		$this->chech_admin_login();
		$args=func_get_args();

		if(isset($_POST['submit']))
		{
			date_default_timezone_set('Asia/Kolkata');

			if($_FILES['image']['name']!='')
			{
				$image = $_FILES['image']['name'];
				$path = 'media/uploads/slider/'.$image;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$image = $_POST['oldimage'];
			}

			$data['image'] = $image;
			$data['title'] = $this->input->post('title');
			$data['sub_title'] = $this->input->post('sub_title');
			$data['status'] = $this->input->post('status');
			$data['content'] = $this->input->post('content');
			
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('slider',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		    // redirect('admin_con/slider/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'slider');
		$this->load->view('admin/verti/view',$data);
	}





}