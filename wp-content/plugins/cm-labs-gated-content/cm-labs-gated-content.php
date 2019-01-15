<?php
/*
Plugin Name: CM Labs - Gated Content
Plugin URI:
Description: Declares a plugin that will be visible in the
WordPress admin interface
Version: 1.0
Author: Yannick Lefebvre - CM Labs
Author URI: http://cm-labs.com
License: GPLv2
*/

function cmlabs_gatedcontent_get_options() {
    $options = get_option( 'cmlabs_gatedcontent_options', array() );
    $new_options['replacement_text'] = 'You need to be logged in to view this content.';
    $new_options['login_accepted'] = true;
    $new_options['cookie_accepted'] = true;
    $merged_options = wp_parse_args( $options, $new_options );
    $compare_options = array_diff_key( $new_options, $options );
    if ( empty( $options ) || !empty( $compare_options ) ) {
	update_option( 'cmlabs_gatedcontent_options', $merged_options );
    }
    return $merged_options;
}

add_action( 'wp_enqueue_scripts', 'cmlabs_gatedcontent_queue_scripts' );

function cmlabs_gatedcontent_queue_scripts() {
    wp_enqueue_script( 'jquerycookie', plugins_url( 'js/js.cookie.js', __FILE__ ), array( 'jquery' ), false, false );
}

add_action( 'add_meta_boxes', 'cmlabs_gatedcontent_register_meta_box' );

function cmlabs_gatedcontent_register_meta_box() {
    add_meta_box( 'cmlabs_gatedcontent_control', 'Gated Content', 'cmlabs_gatedcontent_meta_box', 'resources', 'normal', 'high' );
}

function cmlabs_gatedcontent_meta_box( $post ) { ?>
    Require user login or form completion to show content <input id="contentgate" name="contentgate" type="checkbox" <?php checked( get_post_meta( $post->ID, 'content_gate', true ) ); ?>>
<?php }

add_action( 'save_post', 'cmlabs_gatedcontent_save_gatedcontent_status', 10, 2 );

function cmlabs_gatedcontent_save_gatedcontent_status( $resource_id, $resource ) {
    if ( 'resources' == $resource->post_type ) {
	if ( isset( $_POST['contentgate'] ) ) {
	    update_post_meta( $resource_id, 'content_gate', true );
	} else {
	    update_post_meta( $resource_id, 'content_gate', false );
	}
    }
}

add_filter( 'the_content', 'cmlabs_gatedcontent_content_filter' );

function cmlabs_gatedcontent_content_filter( $content ) {
    $gated_content = get_post_meta( get_the_ID(), 'content_gate', true );
    global $post;
       

    if ( $gated_content && in_the_loop() ) {
	$options = cmlabs_gatedcontent_get_options();
	
	if ( ( !$options['login_accepted'] && !$options['cookie_accepted'] ) ||
		( !$options['login_accepted'] && $options['cookie_accepted'] && !isset( $_COOKIE['gated-' . $post->post_name] ) ) ||
		( $options['login_accepted'] && !is_user_logged_in() && !$options['cookie_accepted'] ) ||
		( $options['login_accepted'] && !is_user_logged_in() && $options['cookie_accepted'] && !isset( $_COOKIE['gated-' . $post->post_name] ) ) 
		) {
	    $content = '';
	    $content = '<div class="gated-content">';
	    $content .= "<script type='text/javascript'>\n";
	    $content .= "/* <![CDATA[ */\n";
	    $content .= "function findGetParameter(parameterName) {\n";
	    $content .= "\tvar result = null,\n";
	    $content .= "\ttmp = [];\n";
	    $content .= "\tvar items = location.search.substr(1).split('&');\n";
	    $content .= "\tfor (var index = 0; index < items.length; index++) {\n";
	    $content .= "\ttmp = items[index].split('=');\n";
	    $content .= "\t\tif (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);\n";
	    $content .= "\t}\n";
	    $content .= "\treturn result;\n";
	    $content .= "}\n\n";
	    
	    $content .= "goog_snippet_vars = function() {\n";
	    $content .= "\tvar w = window;\n";
	    $content .= "\tw.google_conversion_id = 1032196280;\n";
	    $content .= "\tw.google_conversion_label = 'YLwnCKD8kXEQuKGY7AM'\n";
	    $content .= "\tw.google_remarketing_only = false;\n";
	    $content .= "}\n\n";
	    
	    $content .= "// DO NOT CHANGE THE CODE BELOW.\n";
	    $content .= "goog_report_conversion = function(url) {\n";
	    $content .= "\tgoog_snippet_vars();\n";
	    $content .= "\twindow.google_conversion_format = '3'\n";
	    $content .= "\tvar opt = new Object();\n";
	    $content .= "\topt.onload_callback = function() {\n";
	    $content .= "\t\tif (typeof(url) != 'undefined') {\n";
	    $content .= "\t\t\twindow.location = url;\n";
	    $content .= "\t\t}\n";
	    $content .= "\t}\n";
	    $content .= "\tvar conv_handler = window['google_trackConversion'];\n";
	    $content .= "\tif (typeof(conv_handler) == 'function') {\n";
	    $content .= "\t\tconv_handler(opt);\n";
	    $content .= "\t}\n";
	    $content .= "}\n";
	    $content .= "/* ]]> */\n";
	    $content .= "</script>\n\n";
	    
	    $content .= "<script type='text/javascript' src='//www.googleadservices.com/pagead/conversion_async.js'>\n";
	    $content .= "</script>\n";
	    
	    $content .= do_shortcode( nl2br ( stripslashes( $options['replacement_text'] ) ) );
	    $content = str_replace( '[login_page_url]', '/login?redirect_to=' . get_permalink(), $content );
	       
	    $content .= "<script>\n";
	    $content .= "jQuery('input[name=source_additional]').val('" . get_permalink() . "');\n";
	    $content .= "function setGatedContentCookie() {\n";
	    $content .= "Cookies.set( 'gated-" . $post->post_name . "', '1', {expires: 365} );";
	    $content .= "window.location.replace('" . get_permalink() . "');";
	    $content .= "}\n";
	    $content .= "</script>";
	    $content .= "</div>";
	} else {
	    if ( $options['login_accepted'] && is_user_logged_in() && in_the_loop() ) {
		$user_id = get_current_user_id();
		$gated_content_views = get_user_meta( $user_id, 'gated-' . $post->post_name, true );
		if ( empty( $gated_content_views ) ) {
		    $gated_content_views = 0;
		}
		$gated_content_views = $gated_content_views + 1;
		update_user_meta( $user_id, 'gated-' . $post->post_name, $gated_content_views );
		update_user_meta( $user_id, 'gated-' . $post->post_name . '-last-visit-date', date( 'm/d/Y', time() ) );
	    }
	}	
    }
    
    return $content;
}

