<?php

class Product extends CI_Controller
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
				$img = slug2($bimage);
				$bimage = slug2($bimage);
				$path = 'media/uploads/product/'.$img;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				$bimage = "";
			}

			$data['image'] = $bimage;
			$data['heading'] = $this->input->post('heading');
			$data['cat_id'] = implode(",",$this->input->post('categ'));
			$data['content'] = $this->input->post('content');
			if(empty($data['slug']))
			{
				$slug = slug($data['heading']);
			}
			else
			{
				$slug = slug($data['slug']);
			}
			$data['slug'] = $slug;
			$data['status'] = $this->input->post('status');
			$data['addeddate'] = date('y-m-d h:i:sA');

			$this->crud->insert('product',$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully saved.</div>');
			
			redirect('admin_con/product/listing');	
		}

		$this->load->view('admin/product/add');
	}


	//----listing list dekhney ke lia 

	function listing()
	{
		$this->chech_admin_login();
		$this->db->order_by("id desc");
		$data['ALLDATA'] = $this->crud->get_data('product');
		$this->load->view('admin/product/list',$data);
	}


	//--delete ke lia

	function delete()
	{
		$args=func_get_args();
		$data=$this->crud->selectDataByMultipleWhere('product',array('id'=>$args[0]));
		$path = 'media/uploads/product/'.$data[0]->image;
		@unlink($path);
		$this->crud->delete('product',$args[0]);
		$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		redirect('admin_con/product/listing');

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
				$img = slug2($image);
				$image = slug2($image);
			    $path = 'media/uploads/product/'.$img;
				move_uploaded_file($_FILES['image']['tmp_name'],$path);
			}

			else
			{
				 $image = slug2($_POST['oldimage']);
			}
            // die;
			$data['image'] = $image;
			$data['cat_id'] = implode(",",$this->input->post('categ'));
			$data['heading'] = $this->input->post('heading');
			$data['content'] = $this->input->post('content');
			if(empty($data['slug']))
			{
				$slug = slug($data['heading']);
			}
			else
			{
				$slug = slug($data['slug']);
			}
			$data['slug'] = $slug;
			$data['status'] = $this->input->post('status');
			
			$data['modifieddate'] = date('y-m-d h:i:sA');

			$this->crud->update('product',$args[0],$data);

			$this->session->set_flashdata('message','<div class="alert alert-success">Record has been successfully deleted.</div>');
		    redirect('admin_con/product/listing');
		}

		$data['EDITDATA'] = $this->crud->fetchdatabyid($args[0],'product');
		$this->load->view('admin/product/edit',$data);
	}





	//----------------view





}