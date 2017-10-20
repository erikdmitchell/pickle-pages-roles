<?php
/*
Plugin Name: Pickle Pages Roles
Plugin URI:  
Description: Allow admin access to pages and posts based on a user role.
Version:     0.1.0
Author:      Erik Mitchell
Author URI:  
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ppr
Domain Path: /languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('PPR_FILE', __FILE__);

final class Pickle_Pages_Roles {

	public $version='0.1.0';

	protected static $_instance=null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	private function define_constants() {
		$this->define('PICKLE_PAGES_ROLES_PATH', plugin_dir_path(__FILE__));
		$this->define('PICKLE_PAGES_ROLES_URL', plugin_dir_url(__FILE__));
		$this->define('PICKLE_PAGES_ROLES_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
		$this->define('PICKLE_PAGES_ROLES_ADMIN_URL', plugin_dir_url(__FILE__).'admin/');
		$this->define('PICKLE_PAGES_ROLES_VERSION', $this->version);
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function includes() {
		/**
		 * general
		 */
		include_once(PICKLE_PAGES_ROLES_PATH.'functions.php');
		
		/**
		 * classes
		 */
		include_once(PICKLE_PAGES_ROLES_PATH.'classes/install.php');
		
		/**
		 * admin
		 */
		include_once(PICKLE_PAGES_ROLES_ADMIN_PATH.'admin.php');
		include_once(PICKLE_PAGES_ROLES_ADMIN_PATH.'classes/metabox.php');
		include_once(PICKLE_PAGES_ROLES_ADMIN_PATH.'classes/settings.php');		
		
		if (is_admin()) :
			$this->admin=new Pickle_Pages_Roles_Admin();
		endif;
	}

	private function init_hooks() {
		//register_activation_hook( WC_PLUGIN_FILE, array( 'WC_Install', 'install' ) );
		register_activation_hook(PPR_FILE, array('Pickle_Pages_Roles_Install', 'install'));
		//register_activation_hook(PPR_FILE, array($this, 'install'));
	}
	
	public function install() {
//add_option('ppr', 1);
	}

}

function pickle_pges_roles() {
	return Pickle_Pages_Roles::instance();
}

// Global for backwards compatibility.
$GLOBALS['pickle_pges_roles'] = pickle_pges_roles();
?>