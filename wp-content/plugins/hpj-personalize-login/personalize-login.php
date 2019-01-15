<?php
/**
 * Plugin Name:       Personalize Login
 * Description:       A plugin that replaces the WordPress login flow with a custom page.
 * Version:           1.0.0
 * Text Domain:       personalize-login
 */

class Personalize_Login_Plugin {

	/**
	 * Initializes the plugin.
	 *
	 * To keep the initialization fast, only add filter and action
	 * hooks in the constructor.
	 */
	public function __construct() {

		// Redirects
		add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

		add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
		add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );

		// Handlers for form posting actions
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		// Other customizations
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

		// Setup
		add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js_to_footer' ) );
		// add_filter( 'admin_init' , array( $this, 'register_settings_fields' ) );

		// Shortcodes
		add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
		add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
		add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
		add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
		
		load_plugin_textdomain('personalize-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		add_action( 'init', array( $this, 'personalize_login') );

	}

	public static function personalize_login() {
		load_plugin_textdomain( 'personalize-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}

	/**
	 * Plugin activation hook.
	 *
	 * Creates all WordPress pages needed by the plugin.
	 */
	public static function plugin_activated() {
		// Information needed for creating the plugin's pages
		$page_definitions = array(
			'login' => array(
				'title' => __( 'Log in', 'personalize-login' ),
				'content' => '[custom-login-form]'
			),
			'account' => array(
				'title' => __( 'Your Account', 'personalize-login' ),
				'content' => '[account-info]'
			),
			'register' => array(
				'title' => __( 'Register', 'personalize-login' ),
				'content' => '[custom-register-form]'
			),
			'password-lost' => array(
				'title' => __( 'Forgot Your Password?', 'personalize-login' ),
				'content' => '[custom-password-lost-form]'
			),
			'password-reset' => array(
				'title' => __( 'Pick a New Password', 'personalize-login' ),
				'content' => '[custom-password-reset-form]'
			)
		);

		foreach ( $page_definitions as $slug => $page ) {
			// Check that the page doesn't exist already
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above
				wp_insert_post(
					array(
						'post_content'   => $page['content'],
						'post_name'      => $slug,
						'post_title'     => $page['title'],
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'ping_status'    => 'closed',
						'comment_status' => 'closed',
					)
				);
			}
		}
	}

	//
	// REDIRECT FUNCTIONS
	//

	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	public function redirect_to_custom_login() {
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			if ( is_user_logged_in() ) {
				//$this->redirect_logged_in_user();
				exit;
			}

			// The rest are redirected to the login page
			// $login_url = home_url( 'login' );
			// if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			// 	$login_url = add_query_arg( 'redirect_to', $_REQUEST['redirect_to'], $login_url );
			// }

			// if ( ! empty( $_REQUEST['checkemail'] ) ) {
			// 	$login_url = add_query_arg( 'checkemail', $_REQUEST['checkemail'], $login_url );
			// }

			// wp_redirect( $login_url );
			// exit;
		}
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {
		// Check if the earlier authenticate filter (most likely,
		// the default WordPress authentication) functions have found errors
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			if ( is_wp_error( $user ) ) {
				$error_codes = join( ',', $user->get_error_codes() );

				$login_url = home_url( 'login' );
				$login_url = add_query_arg( 'login', $error_codes, $login_url );

				wp_redirect( $login_url, 301 );
				exit;
			}
		}

		return $user;
	}

