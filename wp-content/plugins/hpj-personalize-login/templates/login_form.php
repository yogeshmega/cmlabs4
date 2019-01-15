<?php if ( true ) : ?>
<div class="login-form-container">
	<?php if ( $attributes['show_title'] ) : ?>
		<h1 class="h2"><?php _e( 'Log in to continue', 'personalize-login' ); ?></h1>
	<?php endif; ?>
	
		<?php if ( $attributes['sales_portal'] ) : ?>
		<p class="login-info">
			<?php _e( 'Access to the sales portal has been combined with the main CM Labs web site. Your previous sales portal login is not valid anymore.<br /><br />Please log in with your CM Labs credentials below. If you never connected to the main CM Labs site but had a sales portal account, a new account has been created for you and you can just set a <a href="https://www.cm-labs.com/password-lost/">new password here</a>. If you never had a sales portal or CM Labs web site account, click <strong>Create Account</strong> below to create a new account and e-mail <a href="mailto:marketing@cm-labs.com?subject=Sales Portal Access">marketing@cm-labs.com</a> with your account details to request access to the sales portal.<br /><br />Once logged in, the new Sales Portal menu section will appear.<br /><br />', 'personalize-login' ); ?>
		</p>
	<?php endif; ?>
	
	<a rel="nofollow"
		href="<?php echo site_url(); ?>/login/?action=wordpress_social_authenticate&amp;mode=login&amp;provider=LinkedIn&amp;redirect_to=<?php 
		if ( isset( $attributes['redirect'] ) && !empty( $attributes['redirect'] ) ) {
		    echo esc_url( $attributes['redirect'] );   
		} else {
		    echo site_url() . '/' . __( 'account', 'personalize-login' );
		}
		?>"
		title="<?php _e( 'Log in with LinkedIn', 'personalize-login' ); ?>"
		class="btn btn-linked"
		data-provider="LinkedIn">
		<span class="icon-linkedin"></span><span><?php _e('Log in with LinkedIn', 'personalize-login'); ?></span>
	</a>
	<p class="text-center separator"><span><?php _e( 'or', 'personalize-login' ); ?></span></p>

	<!-- Show errors if there are any -->
	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
		<?php foreach ( $attributes['errors'] as $error ) : ?>
			<p class="login-error">
				<?php echo $error; ?>
			</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<!-- Show logged out message if user just logged out -->
	<?php if ( $attributes['logged_out'] ) : ?>
		<p class="login-info">
			<?php _e( 'You have signed out. Would you like to log in again?', 'personalize-login' ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $attributes['registered'] ) : ?>
		<p class="login-info">
			<?php
				printf(
					__( 'You have successfully registered to <strong>%s</strong>. We have emailed your the confirmation link to the email address you entered.', 'personalize-login' ),
					get_bloginfo( 'name' )
				);
			?>
		</p>
	<?php endif; ?>

	<?php if ( $attributes['lost_password_sent'] ) : ?>
		<p class="login-info">
			<?php _e( 'Check your email for a link to reset your password.', 'personalize-login' ); ?>
		</p>
	<?php endif; ?>

	<?php if ( $attributes['password_updated'] ) : ?>
		<p class="login-info">
			<?php _e( 'Your password has been updated. You can log in now.', 'personalize-login' ); ?>
		</p>
	<?php endif; ?>

	<div class="login-form-container">
		<form method="post" action="<?php echo wp_login_url(); ?>">
			<div class="login-username form-group">
				<label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
				<input class="form-control" type="text" name="log" id="user_login">
			</div>
			<div class="login-password form-group">
				<label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
				<input class="form-control" type="password" name="pwd" id="user_pass">
				<?php if ( isset( $attributes['redirect'] ) && !empty( $attributes['redirect'] ) ) { ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $attributes['redirect'] ); ?>">
				<?php } ?>
			</div>
			<div class="login-submit form-group">
				<input class="btn btn-default" type="submit" value="<?php _e( 'Log in', 'personalize-login' ); ?>">
				<a class="btn btn-register" href="/<?php _e( 'register', 'personalize-login' );?>"><?php _e( 'Create account', 'personalize-login' );?></a>
				<a class="forgot-password-link" href="/<?php _e( 'password-lost', 'personalize-login' );?>">
					<?php _e( 'Forgot your password?', 'personalize-login' ); ?>
				</a>
			</div>
		</form>
	</div>

</div>
<?php else : ?>
	<div class="login-form-container">
		<form method="post" action="<?php echo wp_login_url(); ?>">
			<div class="login-username form-group">
				<label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?></label>
				<input type="text" name="log" id="user_login">
			</div>
			<div class="login-password form-group">
				<label for="user_pass"><?php _e( 'Password', 'personalize-login' ); ?></label>
				<input type="password" name="pwd" id="user_pass">
			</div>
			<div class="login-submit form-group">
				<input type="submit" value="<?php _e( 'Log in', 'personalize-login' ); ?>">
			</div>
		</form>
	</div>
<?php endif; ?>
