<?php
/*
Plugin Name: CM Labs HRF Shortcodes
Plugin URI:  
Description: Additional shortcodes for HTML 5 Responsive Shortcodes
Version:     1.0
Author:      CM Labs Simulation
Author URI:  http://www.cm-labs.com
*/

function cmlabs_hrf_search( $atts ) {
	$output = '<div class="faqsearch">';
	$output .= '<form>';
	$output .= '<input type="text" name="searchterms" id="searchterms" placeholder="Search FAQs" value="' . $_GET['searchterms'] . '"/>';
	$output .= '<input type="submit" value="Search" id="searchfaq" /> <input type="submit" value="Clear Search" id="showallfaq" />';
	$output .= '</form>';
	$output .= '</div>';

	return $output;
}

add_shortcode ( 'hrf_search', 'cmlabs_hrf_search' );

 function highlightWords($string, $words, $divtype)
 {
    foreach ( $words as $word )
    {
        $string = str_ireplace($word, '<' . $divtype . ' class="highlight_word">'.$word.'</' . $divtype . '>', $string);
    }
    /*** return the highlighted string ***/
    return $string;
 }

add_shortcode('cml_hrf_faqs', 'cml_fn_hrf_faqs');

function cml_fn_hrf_faqs($attr)
{
   $faq_params = shortcode_atts( array(
        'category' => '',
        'title' => '',
    ), $attr );
    
   $html = '<div class="hrf-faq-list">';
   
   if( $faq_params['title'] != ''){
   $html .= '<h2 class="frq-main-title">'.$faq_params['title'].'</h2>';
   
   }
   $head_tag  = get_option('hrf_question_headingtype','h3');
   $faq_args = array(
        'post_type'      => 'hrf_faq',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
   );
   
   if ( isset( $_GET['searchterms'] ) && !empty( $_GET['searchterms'] ) ) {
		$faq_args['s'] = $_GET['searchterms'];
   }
   
   if( $faq_params['category'] != '' ){
      $faq_args['category_name'] = $faq_params['category'];
   }
   
   $faq_query = new WP_Query( $faq_args );

   if( $faq_query->have_posts() ): 
      while( $faq_query->have_posts() ): 
         $faq_query->the_post();
		 $content_to_display = apply_filters( 'the_content', get_the_content() );
		 $title_to_display = get_the_title();
		 
		 if ( isset( $_GET['searchterms'] ) && !empty( $_GET['searchterms'] ) ) {
			 $searcharray = explode( ' ', $_GET['searchterms'] );
			 
			 $content_to_display = highlightWords( $content_to_display, $searcharray, 'span' );
			 $title_to_display = highlightWords( $title_to_display, $searcharray, 'div' );
		 }		 

         $html .= '<article class="hrf-entry" id="hrf-entry-' . $faq_query->post->ID . '">
                      <' . $head_tag . ' class="hrf-title close-faq" data-content-id="hrf-content-' . $faq_query->post->ID . '"><span></span>' . $title_to_display . '</' . $head_tag . '>
                     <div class="hrf-content" id="hrf-content-'.$faq_query->post->ID.'">'. $content_to_display .'</div>
                  </article>';

      endwhile;
   else:
      $html .= "No FAQs Found";
   endif;
   wp_reset_query();
   $html .= '</div>';
   $html .= '<script type="text/javascript">';
   $html .= "\tjQuery( document ).ready(function() {\n";
   $html .= "\t\tjQuery( '#searchterms' ).val('');\n";
   $html .= "\t});\n";
   $html .= '</script>';
   
   return $html;
}