add_action( 'admin_menu', 'cmlabs_gatedcontent_admin_menu' );

function cmlabs_gatedcontent_admin_menu() {
    add_options_page( 'CM Labs Gated Content', 'CM Labs Gated Content', 'manage_options', 'cmlabs-gated-content', 'cmlabs_gatedcontent_configpage' );
}

function cmlabs_gatedcontent_configpage() {
    // Retrieve plugin configuration options from database
    $options = cmlabs_gatedcontent_get_options();
    ?>
    <div id="cmlabs-gatedcontent-general" class="wrap">
    <h2>CM Labs Gated Content</h2><br />
    <form method="post" action="admin-post.php">
	<input type="hidden" name="action" value="save_cmlabs_gatedcontent_options" />
    
	<!-- Adding security through hidden referrer field -->
	<?php wp_nonce_field( 'cmlabs-gatedcontent' ); ?>

	<table>
	    <tr>
		<td style="width: 300px; font-weight: bold;">Logged-in visitors can view content</td>
		<td><input id="login_accepted" name="login_accepted" type="checkbox" <?php checked( $options['login_accepted'] ); ?>></td>
	    </tr>
	    <tr>
		<td style="width: 300px; font-weight: bold;">Users with cookie from gate form can view content</td>
		<td><input id="cookie_accepted" name="cookie_accepted" type="checkbox" <?php checked( $options['cookie_accepted'] ); ?>></td>
	    </tr>
	</table>
	<h3>Text shown when no access</h3>
	<?php wp_editor( stripslashes( $options['replacement_text'] ), 'replacement_text' ); ?>
	<br /><br />
	<input type="submit" value="Submit" class="button-primary"/>
    </form>
</div>
<?php }

add_action( 'admin_init', 'cmlabs_gatedcontent_admin_init' );

function cmlabs_gatedcontent_admin_init() {
    add_action( 'admin_post_save_cmlabs_gatedcontent_options', 'process_cmlabs_gatedcontent_options' );
}

function process_cmlabs_gatedcontent_options() {
    // Check that user has proper security level
    if ( !current_user_can( 'manage_options' ) ) {
	wp_die( 'Not allowed' );
    }

    // Check if nonce field configuration form is present
    check_admin_referer( 'cmlabs-gatedcontent' );
    
    // Retrieve original plugin options array
    $options = cmlabs_gatedcontent_get_options();
    
    // Cycle through all text form fields and store their values
    // in the options array
    foreach ( array( 'replacement_text' ) as $option_name ) {
	if ( isset( $_POST[$option_name] ) ) {
	    $options[$option_name] = $_POST[$option_name];
	}
    }
    
    // Cycle through all check box form fields and set the options
    // array to true or false values based on presence of variables
    foreach ( array( 'login_accepted', 'cookie_accepted' ) as $option_name ) {
	if ( isset( $_POST[$option_name] ) ) {
	    $options[$option_name] = true;
	} else {
	    $options[$option_name] = false;
	}
    }
    
    // Store updated options array to database
    update_option( 'cmlabs_gatedcontent_options', $options );
    
    // Redirect the page to the configuration form
    wp_redirect( add_query_arg( 'page', 'cmlabs-gated-content', admin_url( 'options-general.php' ) ) );
    exit;
}