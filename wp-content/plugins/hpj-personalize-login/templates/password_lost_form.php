<div id="password-lost-form" class="widecolumn">
	<?php if ( $attributes['show_title'] ) : ?>
		<h1 class="h2"><?php _e( 'Forgot Your Password?', 'personalize-login' ); ?></h1>
	<?php endif; ?>

	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
		<?php foreach ( $attributes['errors'] as $error ) : ?>
			<p>
				<?php echo $error; ?>
			</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<p>
		<?php
			_e(
				"Enter your email address and we'll send you a link you can use to pick a new password.",
				'personalize-login'
			);
		?>
	</p>

	<form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
		<div class="form-row form-group">
			<label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
			<input class="form-control" type="text" name="user_login" id="user_login">
		</div>

		<div class="lostpassword-submit">
			<input class="btn btn-default lostpassword-button" type="submit" name="submit" value="<?php _e( 'Reset Password', 'personalize-login' ); ?>"/>
		</div>
	</form>
</div>