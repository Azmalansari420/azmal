<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Front_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cms/Page_model');
		$this->load->model("profiles_model");
		$this->load->model("location/cities_model");
		$this->load->model("location/locality_model");
	}

	public function index($slug=false){
		$city						= '';
		$locality					= '';
		$city_slug					= '';
		$locality_slug				= '';
		$type_slug					= 'call-girls';
		//$this->data['this'] 		= $this;
		//echo $slug;
		if($slug=='index'){
			$page = $this->Page_model->fetch_row_by_field('slug', 'call_girls');
		}else{
			$page 						= $this->cities_model->fetch_row_by_field("slug", $slug);
			if(!isset($page->id)){
				$page 					= $this->locality_model->fetch_row_by_field("slug", $slug);
				if(isset($page->id)){
					$locality			= $page->name;
					$locality_slug		= $page->slug;
					$city				= $page->city;
					$city_page 			= $this->cities_model->fetch_row_by_field("name", $city);
					if(isset($city_page->id)){
						$city_slug		= $city_page->slug;
					}
				}
			}else{
				$city					= $page->name;
				$city_slug				= $page->slug;
			}
		}
		
		//print_r($page);
		
		if(isset($page->id)){
			if($slug=='index'){
				$this->data['breadcrumbs'] = array('call-girls/'=> 'Call Girls');
				
				$this->data['title'] 		= $page->title;
				$this->data['state'] 		= '';
			}else{
				$this->data['breadcrumbs'] = array('call-girls/'=> 'Call Girls', 'call-girls/'.$city_slug=> $city);
				if($locality_slug!=''){
					$this->data['breadcrumbs'] = array('call-girls/'=> 'Call Girls', 'call-girls/'.$city_slug=> $city, 'call-girls/'.$locality_slug=> $locality);
				}
				
				$this->data['title'] 		= $page->name;
				$this->data['state'] 		= $page->state;
			}
				
			$this->data['id'] 				= $page->id;
			$this->data['page'] 			= $slug;
			
			$this->data['type'] 			= $type_slug;
			$this->data['type_slug'] 		= $type_slug;
			$this->data['city'] 			= $city;
			$this->data['city_slug'] 		= $city_slug;
			$this->data['locality'] 		= $locality;
			$this->data['locality_slug'] 	= $locality_slug;
			
			$this->data['meta_title'] 		= $page->meta_title;
			$this->data['meta_description'] = $page->meta_description;
			$this->data['meta_keywords'] 	= $page->meta_keywords;
			$this->data['data'] = $page;
		}else{
			redirect(base_url());
		}
		
		
		$this->page('listing-page');
	}

	
	public function _remap($slug=''){

		if($slug == ''){

			redirect(home_url());
		}elseif($slug == 'load'){

			$this->load();
		}else{

        	$this->index($slug);
    	}
	}
}