	/**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
		$redirect_url = admin_url();

		if ( ! isset( $user->ID ) ) {
			return $redirect_url;
		}
		
		if ( user_can( $user, 'manage_options' ) ) {
			// Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
			if ( $requested_redirect_to == '' ) {
				$redirect_url = admin_url();
			} else {
				$redirect_url = $redirect_to;
			}
		} else {
		    if ( isset( $_POST['redirect_to'] ) && !empty( $_POST['redirect_to'] ) ) {
			$redirect_url = esc_url( $_POST['redirect_to'] );
		    } else {
			// Non-admin users always go to their account page after login
			$redirect_url = home_url( 'account' );
		    }			
		}
		
		return wp_validate_redirect( $redirect_url, home_url() );
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
		if ( false !== strpos( $_SERVER['HTTP_REFERER'], '/fr/' ) ) {
			$redirect_url = home_url( 'fr/connexion?logged_out=true' );
		} else {
			$redirect_url = home_url( 'login?logged_out=true' );
		}
		wp_redirect( $redirect_url, 301 );
		exit;
	}

	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
		// if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		// 	if ( is_user_logged_in() ) {
		// 		$this->redirect_logged_in_user();
		// 	} else {
		// 		wp_redirect( home_url( 'register' ) );
		// 	}
		// 	exit;
		// }
	}

	/**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_custom_lostpassword() {
		// if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		// 	if ( is_user_logged_in() ) {
		// 		$this->redirect_logged_in_user();
		// 		exit;
		// 	}

		// 	wp_redirect( home_url( 'password-lost' ) );
		// 	exit;
		// }
	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_custom_password_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'login?login=expiredkey' ), 301 );
				} else {
					wp_redirect( home_url( 'login?login=invalidkey' ), 301 );
				}
				exit;
			}

			$redirect_url = home_url( 'password-reset' );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url, 301 );
			exit;
		}
	}


	//
	// FORM RENDERING SHORTCODES
	//

	/**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
     * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_login_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => true );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		}

		// Pass the redirect parameter to the WordPress login functionality: by default,
		// don't specify a redirect, but if a valid redirect URL has been passed as
		// request parameter, use it.
		$attributes['redirect'] = '';
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
		}
		
		if ( isset( $_GET['source'] ) && 'salesportal' == $_GET['source'] ) {
			$attributes['sales_portal'] = true;
		}

		// Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
			$error_codes = explode( ',', $_REQUEST['login'] );

			foreach ( $error_codes as $code ) {
				$errors []= $this->get_error_message( $code );
			}
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

		// Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );

		// Check if the user just requested a new password
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

		// Check if user just updated password
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
		$attributes['genereate']  = array( 'genereate' => false );
		

		// Render the login form using an external template
		return $this->get_template_html( 'login_form', $attributes );
	}

	/**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => true );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} elseif ( ! get_option( 'users_can_register' ) ) {
			return __( 'We\'re sorry, we cannot create your account right now. Please try again later.', 'personalize-login' );
		} else {
			// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['register-errors'] ) ) {
				$error_codes = explode( ',', $_REQUEST['register-errors'] );

				foreach ( $error_codes as $error_code ) {
					$attributes['errors'] []= $this->get_error_message( $error_code );
				}
			}

			// Retrieve recaptcha key
			$attributes['recaptcha_site_key'] = get_option( 'personalize-login-recaptcha-site-key', null );

			return $this->get_template_html( 'register_form', $attributes );
		}
	}

	/**
	 * A shortcode for rendering the form used to initiate the password reset.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_lost_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => true );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} else {
			// Retrieve possible errors from request parameters
			$attributes['errors'] = array();
			if ( isset( $_REQUEST['errors'] ) ) {
				$error_codes = explode( ',', $_REQUEST['errors'] );

				foreach ( $error_codes as $error_code ) {
					$attributes['errors'] []= $this->get_error_message( $error_code );
				}
			}

			return $this->get_template_html( 'password_lost_form', $attributes );
		}
	}

	/**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
		// Parse shortcode attributes
		$default_attributes = array( 'show_title' => true );
		$attributes = shortcode_atts( $default_attributes, $attributes );

		if ( is_user_logged_in() ) {
			return __( 'You are already signed in.', 'personalize-login' );
		} else {
			if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
				$attributes['login'] = $_REQUEST['login'];
				$attributes['key'] = $_REQUEST['key'];

				// Error messages
				$errors = array();
				if ( isset( $_REQUEST['error'] ) ) {
					$error_codes = explode( ',', $_REQUEST['error'] );

					foreach ( $error_codes as $code ) {
						$errors []= $this->get_error_message( $code );
					}
				}
				$attributes['errors'] = $errors;

				return $this->get_template_html( 'password_reset_form', $attributes );
			} else {
				return __( 'Invalid password reset link.', 'personalize-login' );
			}
		}
	}

	/**
	 * An action function used to include the reCAPTCHA JavaScript file
	 * at the end of the page.
	 */
	public function add_captcha_js_to_footer() {
		echo "<script src='https://www.google.com/recaptcha/api.js?hl=en'></script>";
	}

