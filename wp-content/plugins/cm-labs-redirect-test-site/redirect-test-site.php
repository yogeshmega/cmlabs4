<?php
/*
Plugin Name: CM Labs Redirect Test Site
Plugin URI:
Description: Redirects test site visitors to main site
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca/

Copyright 2017 CM Labs
*/

add_action( 'template_redirect', 'cmlabs_home_switcher_redirect');

function cmlabs_home_switcher_redirect() {

	if ( $_SERVER['SERVER_NAME'] == 'www-test.cm-labs.com' || $_SERVER['SERVER_NAME'] == 'www-sandbox.cm-labs.com' || $_SERVER['SERVER_NAME'] == 'www-test2.cm-labs.com' ) {
		if ( !is_user_logged_in() ) {
			wp_redirect( 'https://www.cm-labs.com' . $_SERVER['REQUEST_URI'], 301 );
		}
	}
}