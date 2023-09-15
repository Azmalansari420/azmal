<?php
class Section_model extends WD_Model {

	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;

	const CONTENT_POSITION_ABOVE = 'a';
	const CONTENT_POSITION_BELOW = 'b';

	function __construct(){
		$this->set_table_name('cms_sections');
		$this->set_table_index('id');
	}

	public function status_options(){
		return array(self::STATUS_ENABLED=>'Enabled', self::STATUS_DISABLED=>'Disabled');
	}

	public function content_position_options(){
		return array(self::CONTENT_POSITION_ABOVE=>'Above Template', self::CONTENT_POSITION_BELOW=>'Below Template');
	}

	function save($id=false){

		//$this->post_data('type', 'type');
		$this->post_data('identifier', 'identifier');
		$this->post_data('title', 'title');
		
		$content = htmlspecialchars_decode(htmlentities($_POST['content'], ENT_QUOTES, 'UTF-8'));
		$this->set_post_data('content', $content);
		
		$this->post_data('template', 'template');
		$this->post_data('includes', 'includes');
		$this->post_data('content_position', 'content_position');

		$this->post_data('status', 'status');
		//$this->post_data('visibility', 'visibility');
			
		$return = $id;
		$section_data = array();

		if($id){//update
			$this->set_post_data('updated_on', date("Y-m-d H:i:s"));
			
			$section_data = $this->get_post_data();

			$this->post_update($id, 'id');

			$return = $id;
		
		}else{//new insert
			$this->set_post_data('added_on', date("Y-m-d H:i:s"));

			$section_data = $this->get_post_data();
			$return = $this->post_save();
		}

		$this->load->library('cms');

		try{
		
			$cache = $this->cms->cache_section($section_data);
		}catch (\Exception $e){
		
			die($e->getMessage());
		}
		return $return;
	}
	
	function remove($id){
		//remove attribute
		$this->db->where("id", $id)->delete($this->table_name());
	}
}
?>