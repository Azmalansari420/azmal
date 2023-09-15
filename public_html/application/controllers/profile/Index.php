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
		
		if($slug==''){
			redirect(base_url());
		}
		
		$profile = $this->profiles_model->fetch_row_by_field("slug", $slug);
		if(!isset($profile->id)){
			redirect(base_url());
		}
		
		$save_v['viewed'] = $profile->viewed+1;	
		$this->db->where("id", $profile->id)->update($this->profiles_model->table_name(), $save_v);
		
		//$this->data['breadcrumbs'] = array('category/'=> 'Collections');
		
		$city_page 			= $this->cities_model->fetch_row_by_field("name", $profile->city);
		$locality_page		= $this->locality_model->fetch_row_by_field("name", $profile->locality);
		
		$this->data['breadcrumbs'] = array($profile->type => ucwords(str_replace('_', ' ', $profile->type)), $profile->type.'/'.$city_page->slug=> $profile->city);	
		if(isset($city_page->id) && isset($locality_page->id)){
			//$this->data['breadcrumbs'] = array($profile->type => ucwords(str_replace('_', ' ', $profile->type)), $profile->type.'/'.$city_page->slug=> $profile->city, $profile->type.'/'.$locality_page->slug=> $profile->locality);
		}else{
			$this->data['breadcrumbs'] = array($profile->type => ucwords(str_replace('_', ' ', $profile->type)), $profile->type.'/'.$city_page->slug=> $profile->city);			
		}
		
		$this->data['id'] 				= $profile->id;
		$this->data['page'] 			= $slug;
		$this->data['title'] 			= $profile->name;
		
		$this->data['data'] 			= $profile;
		$this->data['meta_title'] 		= $profile->meta_title;
		$this->data['meta_keywords'] 	= $profile->meta_keywords;
		$this->data['meta_description'] = $profile->meta_description;
		
		if($this->data['meta_title']==''){
			$this->data['meta_title'] = $profile->name;
		}
		
		$meta_keywords = $profile->name.' '.strip_tags($profile->about_us);
		$meta_keywords = substr($meta_keywords, 0, 250);
		
		
		if($this->data['meta_keywords']==''){
			$this->data['meta_keywords'] = $meta_keywords;
		}
		
		if($this->data['meta_description']==''){
			$this->data['meta_description'] = $meta_keywords;
		}
		
		$this->data['gallery'] 			= $this->profiles_model->gallery_images($profile->id);
		
		$this->data['listing'] 			= $this->db->order_by('id', 'random')->where('city', $profile->city)->limit(10)->get($this->profiles_model->table_name())->result();
		
		$this->theme->add_css(array('slick.css', 'slick-theme.css'));
		$this->theme->add_js(array('slick.min.js'));
		
		$this->page('profile-view');
	
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