<div id="post-<?php the_ID(); ?>">
    <div class="entry-content entry">
        <?php if ( !is_user_logged_in() ) : ?>
            <p class="warning">
                <?php _e('You must be logged in to edit your profile.', HPJ_CMLABS_I18N_DOMAIN ); ?>
            </p><!-- .warning -->
        <?php else : ?>
			<p><?php _e( 'Your account allows you to download Vortex Studio installation files, manage your profile, manage your licenses and exchange ideas with other Vortex Studio users on our community forums.<br /><br />If this is your first time visiting this page, here are a few of the things you can do:', HPJ_CMLABS_I18N_DOMAIN ); ?></p>
			<h4><?php _e( 'Get your free copy of Vortex Studio Essentials', HPJ_CMLABS_I18N_DOMAIN ); ?></h4>
			<p><?php _e( 'Looking for Vortex Studio Essentials?', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="ftp://vortexstudio:9WuWQhg34T3n@ftp.cm-labs.com/2018b/Vortex_Studio_2018.3.0.42_x64_vc14.msi"><?php _e( 'Click here to download your copy', HPJ_CMLABS_I18N_DOMAIN ); ?></a>, <?php _e( 'then get your free activation key on the', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="/licenses/"><?php _e( 'Licenses Page', HPJ_CMLABS_I18N_DOMAIN ); ?></a>.</p>
			<h4><?php _e( 'Register an activation key', HPJ_CMLABS_I18N_DOMAIN ); ?></h4>
			<p><?php _e( 'If you have purchased Vortex Studio and received an activation key by email, you can', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="/licenses/"><?php _e( 'register it to your account using the Licenses page', HPJ_CMLABS_I18N_DOMAIN ); ?></a>.</p>
			<h4><?php _e( 'Download Vortex Studio', HPJ_CMLABS_I18N_DOMAIN ); ?></h4>
			<p><a href="/downloads/"><?php _e( 'The Downloads page', HPJ_CMLABS_I18N_DOMAIN ); ?></a> <?php _e( 'has everything you need to get the latest version of Vortex Studio.', HPJ_CMLABS_I18N_DOMAIN ); ?></p>
			<h4><?php _e( 'Upgrade an existing license', HPJ_CMLABS_I18N_DOMAIN ); ?></h4>
			<p><?php _e( 'Already have a license for Vortex Software Solution 6.8 and earlier and an active maintenance and support contract?', HPJ_CMLABS_I18N_DOMAIN ); ?> <a href="/contact/licensing"><?php _e( 'Request an upgrade here', HPJ_CMLABS_I18N_DOMAIN ); ?>.</a></p>
		<?php endif; ?>
    </div><!-- .entry-content -->
</div><!-- .hentry .post -->
<script>
jQuery(document).ready(function($) {
 $(".form-control").focus(function(){
   $(this).parent().addClass("active");
  }).blur(function(){
    $(this).parent().not( ".form-password" ).removeClass("active");
  })
});
</script>
