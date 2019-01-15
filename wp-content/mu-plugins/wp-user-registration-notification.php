<?php

load_plugin_textdomain('wp-user-registration-notification', false, 'hpj-wp-user-registration-notification/languages' );

// Redefine user notification function
if ( ! function_exists ( 'wp_new_user_notification' ) ) :
    function wp_new_user_notification( $user_id, $deprecated = null, $notify = '' ) {

        global $wpdb, $wp_hasher;
        $user = get_userdata( $user_id );

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);


        $message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "<br><br>";
        $message .= sprintf(__('Username: %s'), $user_login) . "<br><br>";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "<br>";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);


        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        // Generate something random for a password reset key.
        $key = wp_generate_password( 20, false );

        /** This action is documented in wp-login.php */
        do_action( 'retrieve_password_key', $user->user_login, $key );

        // Now insert the key, hashed, into the DB.
        if ( empty( $wp_hasher ) ) {
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

        $switched_locale = switch_to_locale( get_user_locale( $user ) );
        $headers = array('Content-Type: text/html');
        $message  = '<h3 style="color:#4fa345;font-weight:700;text-decoration:none;font-size:24pt;font-family:Arial;">' . sprintf(__('Welcome to %s', 'wp-user-registration-notification'), get_option('blogname') . '</h3>' );
        $message .= '<p style="font-family:Arial;">' . __("Thank you for signing up. To activate your account and set your password, please confirm your email address by clicking on the link below.", 'wp-user-registration-notification') . "</p>";
        $message .= "<a href='" . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') ."'>" . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "</a>";
        $message .= '<p style="font-family:Arial;">' . sprintf(__('If you received this email by mistake, or did not create a Vortex Studio online account, please disregard this email.', 'wp-user-registration-notification')) . '</p>';

        wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message, $headers);
    }
endif;