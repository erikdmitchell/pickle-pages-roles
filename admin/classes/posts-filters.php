<?php

class Pickle_Pages_Roles_Admin_Posts_Filters {
	
	public function __construct() {
		if (is_admin()) :
			add_action('pre_get_posts', array($this, 'filter_posts_list'));
		endif;
	}
	
	public function filter_posts_list($query) {
//print_r($query);
	}
	
}

new Pickle_Pages_Roles_Admin_Posts_Filters();
?>