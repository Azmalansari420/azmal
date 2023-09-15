<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = "Profiles";
		$this->breadcrumb(array("profile_list"=>"Profiles"));
		$this->load->model("profiles_model");
		$this->load->model("location/cities_model");
		$this->load->model("location/locality_model");
	}
	
	public function status_options(){
		return $status_options = array(1=>"Approved", 2=>"Unapproved", 0=>"Disable");
	}
	
	public function type_options(){
		return array('call_girls'=>"Call Girls", 'escorts'=>"Escorts", 'male_escorts'=>"Male Escorts");
		//return array('Independent'=>"Independent", 'Agency'=>"Agency");
	}
	
	public function gender_options(){
		return array('Girl'=>"Girl", 'Boy'=>"Boy", 'Transgender'=>"Transgender");
	}
	
	public function fetured_options(){
		return array(0=>"No", 1=>"Yes");
	}
	
	public function age_options(){
		return array('20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35');
	}
	
	public function bust_waist_hip_options(){
		return array('32-29-32', '30-28-36', '32-29-34', '32-29-38', '30-26-34', '32-28-34', '34-30-36', '36-30-40', '32-29-38', '34-28-36', '32-29-39', '34-28-37', '32-29-40');
	}
	
	public function eye_color_options(){
		return array('Brown', 'Black', 'Brown', 'Black');
	}
	
	public function height_options(){
		return array("5.0", "5.1", "5.2", "5.3", "5.4", "5.5", "5.6", "5.7", "5.8", "5.9", "6.0");
	}
	
	public function weight_options(){
		return array('40', '42', '44', '45', '46', '48', '50', '52', '54', '55', '56', '58', '60');
	}
	
	public function index(){

		$query = $this->db->select("*")->order_by('id', 'desc')->from($this->profiles_model->table_name());//IMPORTANT: without get() METHOD
		
		$this->load->library("Datatable");
		$this->datatable->setControllerKey('page_index');
		$this->datatable->setTitle("Profiles");
		
		$this->datatable->setColumns(array("name"=>"name", "column"=>"Name"));
		//$this->datatable->setColumns(array("name"=>"email_id", "column"=>"Email ID"));
		
		$this->datatable->setColumns(array("name"=>"mobile_no", "column"=>"Mobile No."));
		$this->datatable->setColumns(array("name"=>"whatapp_no", "column"=>"Whatapp No."));
		
		//$this->datatable->setColumns(array("name"=>"type", "column"=>"Type"));
		//$this->datatable->setColumns(array("name"=>"gender", "column"=>"Gender"));
		
		//$this->datatable->setColumns(array("name"=>"age", "column"=>"Age"));
		//$this->datatable->setColumns(array("name"=>"state", "column"=>"State"));
		$this->datatable->setColumns(array("name"=>"city", "column"=>"City"));
		//$this->datatable->setColumns(array("name"=>"locality", "column"=>"Locality"));
		//$this->datatable->setColumns(array("name"=>"added_on", "column"=>"Registered On", 'calendar'=>true));
		
		$this->datatable->setColumns(array("name"=>"image", "column"=>"Photo", 'callback'=>'profiles_model/image_view', 'hide_search'=>true));	
		
		//$this->datatable->setColumns(array("name"=>"sort_order", "column"=>"Sort Order"));
		$this->datatable->setColumns(array("name"=>"f_top", "column"=>"Top Fetured", 'values'=>$this->fetured_options(), 'search'=>$this->fetured_options())); //
		$this->datatable->setColumns(array("name"=>"f_right", "column"=>"Right Fetured", 'values'=>$this->fetured_options(), 'search'=>$this->fetured_options())); //
		$this->datatable->setColumns(array("name"=>"type", "column"=>"type", 'values'=>get_type(), 'search'=>get_type('no'))); //
		
		$this->datatable->setColumns(array("name"=>"status", "column"=>"Status", 'values'=>$this->status_options(), 'search'=>$this->status_options())); //
		//$this->datatable->setColumns(array("name"=>"created_at", "column"=>"Added Date"));
		
		
		$this->datatable->buttons = array(
			array("link"=>admin_url('profile_list/import'), 'label'=>'Profiles Import'), 
			array("link"=>base_url('assets/csv/profiles_sample.csv?v=1'), 'label'=>'Sample Format'), 
		);
		
		$this->data['grid'] = $this->datatable->grid($query);
		
		add_css(array("datatable.css"));
		add_js(array("datatable.js"));
		
		$this->Page();	
	}

	////////////////////////


	public function import(){
		{//bread_crumb
			$this->breadcrumb(array("profile_list/import"=>"Import Profiles"));
		}
		
		$this->load->model('profiles_model');
		
		{//form
			$this->data['title'] = "Profiles Import CSV";
			$this->load->library('ciform');
			$this->load->library('csvimport');
			
			$general_elements = array(
				array('name' => 'csv', "label"=>"Import", "id"=>"csv", "type"=>"file", "validation"=>'required'),
				array('name' => 'helper', "label"=>"helper", "id"=>"helper", "type"=>"hidden", 'value'=>'done', "validation"=>'required'),
			);
			$this->ciform->sections['General Details'] = $general_elements;
			
			$this->ciform->cancel_link = admin_url('profile_list');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("profile_list/remove");
		}
		
		$this->data['form'] = $this->ciform->create_form('Import CSV');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			
			if(isset($_FILES['csv']) && !empty($_FILES['csv']['name'])){
				$config['upload_path']   = './media/uploads/';
				$config['allowed_types'] = 'csv';
				$config['encrypt_name']  = TRUE;
				$config['max_size']	     = '50000000';
				
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				
				if(!$this->upload->do_upload('csv')){
					//$errors = implode("<br />", $this->upload->display_errors());
					$errors = $this->upload->display_errors();
					error_msg($errors, admin_url("profile_list"));
				}else{
					$file_data = $this->upload->data();
					$file_path = './media/uploads/'.$file_data['file_name'];
					
					if($csv_data = $this->csvimport->get_array($file_path)){
						
						$fields_to_import = array();
						$n = 0;						
						foreach($csv_data as $nums => $rows){
							$n++;
							if($n==1){
								foreach($rows as $row_index => $row_data){
									$fields_to_import[$row_index] = $row_index;
								}
							}	
						}
						
						$count = 0;
						$insertcount = 0;
						$duplicate_profiles = 0;
						$errors_count = 0;
						$empty_names = 0;
						
						foreach($csv_data as $row){
							$count++;
							if($row['name']!= ''){

								$insert_data = array();
								foreach($fields_to_import as $field_to_import){
									$insert_data[$field_to_import] = trim($row[$field_to_import]);
								}
								
								
								if(isset($row['id']) && $row['id']!=''){
									if(isset($row['whatapp_no']) && $row['whatapp_no']!=''){
										$insert_data['whatapp_no']		= str_replace('+91', '', $row['whatapp_no']);
										$insert_data['whatapp_no']		= '+91'.$row['whatapp_no'];
									}
									unset($insert_data['id']);
									$insert_data['updated'] 	= date("Y-m-d H:i:s");
									$this->db->where("id", $row['id'])->update($this->profiles_model->table_name(), $insert_data);
								}else{
									$this->load->library('app');
									$slug = $this->app->slug($row['name']);
									//$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
									$insert_data['slug'] = $slug;
									
									$insert_data['added_on'] 		= date("Y-m-d H:i:s");
									$insert_data['status'] 			= 1;
									
									if(isset($row['image']) && $row['image']!=''){
										$insert_data['image']		= '/profilescsv/'.$row['image'];
									}
									
									$age_options				= array_rand($this->age_options(), 2);
									$insert_data['age']			= $this->age_options()[$age_options[0]];
									
									$bust_waist_hip_options			= array_rand($this->bust_waist_hip_options(), 2);
									$insert_data['bust_waist_hip']	= $this->bust_waist_hip_options()[$bust_waist_hip_options[0]];
									
									$eye_color_options			= array_rand($this->eye_color_options(), 2);
									$insert_data['eye_color']	= $this->eye_color_options()[$eye_color_options[0]];
									
									$hair_color_options			= array_rand($this->eye_color_options(), 2);
									$insert_data['hair_color']	= $this->eye_color_options()[$hair_color_options[0]];
									
									$height_options				= array_rand($this->height_options(), 2);
									$insert_data['height']		= $this->height_options()[$height_options[0]];
									
									$weight_options				= array_rand($this->weight_options(), 2);
									$insert_data['weight'] 		= $this->weight_options()[$weight_options[0]];
									
									$insert_data['language']	= 'Hindi, English';
									$insert_data['nationality'] = 'Indian';
									
									$services						= array_rand(get_services(), 8);
									$insert_data['services'] 		= implode('=', $services);
									//$insert_data['services'] 		= '69 Position=French Kissing=Kissing=Kissing if good chemistry=Erotic massage=Girlfriend Experience (GFE)=Blowjob without Condom=Cumshot on body (COB)=Anal Sex=Blowjob with Condom=Sex in Different Positions';
									
									$insert_data['availability'] 	= 'a:7:{s:6:"Monday";s:3:"Yes";s:7:"Tuesday";s:3:"Yes";s:9:"Wednesday";s:3:"Yes";s:8:"Thursday";s:3:"Yes";s:6:"Friday";s:3:"Yes";s:8:"Saturday";s:3:"Yes";s:6:"Sunday";s:3:"Yes";}';
									
									$insert_data['rates'] 		= 'a:5:{i:1;s:7:"4000 Rs";i:2;s:7:"8000 Rs";i:3;s:8:"12000 Rs";i:4;s:8:"15000 Rs";i:5;s:8:"25000 Rs";}';
									
									$this->db->insert($this->profiles_model->table_name(), $insert_data);
									
									$id = $this->db->insert_id();
									$save_u['slug'] = $insert_data['slug'].'-'.($id+1000);
									$this->db->where("id", $id)->update($this->profiles_model->table_name(), $save_u);
								}
								
								unset($insert_data);
								$insertcount++;
								
								//exit;
							}else{
								$empty_names++;
								$errors_count++;
							}
						}
					}else{
						error_msg("File is empty", admin_url("profile_list"));
					}
					
					unlink($file_path);
				}
			}else{
				error_msg("File not selected", admin_url("profile_list"));
			}	

			$msg = "Total {$count} rows processed.<br />";
			if($insertcount > 0){
				$msg .= "{$insertcount} New Profiles added.<br />";
			}
			if($duplicate_profiles > 0){
				$msg .= "{$duplicate_profiles} Duplicate Profiles found.<br />";
			}
			if($empty_names > 0){
				$msg .= "{$empty_names} Empty Profiles Name found.<br />";
			}
			
			if($errors_count==0){
				msg($msg, admin_url("profile_list"));
			}else{
				error_msg($msg, admin_url("profile_list"));
			}
		}	
	}
	

	
	public function add($id=false){
		$user_type = 'admin';
		$fields = array();
		{//Important
			$user = $this->_user;
			if(isset($user['user_type']) && $user['user_type']=='user'){
				$user_type = 'user';
				$permissions = $user['permissions'];
				if(isset($permissions['fields'])){
					$fields = $permissions['fields'];
				}
			}
		}
		
		{//bread_crumb
			$this->breadcrumb(array("profile_list/add/".$id=>"Profile"));
		}

		{//form
			$this->data['title'] = "Profile";
			$this->load->library('ciform');
			
			$age_options				= array_rand($this->age_options(), 2);
			$age 						= $this->age_options()[$age_options[0]];
			
			$bust_waist_hip_options		= array_rand($this->bust_waist_hip_options(), 2);
			$bust_waist_hip 			= $this->bust_waist_hip_options()[$bust_waist_hip_options[0]];
			
			$eye_color_options			= array_rand($this->eye_color_options(), 2);
			$eye_color 					= $this->eye_color_options()[$eye_color_options[0]];
			
			$hair_color_options			= array_rand($this->eye_color_options(), 2);
			$hair_color 				= $this->eye_color_options()[$hair_color_options[0]];
			
			$height_options				= array_rand($this->height_options(), 2);
			$height 					= $this->height_options()[$height_options[0]];
			
			$weight_options				= array_rand($this->weight_options(), 2);
			$weight 					= $this->weight_options()[$weight_options[0]];
			
			$language	 				= 'Hindi, English';
			$nationality 				= 'Indian';
			
			$data 				= '';
			if($id){
				$data = (array)$this->profiles_model->fetch_row_by_field("id", $id);
				$this->ciform->form_data = $data;
				
				$age 				= $data['age'];
				$bust_waist_hip		= $data['bust_waist_hip'];
				$eye_color	 		= $data['eye_color'];
				$hair_color 		= $data['hair_color'];
				$height		 		= $data['height'];
				$weight		 		= $data['weight'];
				$language	 		= $data['language'];
				$nationality 		= $data['nationality'];
			}
			
			
			$get_services = get_services('no');
			
			
			$table = $this->profiles_model->table_name();
			
			$general_elements = array(
				array('name' => 'name', "label"=>"Name", "id"=>"name", "type"=>"text", "validation"=>'required'),
				array('name' => 'sort_order', "label"=>"Sort Order", "id"=>"sort_order", "type"=>"text"),
				
				array('name' => 'image', "class"=>'pr_image col-lg-4', "label"=>"Photo", "type"=>"image", "image_path"=>"media/uploads/"),
				
				array('name' => 'mobile_no', "label"=>"Mobile No.", "type"=>"text"),
				array('name' => 'whatapp_no', "label"=>"Whatapp No.", "type"=>"text"),
				
				array('name' => 'email_id', "label"=>"Email ID", "type"=>"text"),
				
				array('name' => 'about_us', "label"=>"About Us", "type"=>"textarea", "validation"=>'required', "rows"=>'5'),
				
				array('name' => 'type', "label"=>"Profile type", "type"=>"select", "options"=>get_type('no'), "validation"=>'required'),
				array('name' => 'gender', "label"=>"Gender", "type"=>"select", "options"=>$this->gender_options(), "validation"=>'required'),
				
				array('name' => 'age', "label"=>"Age", "type"=>"text", "value"=>$age),
				//array('name' => 'bust_waist_hip', "label"=>"Bust waist hip", "type"=>"text", "value"=>$bust_waist_hip),
				//array('name' => 'eye_color', "label"=>"Eye color", "type"=>"text", "value"=>$eye_color),
				//array('name' => 'hair_color', "label"=>"Hair color", "type"=>"text", "value"=>$hair_color),
				//array('name' => 'height', "label"=>"Height", "type"=>"text", "value"=>$height),
				//array('name' => 'weight', "label"=>"Weight", "type"=>"text", "value"=>$weight),
				//array('name' => 'language', "label"=>"Language", "type"=>"text", "value"=>$language),
				//array('name' => 'ethnicity', "label"=>"Ethnicity", "type"=>"text", "validation"=>'required'),
				//array('name' => 'nationality', "label"=>"Nationality", "type"=>"text", "value"=>$nationality),
				array('name' => 'website', "label"=>"Website", "type"=>"text"),
				
				
				array('name' => 'f_top', "label"=>"Top Fetured", "type"=>"select", "options"=>$this->fetured_options(), "validation"=>'required'),
				array('name' => 'f_right', "label"=>"Right Fetured", "type"=>"select", "options"=>$this->fetured_options(), "validation"=>'required'),
				//array('name' => 'f_footer', "label"=>"Footer Fetured", "type"=>"select", "options"=>$this->fetured_options(), "validation"=>'required'),
				
				array('name' => 'status', "label"=>"Status", "type"=>"select", "options"=>$this->status_options(), "validation"=>'required'),
			);	

			$this->ciform->sections['General Details'] 		= $general_elements;
			
			////////////////////////
			$this->data['data']		= $data;
			$city_html = $this->load->view($this->admin_view('profile_list/add_city'), $this->data, true);
			$location_elements = array(
				//array('name' => 'state', "label"=>"State", "type"=>"select", 'id'=>'state', 'width'=>'100%', "options"=>get_states()),
				array('name' => 'city', "label"=>"City", "type"=>"html", "html"=>$city_html),
				array('name' => 'zip_code', "label"=>"Zip Code", "type"=>"text"),
			);	
			$this->ciform->sections['Location'] 		= $location_elements;
			
			
			///////////////////////////////////
			
			{//gallery
				$this->data['gallery_images'] = array();
				if($id){
					$this->data['gallery_images'] = $this->profiles_model->gallery_images($id);
				}
				$gallery_html = $this->load->view($this->admin_view('media/gallery'), $this->data, true);
				$_gallery = array(
					array('name' => 'gallery', "label"=>"Gallery", "type"=>"html", "html"=>$gallery_html),
				);
				$this->ciform->sections['Gallery'] = $_gallery;
			}
			
			
			////////////////////////////////
			$availability_html = $this->load->view($this->admin_view('profile_list/add_availability'), $this->data, true);
			$availability_elements = array(
				array('name' => 'availability', "label"=>"Availability", "type"=>"html", "html"=>$availability_html),
			);	
			//$this->ciform->sections['Availability'] 		= $availability_elements;
			
			
			/////////////////////////////
			$rates_html = $this->load->view($this->admin_view('profile_list/add_rates'), $this->data, true);
			$rates_elements = array(
				array('name' => 'rates', "label"=>"Availability", "type"=>"html", "html"=>$rates_html),
			);	
			//$this->ciform->sections['Rates'] 		= $rates_elements;
			
			
			/////////////////////////////
			$this->data['get_services']		= $get_services;
			$services_html = $this->load->view($this->admin_view('profile_list/add_services'), $this->data, true);
			$services_elements = array(
				array('name' => 'services', "label"=>"Services", "type"=>"html", "html"=>$services_html),
			);	
			//$this->ciform->sections['Services'] 		= $services_elements;
			
			
			////////////////////
			$elements = array(
					array('name' => 'meta_title', "label"=>"Meta Title", "type"=>"textarea"),
					array('name' => 'meta_description', "label"=>"Meta Description", "type"=>"textarea"),
					array('name' => 'meta_keywords', "label"=>"Meta Keywords", "type"=>"textarea"),
					array('name' => 'header_code', "label"=>"Header Code", "rows"=>"5", "type"=>"textarea"),
			);
			$this->ciform->sections['Profile Meta'] = $elements;
			
			
			$this->ciform->cancel_link = admin_url('profile_list');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("profile_list/remove");
			//$this->ciform->save_link = false;
		}
		
		$this->data['form'] = $this->ciform->create_form('Manage Vendors Profile');
		if($this->data['form']){
			$this->Page();
		}else{//save post
			//print_r($_POST);exit;
			$saved = $this->profiles_model->save($id);
			if($saved){
				msg('Customer Updated successfully.', admin_url("profile_list"));
			}else{
				error_msg('Error!', admin_url("profile_list"));
			}
			//redirect($this->admin_url("cms/index/add/$saved"));
		}
	}
	
	//////////////////////////////
	
	
	function get_cities(){
		$city				= $_POST['city'];
		$state				= $_POST['state'];
		
		$html = '<option value="">Select City</option>';
		foreach(get_cities($state) as $k => $c){
			if($c->name == $city){
				$html .= '<option selected="selected" value="'.$c->name.'">'.$c->name.'</option>';
			}else{
				$html .= '<option value="'.$c->name.'">'.$c->name.'</option>';
			}
		}
		
		echo $html;
	}
	
	function get_locality(){
		$city				= $_POST['city'];
		$locality			= $_POST['locality'];
		
		$html = '<option value="">Select City</option>';
		foreach(get_locality($city) as $k => $c){
			if($c->name == $locality){
				$html .= '<option selected="selected" value="'.$c->name.'">'.$c->name.'</option>';
			}else{
				$html .= '<option value="'.$c->name.'">'.$c->name.'</option>';
			}
		}
		
		echo $html;
	}
	
	
	
	