	/**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	 */
	private function get_template_html( $template_name, $attributes = null ) {
		if ( ! $attributes ) {
			$attributes = array();
		}

		ob_start();

		do_action( 'personalize_login_before_' . $template_name );

		require( 'templates/' . $template_name . '.php');

		do_action( 'personalize_login_after_' . $template_name );

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	//
	// ACTION HANDLERS FOR FORMS IN FLOW
	//

	/**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$redirect_url = home_url( __( 'register', 'personalize-login' ) );

			if ( ! get_option( 'users_can_register' ) ) {
				// Registration closed, display error
				$redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
			// } elseif ( ! $this->verify_recaptcha() ) {
			// 	// Recaptcha check failed, display error
			// 	$redirect_url = add_query_arg( 'register-errors', 'captcha', $redirect_url );
			// } elseif ( $_POST['password'] != $_POST['repeat_password'] ) {
			// 	// Passwords dont match
			// 	$redirect_url = add_query_arg( 'register-errors', 'passwords_not_matched', $redirect_url );
			} else {
				$email = $_POST['email'];
				// $password = $_POST['password'];

				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name = sanitize_text_field( $_POST['last_name'] );
				$user_company = sanitize_text_field( $_POST['user_company'] );
				$user_locale = sanitize_text_field( $_POST['locale'] );

				$result = $this->register_user( $email, $first_name, $last_name, $user_company );
				
				if ( is_wp_error( $result ) ) {
					// Parse errors into a string and append as parameter to redirect
					$errors = join( ',', $result->get_error_codes() );
					$redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
				} else {
					if ( !empty( $user_locale ) ) {
						update_user_meta( $result, 'locale', $user_locale );
					}

					// Success, redirect to login page.
					$redirect_url = home_url( __( 'login', 'personalize-login' ) );
					$redirect_url = add_query_arg( 'registered', $email, $redirect_url );
				}
			}

			wp_redirect( $redirect_url, 301 );
			exit;
		}
	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$errors = retrieve_password();
			if ( is_wp_error( $errors ) ) {
				// Errors found
				$redirect_url = home_url( 'password-lost' );
				$redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
			} else {
				// Email sent
				$redirect_url = home_url( __( 'login', 'personalize-login' ) );
				$redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
				if ( ! empty( $_REQUEST['redirect_to'] ) ) {
					$redirect_url = $_REQUEST['redirect_to'];
				}
			}

			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$rp_key = $_REQUEST['rp_key'];
			$rp_login = $_REQUEST['rp_login'];

			$user = check_password_reset_key( $rp_key, $rp_login );

			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					wp_redirect( home_url( 'login?login=expiredkey' ), 301 );
				} else {
					wp_redirect( home_url( 'login?login=invalidkey' ), 301 );
				}
				exit;
			}

			if ( isset( $_POST['pass1'] ) ) {
				if ( $_POST['pass1'] != $_POST['pass2'] ) {
					// Passwords don't match
					$redirect_url = home_url( 'password-reset' );

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

					wp_redirect( $redirect_url, 301 );
					exit;
				}

				if ( empty( $_POST['pass1'] ) ) {
					// Password is empty
					$redirect_url = home_url( 'password-reset' );

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

					wp_redirect( $redirect_url, 301 );
					exit;

				}
				
				if ( strlen( $_POST['pass1'] ) < 6 ) {
					$redirect_url = home_url( 'password-reset' );

					$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
					$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
					$redirect_url = add_query_arg( 'error', 'password_too_short', $redirect_url );

					wp_redirect( $redirect_url, 301 );
					exit;

				}

				// Parameter checks OK, reset password
				reset_password( $user, $_POST['pass1'] );
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				wp_redirect( home_url( __( 'account', 'personalize-login' ) ), 301 );
			} else {
				echo "Invalid request.";
			}

			exit;
		}
	}


	//
	// OTHER CUSTOMIZATIONS
	//

	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
		// Create new message
		$msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";
		$msg .= sprintf( __( 'You asked us to reset the password for your account %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
		$msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";
		$msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";
		$msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
		$msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";

		return $msg;
	}


	//
	// HELPER FUNCTIONS
	//

	/**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */


	private function register_user( $email, $first_name, $last_name, $user_company ) {
		$errors = new WP_Error();
		$user_company  = $_POST['user_company'];
		// $password = $_POST['password'];
		// $repeat_password = $_POST['repeat_password'];

		// Email address is used as both username and email. It is also the only
		// parameter we need to validate
		if ( ! is_email( $email ) ) {
			$errors->add( 'email', $this->get_error_message( 'email' ) );
			return $errors;
		}

		if ( username_exists( $email ) || email_exists( $email ) ) {
			$errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
			return $errors;
		}

		// if ( empty( $_POST['password'] ) ) {
		// 	// Password is empty
		// 	$errors->add( 'password_empty', $this->get_error_message( 'password_empty') );
		// 	return $errors;
		// }

		// if ( strlen( $_POST['password'] ) < 6 ) {
		// 	$errors->add( 'password_too_short', $this->get_error_message( 'password_too_short') );
		// 	return $errors;
		// }

		// if ( $_POST['password'] != $_POST['repeat_password'] ) {
		// 	// Passwords dont match
		// 	$errors->add( 'passwords_not_matched', $this->get_error_message( 'passwords_not_matched') );
		// 	return $errors;
		// }

		// if ( !empty( $_POST['password'] ) || ($_POST['password'] === $_POST['repeat_password']) ) {
		// 	$password = $_POST['password'];
		// }

		// Generate the password so that the subscriber will have to check email...
		$password = wp_generate_password( 12, false );
		$user_data = array(
			'user_login'    => $email,
			'user_email'    => $email,
			'user_pass'     => $password,
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'nickname'      => $first_name,
		);

		$user_id = wp_insert_user( $user_data );
		wp_new_user_notification( $user_id, $password );
		add_user_meta( $user_id, 'user_company', $user_company, false );

		return $user_id;


	}

	/**
	 * Checks that the reCAPTCHA parameter sent with the registration
	 * request is valid.
	 *
	 * @return bool True if the CAPTCHA is OK, otherwise false.
	 */
	private function verify_recaptcha() {
		// This field is set by the recaptcha widget if check is successful
		if ( isset ( $_POST['g-recaptcha-response'] ) ) {
			$captcha_response = $_POST['g-recaptcha-response'];
		} else {
			return false;
		}

		// Verify the captcha response from Google
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret' => get_option( 'personalize-login-recaptcha-secret-key' ),
					'response' => $captcha_response
				)
			)
		);

		$success = false;
		if ( $response && is_array( $response ) ) {
			$decoded_response = json_decode( $response['body'] );
			$success = $decoded_response->success;
		}

		return $success;
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		$user = wp_get_current_user();
		if ( user_can( $user, 'manage_options' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
				wp_redirect( admin_url(), 301 );
			}
		} else {
			wp_redirect( home_url( 'account' ), 301 );
		}
	}
	

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
		switch ( $error_code ) {
			// Login errors

			case 'empty_username':
				return __( 'Please enter a valid email address.', 'personalize-login' );

			case 'empty_password':
				return __( 'Please enter a password.', 'personalize-login' );

			case 'invalid_username':
				return __(
					"The email or password you entered is incorrect.",
					'personalize-login'
				);

			case 'incorrect_password':
				$err = __(
					"The email or password you entered is incorrect.",
					'personalize-login'
				);
				return sprintf( $err, wp_lostpassword_url() );

			// Registration errors

			case 'email':
				return __( 'Please enter a valid email address.', 'personalize-login' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'personalize-login' );

			case 'password_empty':
				return __( 'Please enter your password.', 'personalize-login' );

			case 'password_too_short':
				return __( 'Passwords require a minimum of 6 characters.', 'personalize-login' );

			case 'passwords_not_matched':
				return __( 'The two passwords you entered don\'t match.', 'personalize-login' );

			case 'closed':
				return __( 'We\'re sorry, we cannot create your account right now. Please try again later.', 'personalize-login' );

			case 'captcha':
				return __( 'The Google reCAPTCHA check failed. Are you a robot?', 'personalize-login' );

			// Lost password

			case 'empty_username':
				return __( 'Please enter your email address to continue.', 'personalize-login' );

			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There are no users registered with this email address.', 'personalize-login' );

			// Reset password

			case 'expiredkey':
			case 'invalidkey':
				return __( 'The password reset link you used has expired.', 'personalize-login' );

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'personalize-login' );

			case 'password_too_short':
				return __( 'Passwords require a minimum of 6 characters.', 'personalize-login' );

			case 'password_reset_empty':
				return __( "Passwords require a minimum of 6 characters.", 'personalize-login' );

			default:
				break;
		}

		return __( 'We\'re sorry, we cannot reset your password right now. Please try again later.', 'personalize-login' );
	}


	//
	// PLUGIN SETUP
	//

	/**
	 * Registers the settings fields needed by the plugin.
	 */
	public function register_settings_fields() {
		// Create settings fields for the two keys used by reCAPTCHA
		register_setting( 'general', 'personalize-login-recaptcha-site-key' );
		register_setting( 'general', 'personalize-login-recaptcha-secret-key' );

		add_settings_field(
			'personalize-login-recaptcha-site-key',
			'<label for="personalize-login-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'personalize-login' ) . '</label>',
			array( $this, 'render_recaptcha_site_key_field' ),
			'general'
		);

		add_settings_field(
			'personalize-login-recaptcha-secret-key',
			'<label for="personalize-login-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'personalize-login' ) . '</label>',
			array( $this, 'render_recaptcha_secret_key_field' ),
			'general'
		);
	}

	public function render_recaptcha_site_key_field() {
		$value = get_option( 'personalize-login-recaptcha-site-key', '' );
		echo '<input type="text" id="personalize-login-recaptcha-site-key" name="personalize-login-recaptcha-site-key" value="' . esc_attr( $value ) . '" />';
	}

	public function render_recaptcha_secret_key_field() {
		$value = get_option( 'personalize-login-recaptcha-secret-key', '' );
		echo '<input type="text" id="personalize-login-recaptcha-secret-key" name="personalize-login-recaptcha-secret-key" value="' . esc_attr( $value ) . '" />';
	}

}
// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );
