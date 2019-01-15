<div id="register-form" class="widecolumn">
	<?php if ( $attributes['show_title'] ) : ?>
		<h1 class="h2"><?php _e( 'Sign up to Vortex studio', 'personalize-login' ); ?></h1>
	<?php endif; ?>
	<a rel="nofollow"
		href="<?php echo site_url(); ?>/login/?action=wordpress_social_authenticate&amp;mode=login&amp;provider=LinkedIn&amp;redirect_to=<?php echo site_url(); ?>/><?php _e( 'account', 'personalize-login' ); ?>"
		title="Log in with LinkedIn"
		class="btn btn-linked"
		data-provider="LinkedIn">
		<span class="icon-linkedin"></span><span><?php _e('Log in with LinkedIn', 'personalize-login'); ?></span>
	</a>
	<p class="text-center separator"><span><?php _e('or', 'personalize-login'); ?></span></p>


	<?php if ( count( $attributes['errors'] ) > 0 ) : ?>
		<?php foreach ( $attributes['errors'] as $error ) : ?>
			<p class="error has-error">
				<?php echo $error; ?>
			</p>
		<?php endforeach; ?>
	<?php endif; ?>

	<form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
		<input type="hidden" name="locale" value="<?php echo pll_current_language('locale'); ?>">
		<div class="form-row form-group">
			<label for="email"><?php _e( 'Email', 'personalize-login' ); ?></label>
			<input class="form-control" type="text" name="email" id="email">
		</div>

		<div class="form-row form-group">
			<label for="first_name"><?php _e( 'First name', 'personalize-login' ); ?></label>
			<input class="form-control" type="text" name="first_name" id="first-name">
		</div>

		<div class="form-row form-group">
			<label for="last_name"><?php _e( 'Last name', 'personalize-login' ); ?></label>
			<input class="form-control" type="text" name="last_name" id="last-name">
		</div>
		<div class="form-row form-group">
			<label for="user_company"><?php _e( 'Company', 'personalize-login' ); ?></label>
			<input class="form-control" type="text" name="user_company" id="user-company">
		</div>

		<!--
        <div class="form-row form-group">
			<label for="password"><?php _e( 'Password', 'personalize-login' ); ?></label>
			<input class="form-control" type="password" name="password" id="password">
		</div>

		<div class="form-row form-group">
			<label for="repeat_password"><?php _e( 'Repeat Password', 'personalize-login' ); ?></label>
			<input class="form-control" type="password" name="repeat_password" id="repeat_password">
		</div>
        -->

		<div class="form-row form-group">
			<?php _e( 'Note: Your password will be generated automatically and emailed to the address you specify above.', 'personalize-login' ); ?>
		</div>

		<!--<?php if ( $attributes['recaptcha_site_key'] ) : ?>
			<div class="recaptcha-container">
				<div class="g-recaptcha" data-sitekey="<?php echo $attributes['recaptcha_site_key']; ?>"></div>
			</div>
		<?php endif; ?>-->
		<div class="form-group">
			<div class="checkbox">
				<label>
					<input type="checkbox" value="">
					<?php _e( 'Subscribe to our newsletter', 'personalize-login' ); ?>
				</label>
			</div>
		</div>

		<div class="signup-submit form-group">
			<input class="btn btn-default btn-block" type="submit" name="submit" class="register-button" value="<?php _e( 'Register', 'personalize-login' ); ?>"/>
		</div>
		<p class="pull-right"> <a href="/<?php _e( 'login', 'personalize-login' ); ?>"><?php _e( 'Already have an account ?', 'personalize-login' ); ?></a></p>
	</form>
</div>