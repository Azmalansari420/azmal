<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('index');
	}

	public function about_us()
	{
		$this->load->view('about-us');
	}


	public function agro()
	{
		$this->load->view('agro');
	}

	public function air()
	{
		$this->load->view('air');
	}

	public function automative()
	{
		$this->load->view('automative');
	}

	public function contact_us()
	{
		if(isset($_POST['submit']))
		{

			date_default_timezone_set('Asia/Kolkata');

			$data2['name'] = $this->input->post('name');
			$data2['email'] = $this->input->post('email');
			$data2['mobile'] = $this->input->post('mobile');
			$data2['subject'] = $this->input->post('subject');
			$data2['message'] = $this->input->post('message'); 

			$this->crud->insert('contact',$data2);
			$message = '
		    <h3 align="center">Contact Mail</h3>
		    <table border="1" width="100%" cellpadding="5" cellspacing="5">
		      <tr>
		        <td width="30%"> Name</td>
		        <td width="70%">'.$_POST["name"].'</td>
		      </tr>
		      <tr>
		        <td width="30%">Email Id</td>
		        <td width="70%">'.$_POST["email"].'</td>
		      </tr>
		      <tr>
		        <td width="30%">Subject</td>
		        <td width="70%">'.$_POST["subject"].'</td>
		      </tr>
		      <tr>
		        <td width="30%">Message</td>
		        <td width="70%">'.$_POST["message"].'</td>
		      </tr>
		      <tr>
		        <td width="30%">Phone</td>
		        <td width="70%">'.$_POST["mobile"].'</td>
		      </tr>
		      
		    </table>
		  ';

		  $this->mail->sendmail($message);
		}

					
		$this->load->view('contact-us');
	}


	public function custom()
	{
		$this->load->view('custom');
	}


	public function fashion()
	{
		$this->load->view('fashion');
	}



	public function leadership()
	{
		$this->load->view('leadership');
	}



	public function pharma()
	{
		$this->load->view('pharma');
	}



	public function power()
	{
		$this->load->view('power');
	}


	public function product($id)
	{
		$args=func_get_args();

		$data['EDITDATA'] = $slugdata = $this->crud->selectDataByMultipleWhere("categories",array("slug"=>$id));

		$cat_id = $slugdata[0]->id;
		$data['cat_id'] = $cat_id;
		$msg = "";
		if(isset($_GET['msg']))
		{	
			$msg = $_GET['msg'];
		}
		$data['msg'] = $msg;
		$this->load->view('product',$data);
	}


	public function enquiry()
	{
		 if(isset($_POST['submit']))
		{
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');
			$data['message'] = $this->input->post('message');

			if($this->crud->insert('get_quate',$data))
			{
			    			$message = '
                		    <h3 align="center"> Get Quote</h3>
                		    <table border="1" width="100%" cellpadding="5" cellspacing="5">
                		      <tr>
                		        <td width="30%"> Name</td>
                		        <td width="70%">'.$_POST["name"].'</td>
                		      </tr>
                		      <tr>
                		        <td width="30%">Email Id</td>
                		        <td width="70%">'.$_POST["email"].'</td>
                		      </tr>
                		     
                		      <tr>
                		        <td width="30%">Message</td>
                		        <td width="70%">'.$_POST["message"].'</td>
                		      </tr>
                		      <tr>
                		        <td width="30%">Phone</td>
                		        <td width="70%">'.$_POST["phone"].'</td>
                		      </tr>
                		      
                		    </table>
                		  ';

		  $this->mail->sendmail($message);
				redirect($this->input->post("riderect_url")."?msg=true");
			}
			else
			{
				redirect($this->input->post("riderect_url")."?msg=false");
			}
		}
	}

	public function rail()
	{
		$this->load->view('rail');
	}

	public function road()
	{
		$this->load->view('road');
	}

	public function sea()
	{
		$this->load->view('sea');
	}

	public function steel()
	{
		$this->load->view('steel');
	}

	public function supply()
	{
		$this->load->view('supply');
	}

	public function telecome()
	{
		$this->load->view('telecome');
	}

	public function vessel()
	{
		$this->load->view('vessel');
	}

	public function vision_mission()
	{
		$this->load->view('vision-mission');
	}

	public function warehouse()
	{
		$this->load->view('warehouse');
	}


	public function contact()
	{
		$this->load->view('contact');
	}

	public function send()
	{
		$this->load->view('send');
	}


// for live

// <?php
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'phpmailer/Exception.php';
// require 'phpmailer/PHPMailer.php';
// require 'phpmailer/SMTP.php';

// if(isset($_POST["send"])){
//   $mail = new PHPMailer(true);

//   $mail->isSMTP();
//   $mail->Host = 'smtp.gmail.com';
//   $mail->SMTPAuth = true;
  // $mail->Username = 'azmalansari17@gmail.com'; // from Your gmail
  // $mail->Password = 'jwtnmopkhnlxjffv'; // Your gmail app password
  // $mail->SMTPSecure = 'tls';
  // $mail->Port = 587;

  // $mail->setFrom('azmal.codediffusion@gmail.com'); // Your gmail

  // $mail->addAddress("azmalansari17@gmail.com"); //to

  // $mail->isHTML(true);

  // $mail->Subject = $_POST["subject"];
  // $mail->Body = $_POST["message"];

  // $mail->send();

  // echo
  // "
  // <script>
  // alert('Sent Successfully');
  // document.location.href = 'index';
  // </script>
  // ";
// }
//

	
	

}
