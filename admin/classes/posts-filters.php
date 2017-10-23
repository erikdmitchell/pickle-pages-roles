<?php

class Pickle_Pages_Roles_Admin_Posts_Filters {
	
	public function __construct() {
		if (is_admin()) :
			add_action('pre_get_posts', array($this, 'filter_posts_list'));

			if (!function_exists('wp_get_current_user'))
				include(ABSPATH.'wp-includes/pluggable.php');

			$this->user=wp_get_current_user();
		endif;
	}
	
	public function filter_posts_list($query) {
		$meta_arrays=array();
		
		//if (!$query->is_main_query())
			//return;
echo "filter posts list";		
		// build "meta_keys" array //
		foreach ($this->user->roles as $role) :
			$meta_arrays[]=array(
				'key' => '_ppr_roles_allow_edit_'.$role,
				'value' => 1,
				'compare' => '=',	
			);
		endforeach;

		$meta_query=array(
			'compare' => 'OR',
			$meta_arrays
		);

		$query->set('meta_query', $meta_query);
	}
	
}

new Pickle_Pages_Roles_Admin_Posts_Filters();
?>