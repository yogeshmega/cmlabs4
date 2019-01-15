<?php
/*
Plugin Name: CM Labs Market Tags
Plugin URI: https://cm-labs.com
Description: 
Version: 1.0
Author: Yannick Lefebvre
*/

function cm_labs_market_tags_get_options() {
	$options = get_option( 'cm_labs_market_tags_options', array() );
	$new_options['market_list'] = '';
	
	$merged_options = wp_parse_args( $options, $new_options );
	$compare_options = array_diff_key( $new_options, $options );
	
	if ( empty( $options ) || !empty( $compare_options ) ) {
		update_option( 'cm_labs_market_tags_options', $merged_options );
	}
	return $merged_options;
}

add_action( 'wp_head', 'cm_labs_market_tags_header', 1 );

function cm_labs_market_tags_header() {
	$options = cm_labs_market_tags_get_options();	
	$market_list = explode( ',', $options['market_list'] );
	
	$page_id = get_queried_object_id();
	if ( !empty( $page_id ) ) {
		$market_tag = get_post_meta( $page_id, 'market_tag', true );
		if ( !empty( $market_tag ) ) { ?>
			<script>
				dataLayer = [{
					'LOB': '<?php echo $market_tag; ?>',
				}];
			</script>
		<?php }
	} elseif ( is_search() ) { ?>
			<script>
				dataLayer = [{
					'LOB': 'corp',
				}];
			</script>
	<?php }
}

add_action( 'admin_menu', 'cm_labs_market_tags_settings_menu' );


function cm_labs_market_tags_settings_menu() {
	add_options_page( 'CM Labs Market Tags', 'CM Labs Market Tags', 'manage_options', 'cm-labs-market-tags-settings', 'cm_labs_market_tags_config_page' );
}

function cm_labs_market_tags_config_page() {
	// Retrieve plugin configuration options from database
	$options = cm_labs_market_tags_get_options();
	?>
	<div id="cm-labs-markey-tags-general" class="wrap">
	<h2>CM Labs Market Tags</h2><br />
	<form method="post" action="admin-post.php">
	<input type="hidden" name="action" value="save_cm_labs_market_tags_options" />
	<!-- Adding security through hidden referrer field -->

	<?php wp_nonce_field( 'cmlabsmarkettags' ); ?>

	Market List (Comma-separated values):
	<input type="text" size="100" name="market_list" value="<?php echo esc_html( $options['market_list'] ); ?>"/><br /><br />
	<input type="submit" value="Submit" class="button-primary"/>
	</form>
	</div>
<?php }

add_action( 'admin_init', 'cm_labs_market_tags_admin_init' );

function cm_labs_market_tags_admin_init() {
	add_action( 'admin_post_save_cm_labs_market_tags_options', 'process_cm_labs_market_tags_options' );
	
	add_meta_box( 'cm_labs_market_tags_meta_box', 'Market Tag', 'cm_labs_market_tags_display_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'cm_labs_market_tags_meta_box', 'Market Tag', 'cm_labs_market_tags_display_meta_box', 'page', 'normal', 'high' );
	add_meta_box( 'cm_labs_market_tags_meta_box', 'Market Tag', 'cm_labs_market_tags_display_meta_box', 'resources', 'normal', 'high' );
	add_meta_box( 'cm_labs_market_tags_meta_box', 'Market Tag', 'cm_labs_market_tags_display_meta_box', 'news', 'normal', 'high' );
}

function process_cm_labs_market_tags_options() {
	// Check that user has proper security level
	if ( !current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed' );
	}
	
	// Check if nonce field configuration form is present
	check_admin_referer( 'cmlabsmarkettags' );
	
	// Retrieve original plugin options array
	$options = cm_labs_market_tags_get_options();

	// Cycle through all text form fields and store their values
	// in the options array
	foreach ( array( 'market_list' ) as $option_name ) {
		if ( isset( $_POST[$option_name] ) ) {
			$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
		}
	}
	
	// Store updated options array to database
	update_option( 'cm_labs_market_tags_options', $options );

	// Redirect the page to the configuration form
	wp_redirect( add_query_arg( 'page', 'cm-labs-market-tags-settings', admin_url( 'options-general.php' ) ) );
exit;
}

function cm_labs_market_tags_display_meta_box( $item ) {
	$market_tag = get_post_meta( $item->ID, 'market_tag', true );
	
	$options = cm_labs_market_tags_get_options();	
	$market_list = explode( ',', $options['market_list'] ); ?>
	
	<select name="market_tag">
		<option value="">No Market Assigned</option>
		<?php
			foreach ( $market_list as $market ) {
				echo '<option value="' . $market . '" ' . selected( $market_tag, $market, false ) . '>' . $market . '</option>';
			}
		?>
	</select>
<?php }

add_action( 'save_post', 'cm_labs_market_tags_save_fields', 10, 2 );

function cm_labs_market_tags_save_fields( $item_id, $item ) {
	// Check post type for book reviews
	if ( 'post' == $item->post_type || 'page' == $item->post_type || 'resources' == $item->post_type || 'news' == $item->post_type ) {
		// Store data in post meta table if present in post data
		if ( isset( $_POST['market_tag'] ) ) {
			update_post_meta( $item_id, 'market_tag', sanitize_text_field( $_POST['market_tag'] ) );
		}
	}
}

