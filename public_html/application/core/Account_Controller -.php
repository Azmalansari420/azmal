<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Controller extends Front_Controller {
	
	var $account = array();

	public function __construct(){
		parent::__construct();
		
		if(!$this->session->userdata('account')){

			redirect(home_url());
		}

		$this->account = $this->session->userdata('account');
		$this->data['account'] = $this->account;

		$is_logged_in = true;
		$this->data['is_logged_in'] = $is_logged_in;

		$this->data['current_page_slug'] = $this->uri->segment(1);

		$this->load->model('customers/customers_model');
	}

	public function get_customer(){
		$this->customer_data = $this->customers_model->get_customer($this->account['id']);
		$this->data['customer'] = $this->customer_data;

		return $this->customer_data;
	}

	public function page($view = false){
		
		//prepare page view
		if($this->config->item('ci_form')){
			$this->data['form'] = $this->config->item('ci_form');
			$this->config->set_item('ci_form', '');
		}

		$content = $this->load->view($this->page_view($view), $this->data, true);

		$this->data['content'] = $content;
		
		$this->load->view($this->page_header(), $this->data);
		if($view){
			$this->load->view($this->page_view("account/account-wrapper"), $this->data);
		}
		$this->load->view($this->page_footer());
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */