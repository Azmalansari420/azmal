<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_404 extends Front_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index($slug=false){
		
		$this->data['title'] = '404 Page Not Found';

		$this->output->set_status_header('404');
		$this->Page("cms_page/no_route");
	}
}