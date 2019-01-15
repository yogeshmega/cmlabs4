<?php
/*
Plugin Name: CM Labs Sales Portal Login Redirect
Plugin URI:
Description: Redirects sales portal visitors to main site login page
Version: 1.0
Author: Yannick Lefebvre
Author URI: http://ylefebvre.ca/

Copyright 2017 CM Labs
*/

function cmlabs_redirect_login_page(){

    // Store for checking if this page equals wp-login.php
    $page_viewed = basename( $_SERVER['REQUEST_URI'] );

    if( false !== strpos( $page_viewed, 'wp-login.php' ) ) {
        wp_redirect( 'https://www.cm-labs.com/login/?source=salesportal' );
        exit();
    }
}

add_action( 'init','cmlabs_redirect_login_page' );