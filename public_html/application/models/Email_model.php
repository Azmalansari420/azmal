<?php
class Email_model extends WD_model{	

	function __construct(){
		$this->set_table_name("email_template");
		$this->set_table_index("id");
		
		$this->load->model('customers/customers_model');
	}
	
	public function get_email_by_code($code=false){
		if($code && $code != ''){
			return $this->db->where('code', trim($code))->get($this->table_name())->row();
		}
		return false;
	}
	
	function save($id=false){
		$save['title'] 		= clean_insert($_POST['title']);
		$save['subject'] 	= clean_insert($_POST['subject']);
		$save['content'] 	= clean_insert($_POST['content']);
		// $save['template_for'] 	= clean_insert($_POST['template_for']);
		$save['status'] 	= clean_insert($_POST['status']);

		if($id){//update
			$this->db->where("id", $id)->update($this->table_name(), $save);
			return $id;
		}else{//new insert
			$code = strtolower(clean_unique_code(trim($_POST['code']), '-'));
			$this->load->library('app');
			$code = $this->app->validate_slug($code , 'code' , $this->table_name());
			$save['code'] = $code;
			$save['added_on'] 	= date('Y-m-d H:i:s');
			$this->db->insert($this->table_name(), $save);
			$id = $this->db->insert_id();
			return $id;
		}
	}
	

