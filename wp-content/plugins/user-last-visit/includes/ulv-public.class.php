<?php
class Ulv_Public
{
    // Option name
    const OPT_NAME = 'ulv_options';
    
    /**
     * Plugin's options
     *
     * @var     arr $data
     *
     * @since   0.8
     */
    private $data;
    
    // The unique instance of this class.
    protected static $instance = null;
    
    // Default options
    protected $default_data = array(
        'enabled' => false,
        'exclude_backend' => true,
        'exclude_by_role' => array(),
        'exclude_by_id' => array(),
    );
    
    /**
     * Constructor (private in order to keep the uniqueness)
     *
     * @since   0.8
     */
    private function __construct() {
        $this->data = get_option( self::OPT_NAME, $this->default_data );
        add_filter( 'ulv-can-record', array( $this, 'record_filter' ), 10, 1 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
    }
    
    /**
     * Load text domain
     *
     * @since   0.8
     */
    public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ulv', false, basename( ULV_PATH ) . '/languages/' );
    }
    
    /**
     * Add a filter on record condition of last visit timestamp
     *
     * @param   bool $record, TRUE if record is still allowed at the time the function is called
     * @return  bool $record
     *
     * @since   0.8
     */
    public function record_filter( $record ) {
        if ( false === $record ) {
            // If something else has already denied the record, just comply with it
            return false;
        }
        
        if ( ! $this->data['enabled'] ) {
            // Recording is disabled
            return false;
        }
        
        if ( is_admin() && ! $this->data['exclude_backend'] ) {
            // exclude all administration pages
            return false;
        }
        
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            
            // exclude by role
            foreach ( $user->roles as $wp_role ) {
                if ( in_array( $wp_role, $this->data['exclude_by_role'] ) ) return false;
            }
            
            // exlude by id
            if ( in_array( strval( $user->ID ), $this->data['exclude_by_id'] ) ) return false;
        }
        return $record;
    }
    
	/**
	 * Get plugin's option
     *
     * @param   str $field, the name of option's field to return
     * @return  mixed, the option's field or the entire option if $field is empty
     * 
     * @since   0.8
	 */
	public function options( $field = '' ) {
        if ( empty( $field ) ) {
            return $this->data;
        } else {
            if ( isset( $this->data[ $field ] ) ) {
                return $this->data[ $field ];
            } else {
                return false;
            }
        }
    }
    
    /**
     * Set plugin's option
     *
     * @param   mixed $field, field name or array of field name of the option to set
     * @param   mixed $value, single or multiple values in array format of the fields
     *
     * @since   0.8
     */
    public function set_options( $field, $value ) {
        if ( ! isset( $field ) ) throw new Exception( 'Missing field name' );
        if ( is_array( $field ) ) {
            if ( is_array( $value ) && count( $value ) == count( $field ) ) {
                $combi = array_combine( $field, $value );
                $this->data = array_merge( $this->data, $combi );
                update_option( self::OPT_NAME, $this->data );
            }
        } else {
            $this->data[ $field ] = $value;
            update_option( self::OPT_NAME, $this->data );
        }
    }    
    
    /**
     * Return the unique instance of this class.
     *
     * @return  obj $instance, the unique allowed instance of the class
     *
     * @since   0.8
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
