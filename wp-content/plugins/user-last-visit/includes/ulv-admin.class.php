<?php
class Ulv_Admin
{
    /**
     * @var     str $nonce, nonce used in admin panel form
     */
    private $nonce;
    
    // Instance of this class.
    protected static $instance = null;
    
    /**
     * Constructor (private in order to keep the uniqueness)
     *
     * @since   0.8
     */
    private function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'init', array( $this, 'init' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_print_scripts', array( $this, 'print_scripts' ) );
		add_filter( 'manage_users_columns', array($this, 'set_users_columns') );
		add_filter( 'manage_users_custom_column', array( $this, 'users_columns') , 6, 3 );
    }
    
    /**
     * Add custom column on the "user.php" page
     *
     * @param   arr $columns, array of column name
     * @return  arr $columns, the modified column array
     *
     * @since   0.8
     */
    public function set_users_columns( $columns ) {
        $columns['last_visit'] = __( 'Last Visit', 'ulv' );
        return $columns;
    }
    
    /**
     * Display of users custom column content
     *
     * @param   str $output, the custom column column content
     * @param   str $name, the current column
     * @param   int $user_id, the user ID
     *
     * @since   0.8
     */
    public function users_columns( $output, $name, $user_id ) {
        if ( 'last_visit' == $name ) {
            $output = '';
            $last_visit = user_last_visit( $user_id );
            if ( false !== $last_visit ) {
                $output .= $last_visit;
            } else {
                $output .= __( 'Unknown', 'ulv' );
            }
            $user = get_user_by( 'id', $user_id );
            $options = Ulv_Public::get_instance()->options();
            if ( ! $options['enabled'] ) {
                $output .= '<br /><span style="color:red;">' . __( 'record disabled', 'ulv' ) . '</span>';
                return $output;
            }
            foreach ( $user->roles as $role ) {
                if ( in_array( $role, $options['exclude_by_role'] ) ) {
                    $output .= '<br /><span style="color:red;">' . __( 'excluded (role)', 'ulv' ) . '</span>';
                    return $output;
                }
            }
            if ( in_array( strval( $user_id ), $options['exclude_by_id'] ) ) {
                    $output .= '<br /><span style="color:red;">' . __( 'excluded (individual)', 'ulv' ) . '</span>';
                    return $output;
            }
            do_action( 'ulv-custom-columns-output', $output, $user_id );
            return $output;
        }
    }
    
    /**
     * Print script in admin page
     *
     * @since   0.8
     */
    public function print_scripts() {
        global $ulv;
        if ( $ulv['admin_page_hk'] == get_current_screen()->id ) {
            $all_users = get_users( array( 'fields' => array( 'user_login' ) ) );
            
            $logins = array();
            foreach ( $all_users as $user) {
                $logins[] = $user->user_login;
            }            
            ?>
            <script type="text/javascript">
            /* <![CDATA[ */
                ;var ulvAllLogins = <?php echo json_encode( $logins ); ?>;
            /* ]]> */
            </script>
            <?php
        }
    }
    
    /**
     * Enqueue scripts
     *
     * @since   0.8
     */
    public function enqueue_scripts() {
        global $ulv;
        if ( $ulv['admin_page_hk'] == get_current_screen()->id ) {
            wp_register_script( 'ulv-admin-js', ULV_URL . 'assets/js/admin-page.js', array( 'jquery-ui-autocomplete' ),  ULV_VERSION );
            
            $translation = array(
                'exclude' => __( 'exclude', 'ulv' ),
                'cancel' => __( 'cancel', 'ulv' ),
                'ajaxNonce' => wp_create_nonce( 'ulv_ajax_nonce' ),
            );
            
            wp_localize_script( 'ulv-admin-js', 'ulvSettingsText', $translation );
            
            wp_enqueue_script( 'ulv-admin-js' );
            wp_enqueue_style( 'ulv-admin-css', ULV_URL . 'assets/css/admin-page.css', array(),  ULV_VERSION );
        }
    }
    
    /**
     * Init function
     *
     * @since   0.8
     */
    public function init() {
        if ( isset( $_POST['ulv_admin_nonce'] ) ) {
			if ( 1 == wp_verify_nonce( $_POST['ulv_admin_nonce'], 'ulv_admin_nonce' ) ) {
                $go = $this->form_treatment();
                unset( $_POST );
                $this->refresh_page( $go );
                die();
            }
        }
        $this->nonce = wp_create_nonce( 'ulv_admin_nonce' );
    }
    
    /**
     * Form submission
     *
     * @since   0.8
     */
    public function form_treatment() {
        $form_name = stripslashes( $_POST['form-name'] );
        switch ( $form_name ) {
            case 'ulv-settings' :
                $options = Ulv_Public::get_instance()->options();
                if ( isset( $_POST['enable-record'] ) ) {
                    $options['enabled'] = true;
                } else {
                    $options['enabled'] = false;
                }
                if ( isset( $_POST['exclude-backend'] ) ) {
                    $options['exclude_backend'] = true;
                } else {
                    $options['exclude_backend'] = false;
                }
                if ( isset( $_POST['exclude-by-role'] ) ) {
                    $options['exclude_by_role'] = wp_unslash( $_POST['exclude-by-role'] );
                } else {
                    $options['exclude_by_role'] = array();
                }
                if ( isset( $_POST['exclude-by-id'] ) ) {
                    $options['exclude_by_id'] = wp_unslash( $_POST['exclude-by-id'] );
                } else {
                    $options['exclude_by_id'] = array();
                }
                $save_fields = array( 'enabled', 'exclude_backend','exclude_by_role', 'exclude_by_id' );
                $save_values = array( $options['enabled'], $options['exclude_backend'], $options['exclude_by_role'], $options['exclude_by_id'] );
                
                Ulv_Public::get_instance()->set_options($save_fields, $save_values);
                
                $running = $_SERVER['PHP_SELF'];
                if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
                    $running .= '?' . $_SERVER['QUERY_STRING'];
                }
                return;
                break;
        }
    }
    
	/**
	 * Refresh/redirect
     *
     * @param   str $go, where to go after form treatment, or just refresh the page
     *
     * @since   0.8
	 */
	private function refresh_page( $go = null ) {
		if ( ! empty( $go ) ) {
			wp_redirect( $go );
            die();
		}
        
		$running = $_SERVER['PHP_SELF'];
		if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$running .= '?' . $_SERVER['QUERY_STRING'];
		}
		wp_redirect( site_url( $running ) );
		die();
    }
    
    /**
     * Add menu and page to the admin panel
     *
     * @since   0.8
     */
    public function admin_menu() {
        global $ulv;
        $ulv['admin_page_hk'] = add_submenu_page(
            'options-general.php',
            __( 'User Last Visit', 'ulv' ),
            __( 'User Last Visit', 'ulv' ),
            'manage_options',
            'ulv_admin_page',
            array( $this, 'admin_page_cb' )
        );
    }
    
    /**
     * Callback that draws the admin page
     *
     * @since   0.8
     */
    public function admin_page_cb() {
        include ULV_PATH . 'views/admin-page.php';
    }
    
    /**
     * Return the unique instance of this class
     *
     * @since   0.8.
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
