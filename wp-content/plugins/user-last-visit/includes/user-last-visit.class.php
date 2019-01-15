<?php
class User_Last_Visit 
{
    // Instance of this class.
    protected static $instance = null;
    
    /**
     * Constructor (private in order to keep the uniqueness)
     *
     * @since   0.8
     */
    private function __construct() {
        add_action( 'wp_loaded', array($this, 'wp_loaded' ) );
    }
    
    /**
     * Init function
     *
     * @since   0.8
     */
    public function wp_loaded() {
        if ( is_user_logged_in() ) {
            $record = true;
            $record = apply_filters( 'ulv-can-record', $record );
            if ( false !== $record ) {
                $user = wp_get_current_user();
                update_user_meta( $user->ID, ULV_META, strtotime( 'now' ) );
            }
        }
    }
    
    /**
     * Get the last visit timestamp
     *
     * @param   mixed $user_id, user id or "current
     * @return  mixed, the timestamp if $user_id is valid and the meta data exists. FALSE otherwise.
     * @since   0.8
     */
    public static function get_user_last_visit( $user_id = 'current' ) {
        if ( 'current' == $user_id ) {
            if ( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $meta = get_user_meta( $user->ID, ULV_META, true );
                return ( empty( $meta ) )? false : $meta;
            } else {
                return false;
            }
        } elseif ( is_int( $user_id ) ) {
            if ( 0 == $user_id ) return false;
            $meta = get_user_meta( $user_id, ULV_META, true );
            return ( empty( $meta ) )? false : $meta;
        }
        return false;
    }
    
    /**
     * Return the unique instance of this class.
     *
     * @return  obj $instance, the unique allowed instance of the class
     * @since   0.8
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
