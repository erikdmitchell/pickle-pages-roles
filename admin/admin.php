<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Pickle_Pages_Roles_Admin {
	
	public $settings='';

	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	public function includes() {

	}

	private function init_hooks() {
		add_action('init', array($this, 'settings'));
	}
	
	public function load_page($name='') {
		$file=PICKLE_PAGES_ROLES_ADMIN_PATH.'pages/'.$name.'.php';
		
		if (file_exists($file))
			load_template($file, true);
	}
	
	public function settings() {
		$default_settings=array(
			'post_types' => '',
			'default_roles' => array('administrator'),
		);
		$stored_settings=get_option('ppr_settings', array());
	
		$this->settings=ppr_wp_parse_args($stored_settings, $default_settings);
	}
	
	public function update_posts_edit_roles() {
		if (empty($this->settings['post_types']) || empty($this->settings['default_roles'])) :
			$post_ids=get_posts(array(
				'posts_per_page' => -1,
				'post_type' => get_post_types(),
				'fields' => 'ids',
				'post_status' => 'any',
			));

			if (count($post_ids)) :
				foreach ($post_ids as $post_id) :
					$existing_roles=ppr_post_edit_roles($post_id);
					$roles=ppr_wp_parse_args($this->settings['default_roles'], $existing_roles);
				
					ppr_remove_post_edit_roles($post_id);
				endforeach;
			endif;
		else :
			$post_ids=get_posts(array(
				'posts_per_page' => -1,
				//'post_type' => $this->settings['post_types'],
				'post_type' => get_post_types(),
				'fields' => 'ids',
				'post_status' => 'any',
			));

			if (count($post_ids)) :
				foreach ($post_ids as $post_id) :
					$existing_roles=ppr_post_edit_roles($post_id);
					$roles=ppr_wp_parse_args($this->settings['default_roles'], $existing_roles);
				
					// add if check, otherwise remove //
					if (in_array(get_post_type($post_id), $this->settings['post_types'])) :					
						ppr_update_post_edit_roles($post_id, $roles); // add
					else :						
						ppr_remove_post_edit_roles($post_id); // remove
					endif;
				endforeach;
			endif;	
		endif;

		return;	
	}

}
?>