	function remove_tem($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
	

	public function template_parser($content='', $variables=array()){
		if($content != ''){
			preg_match_all('/{{(.*?)}}/', stripslashes($content), $matched_vars);
			
			if(count($matched_vars[0]) > 0){
				foreach($matched_vars[0] as $matched_var){
					$var = str_replace(array("{{", "}}"), "", $matched_var);
					if(array_key_exists($var, $variables)){
						$content = str_replace($matched_var, $variables[$var], $content);
					}
				}
			}
		}
		return $content;
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("customer_id", $id)->delete($this->table_name());
	}
	
	
	function total(){
		return $this->db->get($this->table_name())->num_rows();
	}
	
	
	function mail_from(){
		return get_setting('form_mail_id');//"info@marketingkarts.com";
	}
	
	function mail_name(){//mail_name
		return get_setting('mail-from-name');
	}
	
	//////////////////////////////////
	function enquiry($enquiry_data=false){
	
		if(!$enquiry_data){
			return false;
		}

		$body = "<html>";
			$body .= "<body>";
				$body .= $enquiry_data."\n\r";
			$body .= "</body>";
		$body .= "</html>";
		
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from($this->mail_from(), $this->mail_name());
		$this->email->to(get_setting('contact-form-email'));
		$this->email->subject("Enquiry Details ".$this->mail_name());
		$this->email->message($body);
		if($this->email->send()){
			return true;
		}else{
			return false;
		}
	}
	
	function new_register_email($customer_id=false, $vefiry_url=false){
	
		if(!$customer_id){
			return false;
			exit;
		}
		
		$this->load->model('customers_model');
		$customer = (array)$this->customers_model->fetch_row_by_id($customer_id);
		
		if(!$customer){
			return false;
			exit;
		}
		
		$message = "<html>";
			$message .= "<head>";
			$message .= "</head>";
			
			$message .= "<body>";
				$message .= "Dear ".ucwords($customer['name'])."\n\r";
				$message .= "<p>Thank you for registring with ".$this->mail_name().".</p>";
				$message .= "<p>Please click the link below or copy and paste the link in your favorite browsers address bar to confirm your registration.</p>";
				$message .= "<p><a href='".$vefiry_url."'>".$vefiry_url."</a></p>";

				$message .= "<p><br /> Thanks,<br /> ".$this->mail_name()."</p>";
			$message .= "</body>";
		$message .= "</html>";
		
		$subject = $this->mail_name().' : New Registration '.ucwords($customer['name']);
		
		return email($this->mail_from(), $this->mail_name(), $customer['email_id'], $subject, $message, true);
	}
	
	
	function customer_reset_password($id=false){
		
		$this->load->model('customers_model');
		$customer_data = $this->customers_model->fetch_row_by_field("id", $id);
		if(!isset($customer_data->id)){
			return false;
		}

		$reset_password 			= $customer_data->reset_password;
		
		$body = "<html>";
			$body .= "<title>Request New Password</title>";
			$body .= "<head></head>";
			$body .= "<body>";
			
				$body .= '<table width="900px" align="center" cellpadding="10" cellspacing="0" style="font-size: 15px; font-family: arial; border: 1px solid #cccccc;">';
					
					$body .= "<tr>";
						$body .= '<td align="left" style="border: none;"><a href="'.base_url().'"><img width="150px;" src="'.base_url('assets/images/logo.png').'"></a></td>';
						$body .= '<td align="right" style="border: none;"><h1 style="font-family: arial; font-size: 28px; margin: 0;"></h1></td>';
					$body .= "</tr>";
					
					$body .= "<tr>";
						$body .= '<td colspan="2" bgcolor="#fafafa" style="padding: 20px 15px; border: none;">';
							$body .= '<strong style="font-size: 18px; ">Hello ';
								$body .= '<span style="color:#D10002">'.ucfirst($customer_data->name).'</span>';
							$body .= '</strong>,';
							
							/*$body .= '<p style="margin: 5px 0 0">We have received a password change request for your ShoutVilla account ';
								$body .= '<b>'.ucfirst($customer_data->first_name.' '.$customer_data->last_name).'</b>';
							$body .= '</p>';*/
							
							$body .= '<p style="margin: 5px 0 0">If this was not requested by you, please ignore this email and your password will not be changed.</p>';
							$body .= '<p style="margin: 5px 0 0">The link below will remain active for 48 hours</p>';
							 
							$body .= '<p style="margin: 5px 0 0;">';
								$body .= '<a style="color:#006699;" href="'.base_url().'account/new_password?c='.$reset_password.'&d='.$id.'">Reset Password</a>';
							$body .= '</p>';
						$body .= '</td>';
					$body .= "</tr>";
					
					$body .= "<tr>";
						$body .= '<td colspan="2" style="border: none; padding: 20px 15px;" bgcolor="#fafafa">';
							$body .= '<strong style="font-size: 18px;">THANK YOU,</strong>';
							$body .= '<p><strong style="font-size: 18px;">'.$this->mail_name().' Team</strong></p>';
						$body .= '</td>'; 
					$body .= "</tr>";
					
				$body .= "</table>";
				
			$body .= "</body>";
		$body .= "</html>";
		
		$subject = $this->mail_name().' : Change your password';
		$sent = email($this->mail_from(), $this->mail_name(), $customer_data->email_id, $subject, $body, true);
	
		if($sent){
			return true;
		}else{
			return false;
		}
	
	}
	
	
	function enquirie_email($customer_id=false, $enquirie_id=false){
	
		if(!$customer_id){
			return false;
			exit;
		}
		
		$this->load->model('customers_model');
		$customer = (array)$this->customers_model->fetch_row_by_id($customer_id);
		if(!$customer){
			return false;
			exit;
		}
		
		
		if(!$enquirie_id){
			return false;
			exit;
		}
		
		$this->load->model('enquiry_model');
		$enquirie = (array)$this->enquiry_model->fetch_row_by_id($enquirie_id);
		
		if(!$enquirie){
			return false;
			exit;
		}
		
		
		if($customer['enquiry_email_id']!=''){
			$customer_email_id = $customer['enquiry_email_id'];
		}else{
			$customer_email_id = $customer['email_id'];	
		}
		
		
		$message = "<html>";
			$message .= "<head>";
			$message .= "</head>";
			
			$message .= "<body>";
				$message .= "<p>Enquiry Type : <b>".ucwords($enquirie['enquiry_type'])."</b> </p>";
				$message .= "<p>Enquiry For : <b>".ucwords($enquirie['enquiry_for'])."</b> </p>";
				$message .= "<p>Name : <b>".ucwords($enquirie['name'])."</b> </p>";
				
				if($enquirie['paid']=='1'){
					$message .= "<p>Mobile No. : <b>".$enquirie['mobile_no']."</b> </p>";
					$message .= "<p>Email ID : <b>".$enquirie['email_id']."</b> </p>";
					$message .= "<p>Message : <b>".$enquirie['message']."</b> </p>";
				}else{
					$message .= "<p>Mobile No. : <b>91XXXXXXXXXX</b> </p>";
					$message .= "<p>Email ID : <b>XXXXXXXXX@gmail.com</b> </p>";
				}

				$message .= "<p><br /> Thanks,<br /> </p>".$this->mail_name()."";
			$message .= "</body>";
		$message .= "</html>";
		
		$subject = $this->mail_name().' : New Enquirie No. '.$enquirie['id'];
		
		return email($this->mail_from(), $this->mail_name(), $customer_email_id, $subject, $message, true);
	}
	
	
	function paid_enquirie_email($paid_q_id=false){
	
		if(!$paid_q_id){
			return false;
			exit;
		}
		
		$this->load->model('paid_queries_model');
		$paid_q = (array)$this->paid_queries_model->fetch_row_by_id($paid_q_id);
		if(!$paid_q){
			return false;
			exit;
		}
		
		
		$message = "<html>";
			$message .= "<head>";
			$message .= "</head>";
			
			$message .= "<body>";
				$message .= "<p>Enquiry For : <b>".ucwords($paid_q['category_name'])."</b> </p>";
				$message .= "<p>User Name : <b>".ucwords($paid_q['name'])."</b> </p>";
				
				$message .= "<p>Mobile No. : <b>".$paid_q['mobile_no']."</b> </p>";
				$message .= "<p>Email ID : <b>".$paid_q['email_id']."</b> </p>";
				$message .= "<p>Message : <b>".$paid_q['message']."</b> </p>";

				$message .= "<p><br /> Thanks,<br /> </p>".$this->mail_name()."";
			$message .= "</body>";
		$message .= "</html>";
		
		$subject = $this->mail_name().' : New Enquirie '.$paid_q['category_name'].' in '.$paid_q['city'];
		return email($this->mail_from(), $this->mail_name(), $paid_q['client_email_ids'], $subject, $message, true);
	}
	
	
}
?>