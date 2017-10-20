<?php

class Pickle_Pages_Roles_Meta_Box {

    public function __construct() {
        if (is_admin()) :
            add_action('load-post.php', array($this, 'init_metabox'));
            add_action('load-post-new.php', array($this, 'init_metabox'));
        endif;
    }

    public function init_metabox() {
        add_action('add_meta_boxes', array($this, 'add_metabox' ));
        add_action('save_post', array($this, 'save_metabox'), 10, 2);
    }

    public function add_metabox() {
        add_meta_box(
            'ppr-restrict-roles-meta-box',
            __('Restrict Edit Roles', 'ppr'),
            array($this, 'render_metabox'),
            ppr_role_restricted_post_types(),
            'side',
            'low'
        );
 
    }

    public function render_metabox($post) {
        wp_nonce_field('custom_nonce_action', 'custom_nonce');
        
        $html='';
        
        $html.='<form class="ppr-restrict-roles">';
        	$html.='<p class="description">This applies to the backend only.</p>';
        	
        	$html.='<fieldset">';
        	
        		foreach (ppr_get_roles()->role_names as $slug => $name) :
        			$html.='<label for="'.$slug.'"><input name="ppr_retrict_roles[]" type="checkbox" id="'.$slug.'" value="'.$slug.'" '.ppr_checked_array($slug, ppr_post_edit_roles($post->ID), false).'>'.$name.'</label><br/>';
        		endforeach;
        	
        	$html.='</fieldset>';
        $html.='</form>';
        
        echo $html;
    }

    public function save_metabox($post_id, $post) {
        // Check nonce
        if (!isset($_POST['custom_nonce']) || !wp_verify_nonce($_POST['custom_nonce'], 'custom_nonce_action'))
            return;
 
        // Check if user has permissions to save data.
        if (!current_user_can('edit_post', $post_id))
            return;
 
        // Check if not an autosave.
        if (wp_is_post_autosave($post_id))
            return;
 
        // Check if not a revision.
        if (wp_is_post_revision($post_id))
            return;
            
        // update data //
		ppr_update_post_edit_roles($post_id, $_POST['ppr_retrict_roles']);
    }
}
 
new Pickle_Pages_Roles_Meta_Box();	
?>