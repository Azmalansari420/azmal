<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_Controller extends WD_Front_Controller {
	
	var $account = array();

	public function __construct(){
		parent::__construct();
		
		$this->load->helper('front_helper');

		//Theme Setup
		{
			$this->load->library('theme');
			$_theme = $this->theme;

			$this->data['_theme'] = $_theme;

			$this->site_title = $this->theme->get_site_title();
			//Default social card
			$this->data['social_card_image'] = home_url($this->theme->get_social_card_image());
		}	
		
		$this->_per_page = 16;

		$this->data['site_title'] = $this->site_title;

		//validate account
		$is_logged_in = false;
		$this->account = false;
		$this->data['account'] = false;

		if($this->session->userdata('account')){
			$is_logged_in = true;
			$this->account = $this->session->userdata('account');
			$this->data['account'] = $this->account;
		}

		$this->data['is_logged_in'] = $is_logged_in;

		$this->data['current_page_slug'] = $this->uri->segment(1);

		$this->load->helper('cookie');

		$recent_search_cookies = $this->input->cookie('search_keywbords');

		$search_keywords = [];

		if($recent_search_cookies && $recent_search_cookies != ''){

			$search_keywords = explode('|', $recent_search_cookies);
		}

		$this->data['search_keywords'] = $search_keywords;

		//stores
		// $this->load->model('stores/stores_model');
		// $this->data['wd_stores_list'] = $this->stores_model->get_stores_list();

		// $this->data['selected_store'] = false;
		// if($is_logged_in){
		// 	$this->data['selected_store'] = $this->account['store_id'];
		// 	$this->session->set_userdata('selected_store', $this->account['store_id']);
		// }else{
		// 	if($this->session->userdata('selected_store')){
		// 		$this->data['selected_store'] = $this->session->userdata('selected_store');
		// 	}
		// }	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */