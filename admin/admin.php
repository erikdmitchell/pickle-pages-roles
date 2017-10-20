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

}
?>