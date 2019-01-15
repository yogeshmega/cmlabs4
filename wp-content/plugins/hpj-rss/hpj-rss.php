<?php
/*
Plugin Name: HPJ RSS
Plugin URI: http://www.agencehpj.com
Description: Custom RSS feed manager.
Version: 1.0
Author: Patrolie Mvoutoulou
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/hpj-config.php');             
include_once(__DIR__ . '/includes/dispatcher.php');

class Hpj_RSS {

    var $feeds = array();
    var $original_is_feed;
    
    public function __construct() {
        add_action('admin_menu', array($this, 'init_admin_menu'));
        
        add_action('init', array($this, 'init_custom_feed'));
    }
 
    public function init_admin_menu() {
        add_menu_page(__('HPJ RSS', HPJ_RSS_I18N_DOMAIN), __('HPJ RSS', HPJ_RSS_I18N_DOMAIN), 'manage_options', 'hpj-rss-general', array($this, 'admin_menu_general'));    
    }
    
    public function admin_menu_general() {
        Hpj_RSS_Dispatcher::dispatch('setting', 'general');    
    }
    
    public function init_custom_feed() {
        global $wp_rewrite;
        add_feed('feed/application', array($this, 'custom_feed'));
        $wp_rewrite->flush_rules();                  
    }
    
    public function custom_feed() {
        Hpj_RSS_Dispatcher::dispatch('feed', 'rss');
    }
    
}

new Hpj_RSS();
?>
