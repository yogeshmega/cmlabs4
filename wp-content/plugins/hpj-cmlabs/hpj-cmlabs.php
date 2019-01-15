<?php
/*
Plugin Name: HPJ CMLabs
Plugin URI: http://www.agencehpj.com
Description: Front end user space.
Version: 1.0
Author: Patrolie Mvoutoulou
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );

include_once(__DIR__ . '/hpj-config.php');
include_once(__DIR__ . '/includes/helpers/notice.php');
include_once(__DIR__ . '/includes/helpers/form.php');
include_once(__DIR__ . '/includes/helpers/url.php');
include_once(__DIR__ . '/includes/dispatcher.php');

include_once(__DIR__ . '/includes/model/license.php');
include_once(__DIR__ . '/includes/model/product.php');
include_once(__DIR__ . '/includes/library/rlm/fulfillment.php'); 

class Hpj_CMLabs {

    public static $_errors = null;
    public static $_messages = null;
                
    public function __construct() {
        // Activation
        register_activation_hook(__FILE__, array($this, 'install'));
        add_action('plugins_loaded', array($this, 'upgrade'));
        add_action('admin_init', array($this, 'init_data'));
        
        // Notice
        add_action('wp_enqueue_scripts', array($this, 'init_notice'));
        add_action('admin_enqueue_scripts', array($this, 'init_notice'));
            
        // Hook
        add_action('init', array($this, 'init_custom_urls'));

        add_action('admin_init', array($this, 'restrict_access_administration'));

        add_action('template_redirect', array($this, 'restrict_access_specificpage'));

        add_action('admin_menu', array($this, 'init_admin_menu'));

        add_action('init', array($this, 'profile_page_save'));
        add_action('init', array($this, 'licenses_page_save'));
        add_action('init', array($this, 'activation_page_save'));
        add_action('init', array($this, 'contact_form_send'));
        add_action('init', array($this, 'contact_licensing_form_send'));
        add_action('init', array($this, 'download_page'));
        add_action('init', array($this, 'renew_license'));
        add_action('init', array($this, 'licenses_generate_page'));

        add_action('init', array($this, 'admin_submenu_downloads_save'));
        add_action('init', array($this, 'admin_submenu_applications_save'));
        add_action('init', array($this, 'delete_download'));
        add_action('init', array($this, 'delete_application'));
        
        // Pages
        add_shortcode('hpj_cmlabs_profile_page', array($this, 'profile_page'));
        add_shortcode('hpj_cmlabs_downloads_page', array($this, 'downloads_page'));
        add_shortcode('hpj_cmlabs_licenses_page', array($this, 'licenses_page'));
        add_shortcode('hpj_cmlabs_documentation_page', array($this, 'documentation_page'));
        add_shortcode('hpj_cmlabs_activation_page', array($this, 'activation_page'));
        add_shortcode('hpj_cmlabs_load_page', array($this, 'load_page'));
		
	add_action( 'template_redirect', array( $this, 'template_redirect' ), 20 );
		
	add_filter( 'wp_get_nav_menu_items', array( $this, 'cmlabs_exclude_menu_items' ), null, 3 );		
                                                                     
        // Add javascript files
        wp_enqueue_script( 'hpj_cmlabs_js_script', plugins_url('hpj-cmlabs') . '/includes/view/assets/js/script.js', array('jquery'));

        add_action( 'admin_enqueue_scripts', array($this, 'init_admin_script') );
        
        // Wordpress Social Login
        add_action('wsl_process_login_update_wsl_user_data_start', array($this, 'update_user_from_wsl_provider'), 10, 6);
	
        // Add css files
                                                                   
        // Add field                                               
        //add_user_meta( $user_id, $meta_key, $meta_value, $unique );
        
        // Add filter
        add_filter('pll_pre_translation_url', array($this, 'init_translation_link'), 10, 3);
        
        add_action( 'init', array( $this, 'hpj_smlabs_plugin') );
		
	add_action( 'signup_header', array( $this, 'cmlabs_prevent_multisite_signup' ) );
		
	add_action( 'admin_post_hpj_cmlabs_staging_to_release', array( $this, 'cmlabs_staging_to_release' ) );
    }
    	
	function cmlabs_staging_to_release() {
		global $wpdb;
		
		$wpdb->update( $wpdb->prefix . 'hpj_cmlabs_application', array( 'version' => '2' ), array( 'version' => '1' ) );
		$wpdb->update( $wpdb->prefix . 'hpj_cmlabs_application', array( 'version' => '1' ), array( 'version' => '3' ) );

		wp_redirect( add_query_arg( array( 'page' => 'hpj-cmlabs-downloads', 'action' => 'application' ), admin_url( 'admin.php' ) ) );
	}
	
	function cmlabs_prevent_multisite_signup() {
		wp_redirect( site_url(), 301 );
		die();
	}
	
    public static function hpj_smlabs_plugin() {
		load_plugin_textdomain( HPJ_CMLABS_I18N_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}
    
    /**
    * create table, insert data
    */
    public function install() {
        global $wpdb;          

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                                       
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_activation_key (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `activation_key` varchar(50) NOT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_application (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `version` varchar(255) NOT NULL,
            `edition` varchar(255) NOT NULL,
            `published` int(11) NOT NULL,
            `cdate` datetime NOT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_download (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(250) NOT NULL,
            `link` varchar(250) NOT NULL,
            `size` varchar(255) DEFAULT NULL,
            `platform` varchar(255) DEFAULT NULL,
            `description` text,
            `requirement` text,
            `application_id` int(11) NOT NULL,
            `published` int(11) NOT NULL,
            `cdate` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_fulfillment (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `activation_key_id` int(11) NOT NULL,
            `host_id` varchar(250) NOT NULL,
            `license` text NOT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_license (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_product (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(250) NOT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';
        dbDelta( $sql );
            
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'hpj_cmlabs_user (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `rlm_user` varchar(250) DEFAULT NULL,
            `rlm_password` varchar(250) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ' . $charset_collate . ';';       
        dbDelta( $sql );
                                         
        add_option( "hpj_cmlabs_db_version", "1.1" );
    }
    
    public function upgrade() {
        global $wpdb;
        $dbVersion = get_option('hpj_cmlabs_db_version');
        if ($dbVersion == '1.0') {
            $downloadColumns = $wpdb->get_col( "DESC " . $wpdb->prefix . 'hpj_cmlabs_download', 0 );
            if (!in_array('requirement', $downloadColumns)) {
                $sql = 'ALTER TABLE `' . $wpdb->prefix . 'hpj_cmlabs_download` ADD COLUMN `requirement`  text NULL AFTER `description`';
                $wpdb->query( $sql );   
            }                       
            update_option( "hpj_cmlabs_db_version", "1.1" );
            $dbVersion = '1.1'; 
        }    
    }
    
    public function cm_labs_renew_user_license() {
	$activationKey = '';
	$hostId = '';
	$vortexEssentials = false;

	if ( isset( $_POST['activation_key'] ) && !empty( $_POST['activation_key'] ) && isset( $_POST['host_id'] ) && !empty( $_POST['host_id'] ) ) {
	    $activationKey = $_POST['activation_key'];
	    $hostId = $_POST['host_id'];

	    $licenseModel = new Hpj_CMLabs_License_Model();
	    $productModel = new Hpj_CMLabs_Product_Model();
	    $fulfillments = $licenseModel->getFulfillmentFromRLM( $activationKey, $hostId );
	    $products = $productModel->getProductFromRLM();

	    if ( !empty( $fulfillments ) ) {
		foreach ( $fulfillments as $fulfillment ) {		    
		    if (!empty($products) ) {
			foreach ($products as $product) {
			    if ($product->id == $fulfillment->product_id) {
				if ( false !== strpos( $product->name, 'Essentials' ) ) {
				    $vortexEssentials = true;
				    break(2);
				}				
			    }
			}
		    }
		}	
	    }
	}

	if ( $vortexEssentials ) {
	    $fulfillmentModel = new Hpj_CMLabs_RLM_Fulfillment();

	    if ( isset( $_POST['activation_key'] ) && !empty( $_POST['activation_key'] ) ) {
		$fulfillmentModel->deleteFulfillment( $activationKey );
	    }

	    if ( isset( $_POST['activation_key'] ) && !empty( $_POST['activation_key'] ) && isset( $_POST['host_id'] ) && !empty( $_POST['host_id'] ) ) {
		include_once(__DIR__ . '/includes/library/vendor/Httpful/Bootstrap.php');
		include_once(__DIR__ . '/includes/library/vendor/Httpful/Http.php');
		include_once(__DIR__ . '/includes/library/vendor/Httpful/Request.php');

		$request = \Httpful\Request::post(HPJ_CMLABS_RLM_URL_MANUAL_ACTIVATION);
		$response = $request->body('akey=' . urlencode($activationKey) . '&hostid=' . urlencode($hostId) . '&count=1')
		    ->send();
		$return = $response->body;
		#Hpj_CMLabs_RLM_Wrapper::log('Hpj_CMLabs_License_Model::getLicenseFile - response='.$return);
	    }
	    wp_redirect( '/licenses?renewed=1' );	    
	} else {
	    wp_redirect( '/licenses' );	    
	}	

	exit();
    }
    
    /*
    * Add hpj-cmlabs-page if not exist 
    */
    public function init_data() {
        global $wpdb;
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'posts WHERE post_name REGEXP "^hpj-cmlabs-page(-[0-9]+)?$" ORDER by ID ASC;');
        $data = array( 
            'post_author' => get_current_user_id(), 
            'post_content' => '[hpj_cmlabs_load_page]',
            'post_title' => 'HPJ-CMLABS-PAGE',
            'post_name' => 'hpj-cmlabs-page', 
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_type' => 'page',
            'page_template' => 'template-profile.php', 
        );
        if (empty($result)) {
            $pageId = wp_insert_post($data);
            // Check if polylang exist
            if (is_plugin_active('polylang/polylang.php')) {
                $translatePageId = wp_insert_post($data);
                pll_set_post_language($pageId, 'en_CA');
                pll_set_post_language($translatePageId, 'fr_CA');
                pll_save_post_translations(array('en_CA' => $pageId, 'fr_CA' => $translatePageId));
            }
            // Force post-name to have the same like the original
            $wpdb->update($wpdb->prefix . 'posts', array('post_name' => 'hpj-cmlabs-page'), array('ID' => $translatePageId), array('%s'), array( '%d' ));    
        } else {
            if (count($result) == 1) {
                $pageId = $result[0]->ID;
                $translatePageId = wp_insert_post($data);
                if (is_plugin_active('polylang/polylang.php')) {
                    pll_set_post_language($pageId, 'en_CA');
                    pll_set_post_language($translatePageId, 'fr_CA');
                    pll_save_post_translations(array('en_CA' => $pageId, 'fr_CA' => $translatePageId));
                }
                // Force post-name to have the same like the original
                $wpdb->update($wpdb->prefix . 'posts', array('post_name' => 'hpj-cmlabs-page'), array('ID' => $translatePageId), array('%s'), array( '%d' ));
            }
        }    
    }
    
    public function init_translation_link($a, $language, $pageId) {
        global $wp;
        $page = get_page($pageId);
        $url = null;
        if (!empty($page) && $page->post_name == 'hpj-cmlabs-page') {
            $url = $wp->request;
            $pageId = Hpj_CMLabs_Url::getRightUrl($url);
            if (!empty($pageId)) {
                $lang = get_bloginfo("language");
                $url = Hpj_CMLabs_Url::getUrlByPageIdAndLang($pageId, $language->slug);
                if (!empty($url) && trim($url) != '') {
                    $url = $url;
                }
            }
        }
        return $url;                      
    }

    public function init_custom_urls() {
        global $wp_rewrite;
        //add_rewrite_rule( '^profile$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/compte|account)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
	add_rewrite_rule( '^(fr/compte/profil|account/profile)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/licences|licenses)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/licences/activation|licenses/activation)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/licences/telechargement|licenses/download)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        //add_rewrite_rule( '^(fr/licences/generer|en/licenses/generate)', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/telechargement|downloads)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/documentation|documentation)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/contact/vente|contact/sale)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/contact/support|contact/support)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/contact/licence|contact/licensing)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        add_rewrite_rule( '^(fr/contact/renouvellement|contact/renew)$', 'index.php?pagename=hpj-cmlabs-page', 'top' );
        //var_dump($wp_rewrite);die;
        //$wp_rewrite->flush_rules();
    }
	
	public function template_redirect() {
	    global $wp; 

        $url = $wp->request;
        $pageId = Hpj_CMLabs_Url::getRightUrl($url);
	
	if ( isset( $_POST['action'] ) && !empty( $_POST['action'] ) && 'delete_rlm_fulfillment' == $_POST['action'] ) {
            $this->cm_labs_renew_user_license();
            return '';
        } 
			
	$title = null;
        if (!empty($pageId)) {
			ob_start();
            switch ($pageId) {
				case HPJ_CMLABS_PAGE_LICENSES_DOWNLOAD:
                    $this->download_page(); 
					$content = ob_get_clean();

					// Display title
					$this->displayTitle($title);
					
					// Display notice
					$this->display_notice(self::$_messages, self::$_errors);
					
					echo $content;
					
					exit();                        
                break;	
				case HPJ_CMLABS_PAGE_SALES:
					wp_redirect( 'https://sales.cm-labs.com', 301 );
					exit();
				break;
			}			
		}
		
		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$site_referrer = $_SERVER['HTTP_REFERER'];
			$parsed_url = parse_url( $site_referrer );
			if ( !empty( $parsed_url['query'] ) ) {
				$fd_redirect_pos = strpos( $parsed_url['query'], 'fd_redirect_to' );
				if ( $fd_redirect_pos !== false ) {
					wp_redirect( 'http://support.cm-labs.com/support/tickets', 301 );
					exit();
				}
			}			
		}
	}
     
    public function load_page() {
        global $wp;
	
	global $current_user;
	$essentialList = array();
	$runtimeList = array();
	$premiumList = array();
	$otherList = array();
	$profile_completed = 'none';

	$licenseModel = new Hpj_CMLabs_License_Model();
	$productModel = new Hpj_CMLabs_Product_Model();
	$userActivationKeys = $licenseModel->getUserActivationKeysFromDB($current_user->ID);
	if (!empty($userActivationKeys)) {
	    $activationKeys = $licenseModel->getActivationKeyFromRLM($userActivationKeys);
	    $fulfillments = $licenseModel->getFulfillmentFromRLM($userActivationKeys);
	    $products = $productModel->getProductFromRLM();

	    foreach ($activationKeys as &$activationKey) {
		$activationKey->fulfillments = array();
		$activationKey->product = null;
		$productName = null;
		if (!empty($fulfillments)) {
		    foreach ($fulfillments as $fulfillment) {
			if ($activationKey->akey == $fulfillment->akey) {
			    $activationKey->fulfillments[] = $fulfillment;    
			}    
		    }
		}
		if (!empty($products)) {
		    foreach ($products as $product) {
			if ($product->id == $activationKey->product_id) {
			    $activationKey->product = $product;
			    $productName = $product->name;
			    break;
			}
		    }
		}
		if (!empty($productName)) {
		    if (preg_match('/essential/', strtolower($productName), $match)) {
			$essentialList[] = $activationKey;    
		    } else if (preg_match('/(team|solo|academic|cad|demo)/', strtolower($productName), $match)) {
				$premiumList[] = $activationKey;
				$found = true;

		    } else if (preg_match('/runtime|player/', strtolower($productName), $match)) {
			$runtimeList[] = $activationKey;    
		    } else {
			$otherList[] = $activationKey;
		    }
		}                               
	    }
	}
	
	$expiring_essential_key = false;
	
	if ( !empty( $essentialList ) ) {
	    foreach ( $essentialList as $essentialItem ) {
		if ( !empty( $essentialItem->fulfillments ) ) {
		    foreach ( $essentialItem->fulfillments as $fulfillItem ) {
			$date1 = new DateTime( date( 'Y-m-d', time() ) );
			$date2 = new DateTime( $fulfillItem->expdate );			
			$interval = $date1->diff($date2);
			if ( $interval->invert || ( !$interval->invert && $interval->days < 14 ) ) {
			    $expiring_essential_key = true;
			    continue(2);			    
			}
		    }
		}
	    }
	}
	
	if ( false == $expiring_essential_key )	{
	    $essentialList = array();
	}	
	
	if ( empty( $premiumList ) && empty( $runtimeList ) && empty( $otherList ) && empty( $essentialList ) ) {
	    $profile_completed = 'nolicenses';
	} elseif ( !empty( $premiumList ) || !empty( $runtimeList ) || !empty( $otherList ) ) {
	    $profile_completed = 'premium';
	} elseif ( !empty( $essentialList ) && empty( $premiumList ) && empty( $runtimeList ) && empty( $otherList ) ) {
	    if ( get_user_meta( $current_user->ID, 'profile_complete', true ) ) {
		$profile_completed = 'essentials_complete';
	    } else {
		$profile_completed = 'essentials_incomplete';
	    }
	}
        
        $url = $wp->request;
        $pageId = Hpj_CMLabs_Url::getRightUrl($url);
		
        $title = null;
        if (!empty($pageId)) {
            ob_start();
            switch ($pageId) {
                //case HPJ_CMLABS_URL_PROFILE:
		case HPJ_CMLABS_PAGE_ACCOUNT_WELCOME:
                    $this->welcome_page();                                       
                    $title = __('Welcome to Your Vortex Studio Account', HPJ_CMLABS_I18N_DOMAIN);           
                break;
                case HPJ_CMLABS_PAGE_ACCOUNT_PROFILE:
                    $this->profile_page();                                       
                    $title = __('My account', HPJ_CMLABS_I18N_DOMAIN);           
                break;
                case HPJ_CMLABS_PAGE_LICENSES:
		    if ( 'premium' == $profile_completed || 'essentials_complete' == $profile_completed || 'nolicenses' == $profile_completed ) {
			$this->licenses_page();
		    } else {
			wp_redirect( '/account/profile/?missing_items=1&incomplete_profile=1' );
		    }
                    
                    $title = __('Licenses', HPJ_CMLABS_I18N_DOMAIN);
                break;
                case HPJ_CMLABS_PAGE_LICENSES_ACTIVATION:
                    $this->activation_page();                         
                break;
                case HPJ_CMLABS_PAGE_LICENSES_DOWNLOAD:
                    $this->download_page();                         
                break;
                case HPJ_CMLABS_PAGE_DOWNLOADS:
                    $this->downloads_page();
                    $title = __('Downloads', HPJ_CMLABS_I18N_DOMAIN);
                break;
                case HPJ_CMLABS_PAGE_DOCUMENTATION:
                    $this->documentation_page();
                    $title = __('Documentation', HPJ_CMLABS_I18N_DOMAIN);
                break;
                case HPJ_CMLABS_PAGE_CONTACT_SALE:
                    $this->contact_sale_form();
                    $title = __('Request a new license', HPJ_CMLABS_I18N_DOMAIN);
                break;
                case HPJ_CMLABS_PAGE_CONTACT_SUPPORT:
                    $this->contact_support_form();
                    $title = __('Request a new license', HPJ_CMLABS_I18N_DOMAIN);
                break;
                case HPJ_CMLABS_PAGE_CONTACT_LICENSING:
                case HPJ_CMLABS_PAGE_CONTACT_RENEW:
                    $this->contact_licensing_form();
                    $title = __('Contact', HPJ_CMLABS_I18N_DOMAIN);
                break;
                default:
                
                break;
            }
            $content = ob_get_clean();

            // Display title
            $this->displayTitle($title);
            
            // Display notice
            $this->display_notice(self::$_messages, self::$_errors);
            
            echo $content;
        }
    }

    public function init_admin_script($hook) {
        if ($hook == 'hpj-cmlabs_page_hpj-cmlabs-downloads') {
            wp_enqueue_script( 'hpj_cmlabs_js_admin_script', plugins_url('hpj-cmlabs') . '/includes/view/assets/js/admin_script.js', array('jquery'));
        }
    }

    public function init_admin_menu() {
        add_menu_page(__('HPJ CMLabs', HPJ_CMLABS_I18N_DOMAIN), __('HPJ CMLabs', HPJ_CMLABS_I18N_DOMAIN), 'manage_options', 'hpj-cmlabs-general', array($this, 'admin_menu_general'));
        add_submenu_page( 'hpj-cmlabs-general', __('Downloads', HPJ_CMLABS_I18N_DOMAIN), __('Downloads', HPJ_CMLABS_I18N_DOMAIN), 'manage_options', 'hpj-cmlabs-downloads', array($this, 'admin_submenu_downloads'));
    }

    public function admin_menu_general() {
        Hpj_CMLabs_Dispatcher::dispatch('setting', 'general');
    }

    public function admin_submenu_downloads() {
        $this->display_notice(self::$_messages, self::$_errors, true);
        if (!empty($_GET['action']) && $_GET['action'] == 'download') {
            Hpj_CMLabs_Dispatcher::dispatch('setting', 'downloads');
        } else {
            Hpj_CMLabs_Dispatcher::dispatch('setting', 'applications');
        }
    }

    public function admin_submenu_downloads_save() {
        Hpj_CMLabs_Dispatcher::dispatch('setting', 'downloadsSave');
    }
    
    public function admin_submenu_applications_save() {
        Hpj_CMLabs_Dispatcher::dispatch('setting', 'applicationsSave');
    }

    public function delete_download() {
        Hpj_CMLabs_Dispatcher::dispatch('setting', 'deleteDownload');
    }
    
    public function delete_application() {
        Hpj_CMLabs_Dispatcher::dispatch('setting', 'deleteApplication');
    }

    public function init_notice() {
        self::$_messages = Hpj_CMLabs_Notice::getMessages();
        self::$_errors = Hpj_CMLabs_Notice::getErrors();
    }

    /**
    * block subcriber from access to backend
    */
    public function restrict_access_administration(){			
       if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX) ){
           if (!current_user_can('edit_posts') ) {
               wp_redirect( get_bloginfo('url') . '/account', 301 );
               exit();
           }
       }
    }

    /**
    * Redirect to login page if user not logged in
    */
    public function restrict_access_specificpage(){
        global $wp;
        // Current url
        $url = $wp->request;
        $pageId = Hpj_CMLabs_Url::getRightUrl($url);
        // Page to be protected
        
        $pagesUrls = array(
            //HPJ_CMLABS_URL_PROFILE,
            //HPJ_CMLABS_URL_PROFILE,
			HPJ_CMLABS_PAGE_ACCOUNT_WELCOME,
            HPJ_CMLABS_PAGE_ACCOUNT_PROFILE,
            HPJ_CMLABS_PAGE_LICENSES,
            HPJ_CMLABS_PAGE_LICENSES_ACTIVATION,
            HPJ_CMLABS_PAGE_LICENSES_DOWNLOAD,
            //HPJ_CMLABS_URL_LICENSES_GENERATE,
            HPJ_CMLABS_PAGE_DOWNLOADS,
            HPJ_CMLABS_PAGE_DOCUMENTATION,
            HPJ_CMLABS_PAGE_CONTACT_SALE,
            HPJ_CMLABS_PAGE_CONTACT_SUPPORT,
            //HPJ_CMLABS_URL_CONTACT_GETVORTEX,
            HPJ_CMLABS_PAGE_CONTACT_RENEW,
        );
        
        if (!empty($pageId)) {
            if (in_array($pageId, $pagesUrls)) {
                // Secured page aren't cached by the browser so back button issue doesn't happen
                header("Cache-Control: private, must-revalidate, max-age=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
                if (!is_user_logged_in()) {
                   wp_redirect( get_bloginfo('url') . '/login', 301 );
                   exit();
                }
            }
        }
    }

    /**
    * display profile page
    */
	public function welcome_page(){
        Hpj_CMLabs_Dispatcher::dispatch('welcome', 'welcome');
    }
	
    public function profile_page(){
        Hpj_CMLabs_Dispatcher::dispatch('user', 'profile');
    }

    public function profile_page_save() {
        Hpj_CMLabs_Dispatcher::dispatch('user', 'profileSave');
    }

    /**
    * display download page
    */
    public function downloads_page(){
        Hpj_CMLabs::display_notice(self::$_messages, self::$_errors);
        Hpj_CMLabs_Dispatcher::dispatch('download', 'list');
    }

    /**
    * display licenses page
    */
    public function licenses_page(){
        //Hpj_CMLabs::display_notice(self::$_messages, self::$_errors);
        Hpj_CMLabs_Dispatcher::dispatch('license', 'registrationActivationKeyForm');
        Hpj_CMLabs_Dispatcher::dispatch('license', 'list');
        Hpj_CMLabs_Dispatcher::dispatch('license', 'activationForm');
    }

    public function licenses_page_save() {
        Hpj_CMLabs_Dispatcher::dispatch('license', 'registrationActivationKeySave');
    }
    
    public function licenses_generate_page(){
        //Hpj_CMLabs::display_notice(self::$_messages, self::$_errors);
        //Hpj_CMLabs_Dispatcher::dispatch('license', 'registrationActivationKeyForm');
        Hpj_CMLabs_Dispatcher::dispatch('license', 'generateLicense');
    }

    /**
    * display documentation page
    */
    public function documentation_page(){
        //Hpj_CMLabs::display_notice(self::$_messages, self::$_errors);
        Hpj_CMLabs_Dispatcher::dispatch('documentation', 'list');
    }

    public function activation_page(){
        //Hpj_CMLabs_Dispatcher::dispatch('license', 'activationForm');
    }

    public function activation_page_save(){
        Hpj_CMLabs_Dispatcher::dispatch('license', 'activationSave');
    }

    public function renew_license(){
        Hpj_CMLabs_Dispatcher::dispatch('license', 'renewLicense');
    }

    public function download_page(){
        global $wp;
        $url = $wp->request;
		
        if (!empty($url) && $url == HPJ_CMLABS_URL_LICENSES_DOWNLOAD) {
            Hpj_CMLabs_Dispatcher::dispatch('license', 'download');
        }
    }

    public function contact_sale_form() {
        Hpj_CMLabs_Dispatcher::dispatch('contact', 'contactSaleForm');
    }

    public function contact_support_form() {
        Hpj_CMLabs_Dispatcher::dispatch('contact', 'contactSupportForm');
    }

    public function contact_form_send() {
        Hpj_CMLabs_Dispatcher::dispatch('contact', 'contactFormSend');
    }

    public function contact_licensing_form() {
        Hpj_CMLabs_Dispatcher::dispatch('contact', 'contactLicensingForm');
    }

    public function contact_licensing_form_send() {
        Hpj_CMLabs_Dispatcher::dispatch('contact', 'contactLicensingFormSend');
    }
    
    public function displayTitle($title) {
        if (!empty($title)) {
        ?>
            <h2><?php echo $title; ?></h2>
        <?php
        }
    }

    public function display_notice($infos, $errors, $admin = false) {
        $msg = array(
            'messages' => $infos,
            'errors' => $errors,
        );
        $class = array(
            'messages' => (!$admin) ? 'hpj_cmlabs_messages' : 'notice notice-success',
            'errors' => (!$admin) ? 'hpj_cmlabs_errors' : 'notice notice-error',
        );

        foreach ($msg as $key => $messages) {
            if (!empty($messages)) {
            ?>
                <p>
                    <div class='<?php echo $class[$key]; ?>'>
                        <ul class="list-unstyled">
                        <?php
                            foreach ($messages as $message) {
                            ?>
                                <li>
                                    <?= $message ?>
                                </li>
                            <?php
                            }
                        ?>
                        </ul>
                    </div>
                </p>
            <?php
            }
        }
    }
    
    /**
    * Get more data from auth provider(Linkedin, ...) when new user is added
    * 
    * @param mixed $is_new_user
    * @param mixed $user_id
    * @param mixed $provider
    * @param mixed $adapter
    * @param mixed $hybridauth_user_profile
    * @param mixed $wp_user
    */
    public function update_user_from_wsl_provider($is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile, $wp_user) {
        global $current_user;
        // Only if new user, $is_new_user = true
        if (!empty($user_id) && (int)$user_id && $is_new_user) {
            if (strtolower($provider) == 'linkedin') {
                try{
                    // Get location and positions from linkedin
                    $response = $adapter->adapter->api->profile('~:(id,location,positions)');
                    if( isset( $response['success'] ) && $response['success'] === TRUE ){
                        $data = @ new SimpleXMLElement( $response['linkedin'] );
                        if ( ! is_object( $data ) ){
                            throw new Exception( "User profile request failed! " . $provider . " returned an invalid data.", 6 );
                        }                           
                        $company = null;
                        $title = null;
                        $country = null;
                        
                        $countries = file_get_contents(__DIR__ . '/hpj-countries.json');
                        if ($countries) {
                            $countries = array_change_key_case(json_decode($countries, true), CASE_LOWER);
                            if (!empty($countries) && !empty($data->location) && !empty($data->location->country)) {
                                $countryCode = $data->location->country->code;
                                if (!empty($countryCode) && !empty($countries[strtolower($countryCode)])) {
                                    $country = $countries[strtolower($countryCode)];
                                }
                            }
                        }
                        if (!empty($data->positions) && !empty($data->positions->position)) {
                            $title = (!empty($data->positions->position->title)) ? $data->positions->position->title : '';
                            if (!empty($data->positions->position->company)) {
                                $company = (!empty($data->positions->position->company->name)) ? $data->positions->position->company->name : '';
                            }   
                        }
                        // Update user fields
                        update_user_meta($user_id, 'user_title', esc_attr($title));
                        update_user_meta($user_id, 'user_company', esc_attr($company));
                        update_user_meta($user_id, 'user_country', esc_attr($country));
                    } else {
                        throw new Exception( "User profile request failed! " . $provider . " returned an invalid response.", 6 );
                    }
                }
                catch( LinkedInException $e ){
                    throw new Exception( "User profile request failed! " . $provider . " returned an error: $e", 6 );
                }
            }
        }    
    }
	
	function cmlabs_exclude_menu_items( $items, $menu, $args ) {
		// Iterate over the items to search and destroy
		foreach ( $items as $key => $item ) {
			if ( ( $item->object_id == 12476 || $item->object_id == 12481 ) && !current_user_can_for_blog( 3, 'read' ) ) {
				unset( $items[$key] );
			}
		}

		return $items;
	}

}

new Hpj_CMLabs();