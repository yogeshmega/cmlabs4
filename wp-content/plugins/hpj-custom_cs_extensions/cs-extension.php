<?php

/*
Plugin Name: Custom cs extensions
Plugin URI:
Description: hpj
Author:
Author URI:
Version: 0.1.0
*/

define( 'MY_EXTENSION_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_EXTENSION_URL', plugin_dir_url( __FILE__ ) );

add_action( 'wp_enqueue_scripts', 'my_extension_enqueue' );
add_action( 'cornerstone_register_elements', 'my_extension_register_elements' );
add_filter( 'cornerstone_icon_map', 'my_extension_icon_map' );

function my_extension_register_elements() {

	cornerstone_register_element( 'CustomFeatureBox', 'custom-feature', MY_EXTENSION_PATH . 'includes/custom-feature' );
	cornerstone_register_element( 'CustomSolutionBox', 'custom-solution', MY_EXTENSION_PATH . 'includes/custom-solution' );
	cornerstone_register_element( 'CustomSlider', 'custom-slider', MY_EXTENSION_PATH . 'includes/custom-slider' );
	cornerstone_register_element( 'CustomSliderItem', 'custom-slider-item', MY_EXTENSION_PATH . 'includes/custom-slider/custom-slider-item' );
	cornerstone_register_element( 'CustomFeatures', 'custom-features', MY_EXTENSION_PATH . 'includes/custom-features' );
	cornerstone_register_element( 'CustomFeatureItem', 'custom-feature-item', MY_EXTENSION_PATH . 'includes/custom-features/custom-feature-item' );
	cornerstone_register_element( 'CustomRessoursesBox', 'custom-ressources', MY_EXTENSION_PATH . 'includes/custom-ressources' );
	cornerstone_register_element( 'CustomProductBox', 'custom-product', MY_EXTENSION_PATH . 'includes/custom-product' );
	cornerstone_register_element( 'CustomSubFeatures', 'custom-subfeatures', MY_EXTENSION_PATH . 'includes/custom-subfeatures' );
	cornerstone_register_element( 'CustomSubFeatureItem', 'custom-subfeature-item', MY_EXTENSION_PATH . 'includes/custom-subfeatures/custom-subfeature-item' );
	cornerstone_register_element( 'CustomModules', 'custom-modules', MY_EXTENSION_PATH . 'includes/custom-modules' );
	cornerstone_register_element( 'CustomModuleItem', 'custom-modules-item', MY_EXTENSION_PATH . 'includes/custom-modules/custom-modules-item' );
	cornerstone_register_element( 'CustomHeaderSlider', 'header-slider', MY_EXTENSION_PATH . 'includes/header-slider' );
	cornerstone_register_element( 'CustomHeaderSliderItem', 'header-slider-item', MY_EXTENSION_PATH . 'includes/header-slider/header-slider-item' );
	cornerstone_register_element( 'CustomCTABox', 'custom-cta-box', MY_EXTENSION_PATH . 'includes/custom-cta-box' );
	cornerstone_register_element( 'CMLabsFeatureBox', 'cmlabs-feature-box', MY_EXTENSION_PATH . 'includes/cmlabs-feature-box' );	
}

function my_extension_enqueue() {
	wp_enqueue_style( 'my_extension-styles', MY_EXTENSION_URL . '/assets/styles/cs-extension.css', array(), '0.1.0' );
}

function my_extension_icon_map( $icon_map ) {
	$icon_map['cs-extension'] = MY_EXTENSION_URL . '/assets/svg/icons.svg';
	return $icon_map;
}
