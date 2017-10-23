<?php
	
/**
 * ppr_wp_parse_args function.
 * 
 * @access public
 * @param mixed &$a
 * @param mixed $b
 * @return void
 */
function ppr_wp_parse_args(&$a, $b) {
	$a = (array) $a;
	$b = (array) $b;
	$result = $b;
	foreach ( $a as $k => &$v ) {
		if ( is_array( $v ) && isset( $result[ $k ] ) ) {
			$result[ $k ] = ppr_wp_parse_args( $v, $result[ $k ] );
		} else {
			$result[ $k ] = $v;
		}
	}
	
	return $result;
}
//
function ppr_role_checked($post_id=0, $role='', $echo=true) {
	if (ppr_can_role_edit($post_id, $role)) :
		$checked_output='checked="checked"';
	else :
		$checked_output='';
	endif;
	
	if ($echo)
		echo $checked_output;
		
	return $checked_output;
}	
//
function ppr_get_roles() {
	global $wp_roles;
	
	if (!isset($wp_roles)) :
		$wp_roles=new WP_Roles();
	endif;
	
	return $wp_roles;
}

function ppr_checked_array($checked='', $array=array(), $echo=true) {
	if (!is_array($array))
		return;
			
	if (in_array($checked, $array)) :
		$checked_output='checked="checked"';
	else :
		$checked_output='';
	endif;
	
	if ($echo)
		echo $checked_output;
		
	return $checked_output;
}

function ppr_default_roles() {
	return pickle_pges_roles()->admin->settings['default_roles'];
}

function ppr_role_restricted_post_types() {
	return pickle_pges_roles()->admin->settings['post_types'];
}
//
function ppr_post_edit_roles($post_id=0) {
	$default_roles=ppr_default_roles();
	$post_roles=ppr_get_post_edit_roles($post_id);

	return ppr_wp_parse_args($post_roles, $default_roles);
}
//
function ppr_can_role_edit($post_id=0, $role='') {
	if (get_post_meta($post_id, '_ppr_roles_allow_edit_'.$role, true)==1)
		return true;
		
	return false;
}

function ppr_update_post_edit_roles($post_id=0, $roles='') {
	if (!$post_id || empty($roles))
		return;

	foreach (ppr_get_roles()->role_names as $slug => $name) :
		if (in_array($slug, $roles)) :
			$access=1;
		else :
			$access=0;
		endif;
		
		update_post_meta($post_id, '_ppr_roles_allow_edit_'.$slug, $access);
	endforeach;		
}
//
function ppr_remove_post_edit_roles($post_id=0, $roles='') {
	if (!$post_id)
		return;
		
	if (empty($roles)) :
		$roles=ppr_get_roles()->role_names;
		$roles=array_keys($roles);
	endif;
	
	if (!is_array($roles))
		$roles=array_map('trim', explode(',', $roles));
		
	foreach ($roles as $role) :
		update_post_meta($post_id, '_ppr_roles_allow_edit_'.$role, 1);	
	endforeach;
}
//
function ppr_get_post_edit_roles($post_id=0) {
	global $wpdb;
	
	$edit_roles=$wpdb->get_col("SELECT REPLACE(meta_key, '_ppr_roles_allow_edit_', '') AS role FROM $wpdb->postmeta WHERE meta_key LIKE '_ppr_roles_allow_edit_%' AND post_id = $post_id AND meta_value");
	
	return $edit_roles;
}
?>