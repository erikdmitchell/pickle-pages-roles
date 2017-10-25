<?php

class Pickle_Pages_Roles_Admin_Posts_Filters {
	
	public function __construct() {
		if (is_admin()) :
			add_action('pre_get_posts', array($this, 'filter_posts_list')); 
			add_action('admin_head', array($this, 'check_post_edit_access'));

			if (!function_exists('wp_get_current_user'))
				include(ABSPATH.'wp-includes/pluggable.php');

			$this->user=wp_get_current_user();
		endif;
	}
	
	public function filter_posts_list($query) {
		$meta_arrays=array();
		
		if (!$query->is_main_query())
			return;

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
	
	public function check_post_edit_access() {
		global $post;

		if (empty($post))
			return;

		foreach ($this->user->roles as $role) :
			if (!ppr_can_role_edit($post->ID, $role)) :
				wp_die(__('You do not have access to this post.'));
			endif;
		endforeach;
			
		return;
	}
	
}

new Pickle_Pages_Roles_Admin_Posts_Filters();
?>