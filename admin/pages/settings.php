<?php $post_types=get_post_types(); ?>

<div class="wrap">
	
	<h1>Pickle Pages Roles</h1>
			
	<form class="pickle-calendar-settings-form" action="" method="post">
		<?php wp_nonce_field('update_settings', 'pickle_pages_roles_admin', true); ?>	
		
		<h2>Post Details (metabox)</h2>	

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">Enable Role Restriction</th>
						<td>
							<fieldset>
								
								<?php foreach ($post_types as $post_type_slug) : ?>
									
									<?php $post_type=get_post_type_object($post_type_slug); ?>
									
									<label for="<?php echo $post_type_slug; ?>"><input name="settings[post_types][]" type="checkbox" id="<?php echo $post_type_slug; ?>" value="<?php echo $post_type_slug; ?>" <?php ppr_checked_array($post_type_slug, pickle_pges_roles()->admin->settings['post_types']); ?>><?php echo $post_type->labels->name; ?></label><br/>
								
								<?php endforeach; ?>

							</fieldset>
						</td>
				</tr>

				<tr>
					<th scope="row">Set Default Roles</th>
						<td>
							<fieldset>
								
								<?php foreach (ppr_get_roles()->role_names as $slug => $name) : ?>
									
									<label for="<?php echo $slug; ?>"><input name="settings[default_roles][]" type="checkbox" id="<?php echo $slug; ?>" value="<?php echo $slug; ?>" <?php ppr_checked_array($slug, pickle_pges_roles()->admin->settings['default_roles']); ?>><?php echo $name; ?></label><br/>
								
								<?php endforeach; ?>
								
								<p class="description">These roles will have access to the protected pages, posts, etc. by default. <strong>Administrator is always the default.</strong></p>

							</fieldset>
						</td>
				</tr>

			</tbody>				
		</table>	
		
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		
	</form>
	
</div>