<?php
/**
 * User Last Visit
 *
 * @license   GPL-2.0+
 * @copyright 2015 Rija Rajaonah
 *
 * @wordpress-plugin
 * Plugin Name:       User Last Visit
 * Description:       Keep record of user last visit time based on user id and login status
 * Version:           1.0
 * Author:            Rija Rajaonah
 * Text Domain:       ulv
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
if ( !defined( 'WPINC' ) ) {
	die;
}
define( 'ULV_PATH', plugin_dir_path( __FILE__ ) );
define( 'ULV_URL', plugin_dir_url( __FILE__ ) );
define( 'ULV_META', 'last_visit' );
define( 'ULV_VERSION', '1.0' );

// require main logic class file
require_once ULV_PATH . 'includes/user-last-visit.class.php';
add_action( 'plugins_loaded', array( 'User_Last_Visit', 'get_instance' ) );

// require public utility functions
require_once ULV_PATH . 'includes/functions.php';

require_once ULV_PATH . 'includes/ulv-public.class.php';
Ulv_Public::get_instance();

if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    require_once ULV_PATH . 'includes/ulv-ajax-cb.php';
} else {
    if ( is_admin() ) {
        require_once ULV_PATH . 'includes/ulv-admin.class.php';
        Ulv_Admin::get_instance();
    }
}
