<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Locations extends Front_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
	}
	
	public function index(){

		$this->data['meta_title'] 	= "";
		$this->data['page'] 		= "locations";
		$slug 						= 'location';
		$page 						= $this->page_model->fetch_row_by_field('slug', $slug);

		if($page){
			
			$this->data['id'] = $page->id;
			$this->data['page'] = $slug;
			$this->data['title'] = $page->title;
			$this->data['page_url'] = home_url();

			$this->data['meta_title'] = clean_display($page->meta_title);
			$this->data['meta_description'] = clean_display($page->meta_description);
			$this->data['meta_keywords'] = clean_display($page->meta_keywords);
			
			$this->data['data'] = $page;
		
			$this->breadcrumb(array('locations'=>$page->title));
		}
		
		
		$this->page('locations');
	}

}