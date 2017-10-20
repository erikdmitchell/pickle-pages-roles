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

/**
 * ppr_checked_array function.
 * 
 * @access public
 * @param string $checked (default: '')
 * @param array $array (default: array())
 * @param bool $echo (default: true)
 * @return void
 */
function ppr_checked_array($checked='', $array=array(), $echo=true) {
	if (in_array($checked, $array)) :
		$checked_output='checked="checked"';
	else :
		$checked_output='';
	endif;
	
	if ($echo)
		echo $checked_output;
		
	return $checked_output;
}	

function ppr_get_roles() {
	global $wp_roles;
	
	if (!isset($wp_roles)) :
		$wp_roles=new WP_Roles();
	endif;
	
	return $wp_roles;
}

function ppr_default_roles() {
	return pickle_pges_roles()->admin->settings['default_roles'];
}

function ppr_role_restricted_post_types() {
	return pickle_pges_roles()->admin->settings['post_types'];
}

function ppr_post_edit_roles($post_id=0) {
	$default_roles=ppr_default_roles();
	$post_roles=get_post_meta($post_id, '_ppr_roles_allow_edit', true);
	
	return ppr_wp_parse_args($post_roles, $default_roles);
}

function ppr_update_post_edit_roles($post_id=0, $roles='') {
	if (!$post_id || empty($roles))
		return;
	
	update_post_meta($post_id, '_ppr_roles_allow_edit', $roles);		
}
?>