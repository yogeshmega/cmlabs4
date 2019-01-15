<?php
/*
Plugin Name: CM Labs Resources Sorter
Plugin URI:  
Description: Checks the health of your WordPress install
Version:     1.0
Author:      CM Labs Simulation
Author URI:  http://www.cm-labs.com
*/


add_action( 'restrict_manage_posts', 'cml_filter_resources' );

function cml_filter_resources() {
	$screen = get_current_screen();
	global $wp_query;

	if ( $screen->post_type == 'resource' ) {
		wp_dropdown_categories(array(
			'show_option_all' => 'Show All Document Types',
			'taxonomy' => 'resource_type',
			'name' => 'resource_type',
			'orderby' => 'name',
			'selected' =>
				( isset( $wp_query->query['resource_type'] ) ?
					$wp_query->query['resource_type'] : '' ),
			'hierarchical' => false,
			'depth' => 3,
			'show_count' => false,
			'hide_empty' => true,
			)
		);
		
		wp_dropdown_categories(array(
			'show_option_all' => 'Show All Products',
			'taxonomy' => 'resource_product',
			'name' => 'resource_product',
			'orderby' => 'name',
			'selected' =>
				( isset( $wp_query->query['resource_product'] ) ?
					$wp_query->query['resource_product'] : '' ),
			'hierarchical' => false,
			'depth' => 3,
			'show_count' => false,
			'hide_empty' => true,
			)
		);
		
		wp_dropdown_categories(array(
			'show_option_all' => 'Show All Solutions',
			'taxonomy' => 'resource_solution',
			'name' => 'resource_solution',
			'orderby' => 'name',
			'selected' =>
				( isset( $wp_query->query['resource_solution'] ) ?
					$wp_query->query['resource_solution'] : '' ),
			'hierarchical' => false,
			'depth' => 3,
			'show_count' => false,
			'hide_empty' => true,
			)
		);
	} elseif ( $screen->post_type == 'new' ) {
		wp_dropdown_categories(array(
			'show_option_all' => 'Show All Categories',
			'taxonomy' => 'news-category',
			'name' => 'news-category',
			'orderby' => 'name',
			'selected' =>
				( isset( $wp_query->query['news-category'] ) ?
					$wp_query->query['news-category'] : '' ),
			'hierarchical' => false,
			'depth' => 3,
			'show_count' => false,
			'hide_empty' => true,
			)
		);
	}
}

add_filter( 'parse_query', 'cml_perform_resource_filtering' );

function cml_perform_resource_filtering( $query ) {
	$qv = &$query->query_vars;
	
	if ( !empty( $qv['resource_type'] ) && is_numeric( $qv['resource_type'] ) ) {	
		$term = get_term_by( 'id', $qv['resource_type'], 'resource_type' );
		$qv['resource_type'] = $term->slug;
	}
	
	if ( !empty( $qv['resource_product'] ) && is_numeric( $qv['resource_product'] ) ) {	
		$term = get_term_by( 'id', $qv['resource_product'], 'resource_product' );
		$qv['resource_product'] = $term->slug;
	}
	
	if ( !empty( $qv['resource_solution'] ) && is_numeric( $qv['resource_solution'] ) ) {	
		$term = get_term_by( 'id', $qv['resource_solution'], 'resource_solution' );
		$qv['resource_solution'] = $term->slug;
	}
	
	if ( !empty( $qv['news-category'] ) && is_numeric( $qv['news-category'] ) ) {	
		$term = get_term_by( 'id', $qv['news-category'], 'news-category' );
		$qv['news-category'] = $term->slug;
	}
}