//////////////////////////
	
	public function view($id=false){
		if(!$id){
			error_msg('Error!', admin_url("profile_list"));
		}
		
		{//bread_crumb
			$this->breadcrumb(array("profile_list/view/".$id=>"View Customer Details"));
		}
		
		{//form
			$this->data['title'] = "View Customer Details";
			$this->load->library('ciform');
			
			$data = (array)$this->profiles_model->fetch_row_by_field("id", $id);
			$this->ciform->form_data = $data;
			
			$this->data['data']  = $data; 
			
			if(!isset($data['id'])){
				error_msg('Error!', admin_url("profile_list"));
			}
			
			$this->data['data'] 				= $data;
			$this->data['status_options'] 		= $this->status_options();
			
			$this->data['get_orders'] 			= $this->customers_model->get_orders_by_profiles($id);
		
			$view_html 							= $this->load->view("admin/profile_list/view", $this->data, true);
			
			
			$general_elements = array(
				array('name' => 'id', "label"=>"Id",  "id"=>"id", "value"=>$id, "type"=>"hidden", "validation"=>'required'),
				array('name' => 'id', "label"=>"id", 'type'=>'html', 'html'=>$view_html, 'class'=>'col-lg-8'),			
			);	
			
			$this->ciform->sections['User Details'] = $general_elements;
			$this->ciform->cancel_link = admin_url('profile_list');
			$this->ciform->remove_index = 'id';
			$this->ciform->remove_link = $this->admin_url("profile_list/remove");
			
			$this->ciform->save_link = false;
			
			$this->data['form'] = $this->ciform->create_form('Manage Coupan');
		}
		
		
		$this->data['form'] = $this->ciform->create_form('Manage Vendors Profile');
		if($this->data['form']){
			//print_r($_POST);
			$this->Page();
		}else{//save post
			
			$saved = $this->Page_model->save($id);
			if($saved){
				msg('User Updated successfully.', admin_url("profile_list/view/".$id));
			}else{
				error_msg('Error!', admin_url("profile_list/view/".$id));
			}
			//redirect($this->admin_url("cms/index/add/$saved"));
		}
	}


////////////////////
	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
	
	function remove($id=NULL){
		if(!is_null($id)){
			$this->profiles_model->remove($id);
			redirect($this->admin_url("profile_list"));
		}else{
			redirect($this->admin_url("profile_list"));
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */