<?php

define('HPJSLIDER_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/hpjslider/');
define('HPJSLIDER_FRAMEWORK_DIRECTORY_URL', get_template_directory_uri() . '/hpjslider/');
define('HPJSLIDER_FRAMEWORK_DIRECTORY_PATH', dirname(__FILE__));


################################################################################
// FRONT
################################################################################
function hpjslider_front_scripts() {
global $wp_styles;
	// CSS Scripts
	wp_enqueue_style('hpjslider', HPJSLIDER_FRAMEWORK_DIRECTORY_URL .'assets/css/hpjslider.css', false ,'1.0.0' );
		
	// JS Scripts
	wp_enqueue_script('scroolTo', HPJSLIDER_FRAMEWORK_DIRECTORY_URL .'assets/js/jquery.scrollTo.min.js', array('jquery'),'1.4.13', true );
	wp_enqueue_script('hpjslider', HPJSLIDER_FRAMEWORK_DIRECTORY_URL .'assets/js/hpjslider.js', array('jquery'),'1.0.0', true );
	
}
add_action('wp_enqueue_scripts', 'hpjslider_front_scripts');




################################################################################
// ADMIN
################################################################################
//post meta styling
function  hpjslider_admin_scripts() {
	wp_enqueue_style('hpjslider_meta_css', HPJSLIDER_FRAMEWORK_DIRECTORY_URL .'assets/css/hpjslider.admin.css','', '3.0.5');
}

add_action('admin_print_styles', 'hpjslider_admin_scripts');
   

#-----------------------------------------------------------------#
# Custom slider columns
#-----------------------------------------------------------------# 
 
add_filter('manage_edit-home_slider_columns', 'edit_columns_home_slider');  

function edit_columns_home_slider($columns){  
	$column_thumbnail = array( 'thumbnail' => 'Thumbnail' );
	$columns = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, NULL, true );
	return $columns;
}  
  
  
add_action('manage_home_slider_posts_custom_column',  'home_slider_custom_columns', 10, 2);   

function home_slider_custom_columns($portfolio_columns, $post_id){  
	switch ($portfolio_columns) {
		case 'thumbnail':
			$thumbnail = get_post_meta($post_id, 'wpcf-bg-img', true);
			
			if( !empty($thumbnail) ) {
				echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . $thumbnail . '" /></a>';
			} else {
				echo '<a href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit"><img class="slider-thumb" src="' . HPJSLIDER_FRAMEWORK_DIRECTORY_URL . 'assets/images/slider-default-thumb.jpg" /></a>' .
					 '<strong><a class="row-title" href="'.get_admin_url() . 'post.php?post=' . $post_id.'&action=edit">Pas encore d\'image</a></strong>';
			}
		break;
  
		default:
			break;
	}  
}  


add_action( 'admin_menu', 'hpjslider_home_slider_ordering' );

function hpjslider_home_slider_ordering() {
	add_submenu_page(
		'edit.php?post_type=home_slider',
		'Ordre des slides',
		'Ordre', 
		'edit_pages', 'slide-order',
		'hpjslider_home_slider_order_page'
	);
}

function hpjslider_home_slider_order_page(){ ?>
	
	<div class="wrap">
		<h2>Ordre des slides</h2>
		<p>Simplement déplacer vers le haut ou le bas et vos images seront sauvegarder dans cet ordre.</p>
	<?php $slides = new WP_Query( array( 'post_type' => 'home_slider', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' ) ); ?>
	<?php if( $slides->have_posts() ) : ?>
		
		<?php wp_nonce_field( basename(__FILE__), 'hpjslider_meta_box_nonce' ); ?>
		
		<table class="wp-list-table widefat fixed posts" id="sortable-table">
			<thead>
				<tr>
					<th class="column-order">Ordre</th>
					<th class="manage-column column-thumbnail">Image</th>
					<th class="manage-column column-title">Titre</th>
				</tr>
			</thead>
			<tbody data-post-type="home_slider">
			<?php while( $slides->have_posts() ) : $slides->the_post(); ?>
				<tr id="post-<?php the_ID(); ?>">
					<td class="column-order"><img src="<?php echo HPJSLIDER_FRAMEWORK_DIRECTORY_URL . 'assets/images/sortable.png'; ?>" title="" alt="Move Icon" width="25" height="25" class="" /></td>
					<td class="thumbnail column-thumbnail">
						<?php 
						global $post;
						$thumbnail = get_post_meta($post->ID, 'wpcf-bg-img', true);
			
						if( !empty($thumbnail) ) {
						   echo '<img class="slider-thumb" src="' . $thumbnail . '" />' ;
						} 
						else {
							echo '<img class="slider-thumb" src="' . HPJSLIDER_FRAMEWORK_DIRECTORY_URL . 'assets/images/slider-default-thumb.jpg" />' .
								 '<strong>Pas encore d\'image</strong>';
						} ?>
						
					</td>
					<td class="caption column-caption">
						<?php 
						echo get_the_title($post->ID); ?>
					</td>
				</tr>
			<?php endwhile; ?>
			</tbody>
			<tfoot>
				<tr>
					<th class="column-order">Ordre</th>
					<th class="manage-column column-thumbnail">Image</th>
					<th class="manage-column column-caption">Titre</th>
				</tr>
			</tfoot>

		</table>

	<?php else: ?>

		<p>No slides found, why not <a href="post-new.php?post_type=home_slider">create one?</a></p>

	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	</div><!-- .wrap -->
	
<?php }

  
add_action( 'admin_enqueue_scripts', 'home_slider_enqueue_scripts' );

function home_slider_enqueue_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'hpjslider-reorder', HPJSLIDER_FRAMEWORK_DIRECTORY_URL . 'assets/js/admin/nectar-reorder.js' );
}

add_action( 'wp_ajax_hpjslider_update_slide_order', 'hpjslider_update_slide_order' );

//slide order ajax callback 
function hpjslider_update_slide_order() {
	
		global $wpdb;
	 
		$post_type     = $_POST['postType'];
		$order        = $_POST['order'];
		
		if (  !isset($_POST['hpjslider_meta_box_nonce']) || !wp_verify_nonce( $_POST['hpjslider_meta_box_nonce'], basename( __FILE__ ) ) )
			return;
		
		foreach( $order as $menu_order => $post_id ) {
			$post_id         = intval( str_ireplace( 'post-', '', $post_id ) );
			$menu_order     = intval($menu_order);
			
			wp_update_post( array( 'ID' => stripslashes(htmlspecialchars($post_id)), 'menu_order' => stripslashes(htmlspecialchars($menu_order)) ) );
		}
 
		die( '1' );
}


//order the default home slider page correctly 
function set_home_slider_admin_order($wp_query) {  
  if (is_admin()) {  
  
	$post_type = $wp_query->query['post_type'];  
  
	if ( $post_type == 'home_slider') {  
   
	  $wp_query->set('orderby', 'menu_order');  
	  $wp_query->set('order', 'ASC');  
	}  
  }  
}  

add_filter('pre_get_posts', 'set_home_slider_admin_order'); 