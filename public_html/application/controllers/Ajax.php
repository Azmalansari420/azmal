<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ajax extends Front_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cms/page_model');
		$this->load->model('profiles_model');		
	}///public function products_listing(){
	
	
	public function top_listing(){
		if(isset($_POST['state']) &&  $_POST['state']!=''){
			$this->db->where('state', $_POST['state']);
		}
		
		if(isset($_POST['city']) &&  $_POST['city']!=''){
			$this->db->where('city', $_POST['city']);
		}
		
		if(isset($_POST['locality']) &&  $_POST['locality']!=''){
			$this->db->where('locality', $_POST['locality']);
		}
		
		if(isset($_POST['gender']) &&  $_POST['gender']!=''){
			$this->db->where('gender', $_POST['gender']);
		}
		
		if(isset($_POST['type']) &&  $_POST['type']!=''){
			$this->db->where('type', $_POST['type']);
		}
		
		if(isset($_POST['age']) &&  $_POST['age']!=''){
			$age_e		= explode('-', $_POST['age']);
			$this->db->where('age >=',$age_e[0])->where('age >=',$age_e[1]);
		}
		
		if(isset($_POST['short']) &&  $_POST['short']!=''){
			$short		= $_POST['short'];
			if($short!='views'){
				$this->db->order_by("id", $short);	
			}else{
				$this->db->order_by("viewed", 'desc');
			}
		}else{
			//$this->db->order_by("sort_order", 'asc');
			$this->db->order_by("id", 'random');
		}
		
		$this->data['top_listing'] 		= $this->db->where('f_top', 1)->where('status', 1)->limit(6)->get($this->profiles_model->table_name())->result();
		
		$this->load->view(theme_dir().'top_listing', $this->data);
		//print_r($this->data['listing']);
	}
	
	public function right_listing(){
		if(isset($_POST['state']) &&  $_POST['state']!=''){
			$this->db->where('state', $_POST['state']);
		}
		
		if(isset($_POST['city']) &&  $_POST['city']!=''){
			$this->db->where('city', $_POST['city']);
		}
		
		if(isset($_POST['locality']) &&  $_POST['locality']!=''){
			$this->db->where('locality', $_POST['locality']);
		}
		
		if(isset($_POST['gender']) &&  $_POST['gender']!=''){
			$this->db->where('gender', $_POST['gender']);
		}
		
		if(isset($_POST['type']) &&  $_POST['type']!=''){
			$this->db->where('type', $_POST['type']);
		}
		
		if(isset($_POST['age']) &&  $_POST['age']!=''){
			$age_e		= explode('-', $_POST['age']);
			$this->db->where('age >=',$age_e[0])->where('age >=',$age_e[1]);
		}
		
		if(isset($_POST['short']) &&  $_POST['short']!=''){
			$short		= $_POST['short'];
			if($short!='views'){
				$this->db->order_by("id", $short);	
			}else{
				$this->db->order_by("viewed", 'desc');
			}
		}else{
			//$this->db->order_by("sort_order", 'asc');
			$this->db->order_by("id", 'random');
		}
		
		$this->data['right_listing'] 		= $this->db->where('f_right', 1)->where('status', 1)->limit(5)->get($this->profiles_model->table_name())->result();
		
		$this->load->view(theme_dir().'right_listing', $this->data);
		//print_r($this->data['listing']);
	}
	
	
	public function left_menu_listing(){
		$this->load->view(theme_dir().'left_menu_listing', $this->data);
		//print_r($this->data['listing']);
	}
	
	
	public function get_listing(){
		{//Paging
			$per_page 	= 5;
			$page 		= 1;
			if(isset($_POST['page']) && $_POST['page']!=''){
				$page 	= $_POST['page'];
			}
			
			$filters = array();
			if(is_array($_POST) && !empty($_POST)){
				foreach($_POST as $key => $val){
					$filters[$key] = $val;
				}
			}
			
			$this->load->library('pagination');
			
			$this->data['total_rows'] 	= $this->profiles_model->listing($filters, false, false, true);
			
			$this->data['page_start'] 	= $page;

			$listing = $this->profiles_model->listing($filters, $per_page, $page);
			$this->data['listing'] 		= $listing;
		}
		
		$this->load->view(theme_dir().'listing' ,$this->data);
		
		//print_r($this->data['listing']);
	}
	
	
	public function get_cities(){
		$city				= $_POST['city'];
		$state				= $_POST['state'];
		$type				= 'call-girls';
		if(isset($_POST['type']) && $_POST['type']!=''){
			$type			= $_POST['type'];
		}
		
		$html = '<option value="">Select City</option>';
		if($state!=''){
			foreach(get_cities($state) as $k => $c){
				if($c->name == $city){
					$html .= '<option selected="selected" value="'.base_url($type.'/'.$c->slug).'">'.$c->name.'</option>';
				}else{
					$html .= '<option value="'.base_url($type.'/'.$c->slug).'">'.$c->name.'</option>';
				}
			}
		}
		
		echo $html;
	}
	
	public function get_locality(){
		$city				= $_POST['city'];
		$locality			= $_POST['locality'];
		$type				= 'call-girls';
		if(isset($_POST['type']) && $_POST['type']!=''){
			$type			= $_POST['type'];
		}
		
		$html = '<option value="">Select Locality</option>';
		
		if($city!=''){
			foreach(get_locality($city) as $k => $c){
				if($c->name == $locality){
					$html .= '<option selected="selected" value="'.base_url($type.'/'.$c->slug).'">'.$c->name.'</option>';
				}else{
					$html .= '<option value="'.base_url($type.'/'.$c->slug).'">'.$c->name.'</option>';
				}
			}
		}
		
		echo $html;
	}
	
	
	public function get_cities2(){
		$city				= $_POST['city'];
		$state				= $_POST['state'];
		
		$html = '<option value="">Select City</option>';
		if($state!=''){
			foreach(get_cities($state) as $k => $c){
				if($c->slug == $city){
					$html .= '<option selected="selected" value="'.$c->slug.'">'.$c->name.'</option>';
				}else{
					$html .= '<option value="'.$c->name.'">'.$c->name.'</option>';
				}
			}
		}
		
		echo $html;
	}
	
	public function get_locality2(){
		$city				= $_POST['city'];
		$locality			= $_POST['locality'];
		
		$html = '<option value="">Select Locality</option>';
		
		if($city!=''){
			foreach(get_locality($city) as $k => $c){
				if($c->slug == $locality){
					$html .= '<option selected="selected" value="'.$c->slug.'">'.$c->name.'</option>';
				}else{
					$html .= '<option value="'.$c->name.'">'.$c->name.'</option>';
				}
			}
		}
		
		echo $html;
	}
	
}