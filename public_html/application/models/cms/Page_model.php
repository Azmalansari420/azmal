<?php
class Page_model extends WD_Model {

	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

	const VISIBILITY_AUTO = 'a'; // Listed automatically in the url's
	const VISIBILITY_MANUAL = 'm'; // Need to create manual controller

	function __construct(){
		$this->set_table_name('cms_page');
		$this->set_table_index('id');
	}

	public function status_options(){
		return array(self::STATUS_ENABLED=>'Enabled', self::STATUS_DISABLED=>'Disabled');
	}

	public function visibility_options(){
		return array(self::VISIBILITY_AUTO=>'Auto', self::VISIBILITY_MANUAL=>'Manual');
	}
	
	function save($id=false){

		$this->post_data('type', 'type');
		//$this->_post_data['identifier'] = clean_unique_code($_POST['identifier']);

		if(!$id){
			$this->load->library('app');
			$slug = (isset($_POST['slug'])) ? $_POST['slug'] : $this->app->slug($_POST['title']);

			//Validate slug]
			$slug = $this->app->validate_slug($slug, 'slug', $this->table_name());
			$this->set_post_data('slug', $slug);
		}
		if(isset($_POST['slug']) && $id){
			$this->post_data('slug', 'slug');
		}
		
		$this->post_data('title', 'title');
		$this->post_data('banner_image', 'banner_image');
		
		$content = htmlspecialchars_decode(htmlentities($_POST['content'], ENT_QUOTES, 'UTF-8'));
		$this->set_post_data('content', $content);
		
		$this->post_data('template', 'template');
		$this->post_data('includes', 'includes');

		$this->post_data('meta_title', 'meta_title');
		$this->post_data('meta_description', 'meta_description');
		$this->post_data('meta_keywords', 'meta_keywords');

		$this->post_data('status', 'status');
		$this->post_data('visibility', 'visibility');
			
		if($id){//update
			$this->set_post_data('updated_at', date("Y-m-d H:i:s"));
			$this->post_update($id, 'id');
			return $id;
		
		}else{//new insert
			$this->set_post_data('created_at', date("Y-m-d H:i:s"));
			return $this->post_save();
		}
	}

	public function get_active_pages($visibility=self::VISIBILITY_AUTO){
		return $this->db->where('status', self::STATUS_ENABLED)->where('visibility', $visibility)->get($this->table_name())->result();
	}

	public function process_includes($page){

		$return = [];

		if($page->includes != ''){

			$includes = $page->includes;

			$args = array("type", "name", "method", "var", "before", "after");
			$callbacks = [];

			//find all blocks
			preg_match_all('/\[(.*?)\]/i', stripslashes($includes), $blocks);

			foreach($blocks[1] as $block){
				$breaks = explode(",", $block);

				$callback = [];
				foreach($breaks as $break){

					foreach($args as $arg){
						if(stristr($break, $arg)){
							$callback[$arg] = $this->trim_var($break);
						}
					}
				}
				$callbacks[] = $callback;
			}

			if( count($callbacks) > 0){
				foreach($callbacks as $_callback){

					if($_callback['type'] == 'js'){

						$return['js'][$_callback['name']] = $_callback;
					
					}elseif($_callback['type'] == 'css'){
					
						$return['css'][$_callback['name']] = $_callback;
					
					}else{	

						$name = $_callback['name'];
						$type = $_callback['type'];
						
						$this->load->$type($name);

						$var = (array_key_exists('var', $_callback)) ? $_callback['var'] : $_callback['method'];

						$method = $_callback['method'];

						if($_callback['type'] == 'helper'){

							$return[$var] = $method($page);
						}else{

							$return[$var] = $this->$name->$method($page);
						}
					}	
				}
			}
		}

		return $return;
	}

	public function trim_var($break){
		$vars = explode("=", $break);
		$var = trim($vars[1]);
		return str_replace(array('"', "'"), "", $var);
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}

	public function _wd_navigation_fields(){

		$result = $this->db->select('slug, title')->where('status', self::STATUS_ENABLED)->get($this->table_name())->result();
		$pages[''] = 'Select CMS Page';
		foreach($result as $page){
			$pages[$page->slug] = clean_display($page->title);
		}

		$fields = [];

		$fields['label'] = ['title' => 'Label', 'type' => 'text', 'validation' => 'required-entry'];
		$fields['page_slug'] = ['title' => 'Pages', 'type' => 'dropdown', 'validation' => 'required-entry', 'options' => $pages];
		$fields['classes'] = ['title' => 'CSS Classes', 'type' => 'text', 'validation' => ''];

		return $fields;
	}
}
?>