<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Index extends Front_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
	}
	
	public function index(){

		$slug = 'home';

		$page = $this->page_model->fetch_row_by_field('slug', $slug);
		if($page){
			$this->data['id'] = $page->id;
			$this->data['page'] = $slug;
			$this->data['title'] = $page->title;
			$this->data['page_url'] = home_url();

			$this->data['meta_title'] = clean_display($page->meta_title);
			$this->data['meta_description'] = clean_display($page->meta_description);
			$this->data['meta_keywords'] = clean_display($page->meta_keywords);
			
			$this->data['data'] = $page;
		
			{//Includes - Data Source
				$this->process_includes($page);
			}
			
			$this->breadcrumb(array($slug=>$page->title));
			
			$template = "homepage";
			/*if($page->template != ''){
				if(templates($page->template)){
					$template = $page->template;
				}
			}*/

			$this->page($template);
			//$this->theme->add_js(array("validate.js"));

			//$this->page('cms_page/'.$template);
		}else{
			echo "<h1>Homepage not declared in admin</h1>";
		}
	}

	public function pages($slug=false){
		if(!$slug){
			redirect(base_url());
		}
		
		if($slug=='home'){
			redirect(base_url());
		}
		
		if($slug=='location'){
			redirect(base_url('locations'));
		}

		$page = $this->page_model->fetch_row_by_field('slug', $slug);
		if($page){

			$this->breadcrumb(array($slug=>$page->title));
			
			$this->data['id'] = $page->id;
			$this->data['page'] = $slug;
			$this->data['title'] = $page->title;
			$this->data['page_title'] = $page->title;
			
			$this->data['meta_title'] = $page->meta_title;
			$this->data['meta_description'] = $page->meta_description;
			$this->data['meta_keywords'] = $page->meta_keywords;
			
			$this->data['data'] = $page;
			
			{//Includes - Data Source
				$this->process_includes($page);
			}

			$template = "page";
			if($page->template != ''){
				if(templates($page->template)){
					$template = $page->template;
				}
			}
			
			$this->page('cms_page/'.$template);
		}else{
			redirect(base_url());
		}
	}
}