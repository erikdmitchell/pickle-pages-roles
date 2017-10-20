<?php

class Pickle_Pages_Roles_Install {
	
	public static function init() {
		// init		
	}
	
	public static function install() {
		if (!is_blog_installed())
			return;
		
		// Check if we are not already running this routine.
		if ('yes'===get_transient('ppr_installing'))
			return;

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient('ppr_installing', 'yes', MINUTE_IN_SECONDS*10);
		
		self::do_nothing();
		
		delete_transient('ppr_installing');		
	}
	
	public static function do_nothing() {
		return;
	}
	
}

Pickle_Pages_Roles_Install::init();
?>