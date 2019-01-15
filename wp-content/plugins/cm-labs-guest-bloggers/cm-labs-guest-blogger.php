<?php
/*
Plugin Name: CM Labs Guest Blogger
Plugin URI: 
Description: Creates simplified experience for guest bloggers
Version: 1.0
Author: CM Labs
Author URI: 
*/

function cmlabs_custom_menu_page_removing() {
	if (!current_user_can('manage_categories') && is_admin()) {
		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'cornerstone-home' );
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'edit.php?post_type=news' );
		remove_menu_page( 'edit.php?post_type=event' );		
		remove_menu_page( 'edit.php?post_type=resource' );
		remove_menu_page( 'edit.php?post_type=cmlabs_edition' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'profile.php' );
	}
    
}

function cmlabs_admin_bar_render() {
	global $wp_admin_bar;
	
	if (!current_user_can('manage_categories')) {	
		$wp_admin_bar->remove_menu('comments');
		$wp_admin_bar->remove_menu('wpseo-menu');
		$wp_admin_bar->remove_menu('languages');
		$wp_admin_bar->remove_menu('cs-main');
		$wp_admin_bar->remove_menu('new-resource');
		$wp_admin_bar->remove_menu('new-cmlabs_edition');
		$wp_admin_bar->remove_menu('new-event');
		$wp_admin_bar->remove_menu('new-news');
	}
}

function cmlabs_disable_screen_options( $show_screen ) {
    // Logic to allow admins to still access the menu
    if ( current_user_can( 'manage_categories' ) ) {
        return $show_screen;
    }
    return false;
}

function cmlabs_remove_dashboard_meta() {

	if (!current_user_can('manage_categories') && is_admin()) {	
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Removes the 'incoming links' widget
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Removes the 'plugins' widget
		remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'WordPress News' widget
		remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); //Removes the secondary widget
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Removes the 'Quick Draft' widget
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Removes the 'Recent Drafts' widget
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
		remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
		remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
		
		remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox		
	}
	
	add_filter( 'manage_posts_columns', 'cmlabs_columns_head', 100, 2 );
	add_filter( 'manage_edit-post_columns', 'cmlabs_columns_head', 100, 2 );
	add_filter( 'manage_post_posts_columns', 'cmlabs_columns_head', 100, 2 );
}

function cmlabs_remove_metabox() {
    if (!current_user_can('manage_categories') && is_admin()) {
        remove_meta_box( 'wpseo_meta','post','normal' ); // Tags Metabox
		remove_meta_box( 'ml_box', 'post', 'side' ); //Removes the 'Activity' widget (since 3.8)
		remove_meta_box( 'customsidebars-mb','post','side' ); // Tags Metabox
	}
}

function cmlabs_remove_formats()
{
   remove_theme_support('post-formats');
}

function cmlabs_columns_head( $columns ) {
	if (!current_user_can('manage_categories') && is_admin()) {	
		unset( $columns['comments'] );
		unset( $columns['tags'] );
		unset( $columns['cs_replacement'] );
		unset( $columns['wpseo-score'] );
		unset( $columns['wpseo-score-readability'] );
		unset( $columns['language_fr'] );
		unset( $columns['language_en'] );
	}
	return $columns;
}

function cmlabs_dequeue_script() {
	if (!current_user_can('manage_categories') && is_admin()) {	
		wp_dequeue_script( 'yoast-seo-post-scraper' );
		wp_deregister_script( 'yoast-seo-post-scraper' );
		wp_dequeue_script( 'yoast-seo-replacevar-plugin' );		
		wp_deregister_script( 'yoast-seo-replacevar-plugin' );		
		wp_dequeue_script( 'yoast-seo-shortcode-plugin' );		
		wp_deregister_script( 'yoast-seo-shortcode-plugin' );	
		wp_dequeue_script( 'yoast-seo-featured-image' );		
		wp_deregister_script( 'yoast-seo-featured-image' );		
	}
}

if ( is_admin() ) {
	add_action( 'admin_menu', 'cmlabs_custom_menu_page_removing', 20 );
	add_filter( 'screen_options_show_screen', 'cmlabs_disable_screen_options' );
	add_action( 'add_meta_boxes', 'cmlabs_remove_metabox', 20 );
	add_action( 'admin_init', 'cmlabs_remove_dashboard_meta', 1000 );
	add_action( 'after_setup_theme', 'cmlabs_remove_formats', 100 );
}

add_action( 'wp_before_admin_bar_render', 'cmlabs_admin_bar_render' );
add_action( 'wp_print_scripts', 'cmlabs_dequeue_script', 1000 );
