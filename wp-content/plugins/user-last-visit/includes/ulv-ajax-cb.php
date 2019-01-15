<?php
class Ulv_Ajax_Cb
{
    /**
     * Constructor
     *
     * @since   0.8
     */
    public function __construct() {
        add_action( 'wp_ajax_ulv_user_preview', array( $this, 'user_preview' ) );
    }
    
    /**
     * User preview
     *
     * @since   0.8
     */
    public function user_preview() {
        if ( $this->verify_nonce() ) {
            $user = get_user_by( 'login', stripslashes( $_POST['login'] ) );
            if ( $user ) {
                $avatar = get_avatar( $user->ID, 128 );
                ?>
                <div class="alignleft" style="style: margin: 10px;">
                    <?php echo $avatar; ?>
                </div>
                <table>
                    <tr>
                        <td><b><?php _e( 'Login', 'ulv' ); ?></b></td>
                        <td><?php echo $user->user_login; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php _e( 'Email', 'ulv' ); ?></b></td>
                        <td><?php echo ( empty( $user->user_email ) )? __( 'not defined', 'ulv' ) : $user->user_email; ?></td>
                    </tr>
                    <?php 
                        $all_roles = get_editable_roles(); 
                        $user_roles = array();
                        foreach ( $user->roles as $role ) {
                            if ( isset( $all_roles[$role] ) ) {
                                $user_roles[] = $all_roles[$role]['name'];
                            }
                        }
                        
                    ?>
                    <tr>
                        <td><b><?php _e( 'Role', 'ulv' ); ?></b></td>
                        <td><?php echo implode( ', ', $user_roles ); ?></td>
                    </tr>
                </table>
                <p class="submit clear">
                    <input type="submit" class="button button-seconday" id="add-by-id"
                    data-userid="<?php echo $user->ID; ?>"
                    data-userlogin="<?php echo esc_attr( $user->user_login ); ?>" 
                    value="<?php _e( 'Exclude', 'ulv' ); ?>"  />
                </p>
                <?php
            }
        }
        die();
    }
    
    /**
     * Verify nonce validity
     *
     * @since   0.8
     */
    private function verify_nonce() {
        if ( 1 === wp_verify_nonce( $_POST['nonce'], 'ulv_ajax_nonce' ) ) {
            return true;
        } else {
            return false;
        }
    }
}
new Ulv_Ajax_Cb;
