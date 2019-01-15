<?php
/*
Plugin Name: CM Labs Sticky Header
Plugin URI: 
Description: Allows sections to become sticky in browser
Version: 1.0
Author: CM Labs
Author URI: 
*/

add_action('wp_enqueue_scripts', 'cmlabs_enqueue_sticky_kit');

function cmlabs_enqueue_sticky_kit(){
   wp_enqueue_script('sticky', plugins_url( 'js/jquery.sticky.js', __FILE__ ), array('jquery') );
}

add_action('wp_head', 'cmlabs_header_sticky');

function cmlabs_header_sticky() {
	?>
	<script type="text/javascript">
		jQuery( document ).ready( function() {
			jQuery(".sticky_item").sticky({topSpacing:90});
		});		
	</script>
<?php
}
