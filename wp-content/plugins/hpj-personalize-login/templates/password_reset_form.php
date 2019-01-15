<div id="password-reset-form" class="widecolumn">
	<?php if ( $attributes['show_title'] ) : ?>
		<h1 class="h2"><?php _e( 'Pick a New Password', 'personalize-login' ); ?></h1>
	<?php endif; ?>

	<form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
		<input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

		<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
			<?php foreach ( $attributes['errors'] as $error ) : ?>
				<p>
					<?php echo $error; ?>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="form-group">
			<label for="pass1"><?php _e( 'New password', 'personalize-login' ) ?></label>
			<input class="form-control" type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
		</div>
		<div class="form-group">
			<label for="pass2"><?php _e( 'Repeat new password', 'personalize-login' ) ?></label>
			<input class="form-control" type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
		</div>

		<p class="description small"><?php _e( 'The password should be at least <strong>six characters long</strong>. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).', 'personalize-login' ); ?></p>

		<div class="resetpass-submit">
			<input type="submit" name="submit" id="resetpass-button"
			       class="btn btn-default" value="<?php _e( 'Update Password', 'personalize-login' ); ?>" />
		</div>
	</form>
</div>