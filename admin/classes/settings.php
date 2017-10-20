<?php

class Pickle_Pages_Roles_Admin_Settings {
	
	public function __construct() {
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'save_settings'));
	}
	
	public function admin_menu() {
		add_options_page('PPR Settings', 'PPR Settings', 'manage_options', 'ppr-settings', array($this, 'admin_page'));
	}
	
	public function admin_page() {
		pickle_pges_roles()->admin->load_page('settings');
	}
	
	public function save_settings() {
		// Check nonce
        if (!isset($_POST['pickle_pages_roles_admin']) || !wp_verify_nonce($_POST['pickle_pages_roles_admin'], 'update_settings'))
            return;		

		// do we have settings
		if (!isset($_POST['settings']))
			return;
			
		update_option('ppr_settings', $_POST['settings']);
		
		// reload settings //
		pickle_pges_roles()->admin->settings();
		
		// run our admin update //
		pickle_pges_roles()->admin->update_posts_edit_roles();
	}
	
}

new Pickle_Pages_Roles_Admin_Settings();
?>