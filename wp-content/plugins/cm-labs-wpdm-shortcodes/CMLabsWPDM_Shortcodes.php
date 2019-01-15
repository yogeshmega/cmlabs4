<?php
/*
Plugin Name: CM Labs WPDM Shortcodes
Plugin URI:  
Description: Checks the health of your WordPress install
Version:     1.0
Author:      CM Labs Simulation
Author URI:  http://www.cm-labs.com
*/

define ( 'DOCS_PER_PAGE', 25 );

function cml_wpdm_highlightWords( $string, $words, $divtype ) {
	preg_match_all('~\w+~', $words, $m);
    if(!$m)
        return $string;
    $re = '~(' . implode('|', $m[0]) . ')~i';
    return preg_replace($re, "<$divtype class='highlight_word'>$0</$divtype>", $string);
 }
 
add_action( 'after_setup_theme', 'cml_hook_the_remove', 1) ;

function cml_hook_the_remove() {
	remove_action( 'wp_ajax_photo_gallery_upload', 'wpdm_check_upload', 10 );
	
	remove_action( 'init', 'wpdm_view_countplus' );
}


add_action( 'init', 'cml_doc_center_init');

function cml_doc_center_init() {

	register_taxonomy(
		'cml_doc_industries',
		'wpdmpro',
		array(
			'labels' => array(
				'name' => 'Industries',
				'add_new_item' => 'Add New Industry',
				'new_item_name' => "New Industry"
			),
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true
		)
	);
	
	register_taxonomy(
		'cml_doc_regions',
		'wpdmpro',
		array(
			'labels' => array(
				'name' => 'Regions',
				'add_new_item' => 'Add New Region',
				'new_item_name' => "New Region"
			),
			'show_ui' => true,
			'show_tagcloud' => false,
			'hierarchical' => true
		)
	);
	
	add_action( 'wp_ajax_photo_gallery_upload', 'wpdm_cml_check_upload' );
	
}

function wpdm_cml_check_upload() {

  if( !current_user_can( 'edit_posts' ) ) return;

  check_ajax_referer( 'photo-upload' );

  $filename = get_option( '__wpdm_sanitize_filename',0 ) == 1? sanitize_file_name($_FILES['async-upload']['name']):$_FILES['async-upload']['name'] ;

  if( file_exists( UPLOAD_DIR . $filename ) ) {
	  unlink( $filename );
  }
  
  move_uploaded_file($_FILES['async-upload']['tmp_name'],UPLOAD_DIR.$filename);
  echo $filename;
  exit;
}

function cml_posts_where_filter( $where_clause, $query_object ) {
	
	if ( isset( $query_object->query_vars['s'] ) && !empty( $query_object->query_vars['s'] ) && isset( $query_object->query_vars['post_type'] ) && $query_object->query_vars['post_type'] == 'wpdmpro' ) {		
		$newsearch = '';
		
		foreach ( $query_object->query_vars['search_terms'] as $searchterm ) {
            $newsearch .= " OR ( tt.taxonomy IN ( 'wpdmcategory', 'cml_doc_industries', 'cml_doc_regions' ) AND t.name LIKE '%$searchterm%' ) ";
		}
		
		$newsearch .= " OR ( metadate.meta_key = 'release_date' AND FROM_UNIXTIME(metadate.meta_value, '%M %d, %Y') LIKE '%$searchterm%' ) ";
		
		$newsearch .= ' OR ';
		
		$where_clause = str_replace( 'OR', $newsearch, $where_clause );		
	}	
	
	return $where_clause;
}
	
function cml_posts_join_filter( $join ) {
    /* global $wp_query, $wpdb;
	
	if ( !empty( $join ) ) {
		//join taxonomies table
	    $join .= " LEFT JOIN $wpdb->term_relationships tr ON ($wpdb->posts.ID = tr.object_id) ";
	    $join .= " LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
	    $join .= " LEFT JOIN $wpdb->terms t ON (tt.term_id = t.term_id) ";
		$join .= " LEFT JOIN $wpdb->postmeta metadate ON ($wpdb->posts.ID = metadate.post_id) ";	
	} */
            
    return $join;
}

function cml_posts_type_clauses( $clauses, $wp_query ) {
	/* global $wpdb;

	if ( isset( $wp_query->query['orderby'] ) 
		&& ( 'wpdmcategory' == $wp_query->query['orderby'] 
			  || 'cml_doc_industries' == $wp_query->query['orderby'] 
			  || 'cml_doc_regions' == $wp_query->query['orderby'] ) ) {
	
	$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} tr2 ON {$wpdb->posts}.ID=tr2.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (tr2.term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;

		$clauses['where'] .= " AND (taxonomy = '" . $wp_query->query['orderby'] . "' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby']  = "GROUP_CONCAT({$wpdb->terms}.slug ORDER BY slug ASC) ";
		$clauses['orderby'] .= ( 'ASC' == strtoupper( $wp_query->get('order') ) ) ? 'ASC' : 'DESC';
	} */

	return $clauses;
}

function cml_distinct( $distinct ) {
    $distinct = 'DISTINCT';
    return $distinct;
}

add_action( 'wp_ajax_cml_doc_center_update', 'cmlabs_wpdm_documents' );
add_action( 'wp_ajax_nopriv_cml_doc_center_update', 'cmlabs_wpdm_documents' );

function cmlabs_wpdm_documents( $atts ) {
	
	if ( isset( $_GET['ajaxupdate'] ) ) {
		check_ajax_referer( 'cml_doc_center_ajax_refresh' );
	}
	
	extract( shortcode_atts( array(
		'parentcat' => '',
		'border' => 'false'
	), $atts ) );
	
	$permalink = get_permalink();
	
	$doc_type = '';
	$industry = '';
	$region = '';
	$docpage = '';
	$searchstring = '';
	$direct_query_args = array();

	// Preparation of query array to retrieve 5 book reviews
	$query_params = array( 'post_type' => 'wpdmpro',
		'post_status' => 'publish' ,
		'posts_per_page' => -1 );
		
	if ( !isset( $_GET['sort'] ) ) {
		$query_params['orderby'] = 'meta_value_num';
		$query_params['meta_key'] = 'release_date';
		$query_params['order'] = 'DESC';
		$sorttype = 'date';
		$sortorder = $query_params['order'];
		$direct_query_args['sort'] = 'date';
	} elseif ( isset( $_GET['sort'] ) && $_GET['sort'] == 'date' ) {
		$query_params['orderby'] = 'meta_value_num';
		$query_params['meta_key'] = 'release_date';
		
		if ( !isset( $_GET['order'] ) ) {
			$query_params['order'] = 'DESC';
		} else {
			$query_params['order'] = $_GET['order'];
		}		
		
		$sorttype = 'date';
		$sortorder = $query_params['order'];
		$direct_query_args['sort'] = 'date';
	} elseif ( isset( $_GET['sort'] ) && ( 'title' == $_GET['sort'] || 'wpdmcategory' == $_GET['sort'] || 'cml_doc_industries' == $_GET['sort'] || 'cml_doc_regions' == $_GET['sort'] ) ) {
		$query_params['orderby'] = $_GET['sort'];
		
		if ( !isset( $_GET['order'] ) ) {
			$query_params['order'] = 'ASC';	
		} else {
			$query_params['order'] = $_GET['order'];
		}
		$sorttype = $_GET['sort'];
		$sortorder = $query_params['order'];
		$direct_query_args['sort'] = $_GET['sort'];
	}	
	
	if ( isset( $_GET['searchstring'] ) && !empty( $_GET['searchstring'] ) ) {
		$query_params['s'] = $_GET['searchstring'];
		$searchstring = $_GET['searchstring'];
		$direct_query_args['searchstring'] = $_GET['searchstring'];		
	}
	
	if ( isset( $_GET['doctype'] ) && !empty( $_GET['doctype'] ) && $_GET['doctype'] != 'all' ) {
		$query_params['wpdmcategory'] = $_GET['doctype'];
		$doc_type = $_GET['doctype'];
		$direct_query_args['doctype'] = $_GET['doctype'];
	}
	
	if ( isset( $_GET['industry'] ) && !empty( $_GET['industry'] ) && $_GET['industry'] != 'z-all' ) {
		$query_params['cml_doc_industries'] = $_GET['industry'];
		
/* 		$query_params['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'cml_doc_industries',
				'field'    => 'slug',
				'terms'    => array( $_GET['industry'], 'z-all' ),
			)
		); */
		$industry = $_GET['industry'];
		$direct_query_args['industry'] = $_GET['industry'];
	}
	
	if ( isset( $_GET['region'] ) && !empty( $_GET['region'] ) && $_GET['region'] != 'z-all' ) {
		//$query_params['cml_doc_regions'] = ;
		
		$query_params['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'cml_doc_regions',
				'field'    => 'slug',
				'terms'    => array( $_GET['region'], 'z-all' ),
			)
		);
	
		$region = $_GET['region'];
		$direct_query_args['region'] = $_GET['region'];
	}
	
	if ( isset( $_GET['docpage'] ) && !empty( $_GET['docpage'] ) ) {
		//$query_params['paged'] = $_GET['docpage'];
		$docpage = $_GET['docpage'];
		$direct_query_args['docpage'] = $_GET['docpage'];
	} else {
		$docpage = 1;
	}
				
	/* 
	add_filter( 'posts_where', 'cml_posts_where_filter', 10, 2 );
	add_filter( 'posts_join', 'cml_posts_join_filter', 10 );
	*/
	
	if ( 'wpdmcategory' == $sorttype || 'cml_doc_industries' == $sorttype || 'cml_doc_regions' == $sorttype ) {
		add_filter( 'posts_clauses', 'cml_posts_type_clauses', 10, 2 );
	}
	
	$doc_query = new WP_Query;
	$doc_query->query( $query_params );
	
	/* remove_filter( 'posts_where', 'cml_posts_where_filter' );
	remove_filter( 'posts_join', 'cml_posts_join_filter' ); */
	
	if ( 'wpdmcategory' == $sorttype  || 'cml_doc_industries' == $sorttype || 'cml_doc_regions' == $sorttype ) {
		remove_filter( 'posts_clauses', 'cml_posts_type_clauses' );
	}
	
	$output = '';
	
	if ( !isset( $_GET['ajaxupdate'] ) ) {
		$output .= '<div class="cml_doc_filters">';
		
		$industry_terms = get_terms( array( 'taxonomy' => 'cml_doc_industries', 'hide_empty' => false ) );
		
		$output .= '<div class="cml_doc_industry_filter select-wrapper">';
		$output .= '<select id="cml_doc_industry_select">';
		
		if ( !empty( $industry_terms ) ) {
			foreach ( $industry_terms as $industry_term ) {
				$output .= '<option value="' . $industry_term->slug . '" ' . selected( $industry_term->slug, $industry, false ) . '>' . $industry_term->name . '</option>';
			}				
		}
		
		$output .= '</select>';
		$output .= '</div>';
		
		$type_terms = get_terms( 'wpdmcategory', array( 'hide_empty' => false ) );
					
		$output .= '<div class="cml_doc_types_filter select-wrapper">';
		$output .= '<select id="cml_doc_types_select">';
		
		$output .= '<option value="all" ' . selected( 'all', $doc_type, false ) . '>All Document Types</option>';
	
		if ( !empty( $type_terms ) ) {
			foreach ( $type_terms as $type_term ) {
				$output .= '<option value="' . $type_term->slug . '" ' . selected( $type_term->slug, $doc_type, false ) . '>' . $type_term->name . '</option>';
			}				
		}
	
		$output .= '</select>';
		$output .= '</div>';
		
		$all_region_key = '';
		
		$region_terms = get_terms( 'cml_doc_regions', array( 'hide_empty' => false ) );
		
		if ( !empty( $region_terms ) ) {
			foreach ( $region_terms as $term_key => $region_term ) {
				if ( 'English' == $region_term->name ) {
					$all_region_key = $term_key;
				}
			}
		}
		
		if ( !empty( $term_key ) ) {
			$first_item = $region_terms[$all_region_key];
			unset( $region_terms[$all_region_key] );
			array_unshift( $region_terms, $first_item );
		}		
				
		$output .= '<div class="cml_doc_region_filter select-wrapper">';
		$output .= '<select id="cml_doc_region_select">';
		
		if ( !empty( $region_terms ) ) {
			foreach ( $region_terms as $region_term ) {
				$output .= '<option value="' . $region_term->slug . '" ' . selected( $region_term->slug, $region, false ) . '>' . $region_term->name . '</option>';
			}				
		}
		
		$output .= '</select>';
		$output .= '</div>';
		$output .= '<input type="hidden" id="cml_doc_sort_field" value="' . $sorttype . '">';
		$output .= '<input type="hidden" id="cml_doc_sort_order" value="' . $sortorder . '">';
		$output .= '<div class="cml_doc_search">';
		$output .= '<input type="text" id="cml_doc_search_string" size=20 placeholder="Search..." value="' . $searchstring . '">';
		$output .= '<button type="button" class="btn" id="runsearch">Search</button>';
		$output .= '<button type="button" class="btn" id="clearsearch">Clear Search</button>';
		$output .= '</div>';
		$output .= '<div style="clear:both;"></div>';
		$output .= '</div>';
		
		$output .= "<script type='text/javascript'>\n";
		
		$nonce = wp_create_nonce( 'cml_doc_center_ajax_refresh' );
		
		$output .= "function ChangeUrl(page, url) {\n";
		$output .= "\tif (typeof (history.pushState) != 'undefined') {\n";
		$output .= "\t\tvar obj = { Page: page, Url: url };\n";
		$output .= "\t\thistory.pushState(obj, obj.Page, obj.Url);\n";
		$output .= "\t}\n";
		$output .= "}\n";
	
		$output .= "function update_list( updatetype, pagenumber ) {\n";
		$output .= "\tvar industryvalue = jQuery('#cml_doc_industry_select').val();\n";
		$output .= "\tvar orderfield = jQuery('#cml_doc_sort_field').val();\n";
		$output .= "\tvar orderdirection = jQuery('#cml_doc_sort_order').val();\n";
		$output .= "\tvar typevalue = jQuery('#cml_doc_types_select').val();\n";
		$output .= "\tvar regionvalue = jQuery('#cml_doc_region_select').val();\n";
		$output .= "\tvar searchvalue = jQuery('#cml_doc_search_string').val();\n";
		$output .= "\tvar params = { sort: orderfield, order: orderdirection, doctype: typevalue, industry: industryvalue, searchstring: searchvalue, region: regionvalue };\n";
		$output .= "\tquery = '?' + jQuery.param(params);\n";
		$output .= "\tChangeUrl( 'Test', query );\n";
		$output .= "\tjQuery.ajax( { type: 'GET', url: '" . admin_url( 'admin-ajax.php' ) ."', data: { _ajax_nonce: '" . $nonce . "', action: 'cml_doc_center_update', sort: orderfield, order: orderdirection, doctype: typevalue, industry: industryvalue, ajaxupdate : 'full', searchstring: searchvalue, docpage: pagenumber, region: regionvalue }, success: function(data) {\n";
		$output .= "\t\tif ( updatetype == 'full' ) {\n";
		$output .= "\t\t\tjQuery('.cml_doc_output').replaceWith(data);\n";
		$output .= "\t\t} else {\n";
		$output .= "\t\t\tjQuery('.doc_list_footer').replaceWith(data);\n";
		$output .= "\t\t}\n";
		$output .= "} } );\n";
		$output .= "}\n";	
		
		$output .= "jQuery( document ).ready( function() {\n";
		
		$output .= "\tjQuery('#runsearch').click( function() {\n";
		$output .= "\t\tupdate_list( 'full' );\n";
		$output .= "});\n";
		
		$output .= "\tjQuery('#clearsearch').click( function() {\n";
		$output .= "\t\tjQuery( '#cml_doc_search_string' ).val('');\n";
		$output .= "\t\t\tupdate_list( 'full' );\n";
		$output .= "});\n";
		
		$output .= "\tjQuery('#cml_doc_search_string').keyup(function (e) {\n";
		$output .= "\t\tif (e.keyCode == 13) {\n";
		$output .= "\t\t\tupdate_list( 'full' );\n";
		$output .= "\t\t}\n";
		$output .= "});\n";
		
		$output .= "\tjQuery('#cml_doc_types_select, #cml_doc_industry_select, #cml_doc_region_select').change( function() {\n";
		$output .= "\t\tupdate_list( 'full' );\n";
		$output .= "});\n";
		
		$output .= "\tjQuery( document ).on( 'click', '.cml_doc_title_label, .cml_doc_release_date_label, .cml_doc_type_label, .cml_doc_industry_label, .cml_doc_region_label', function() {\n";
		$output .= "\t\tif ( jQuery( this ).attr( 'class' ) == 'cml_doc_title_label' ) { sorttype = 'title'; }\n";
		$output .= "\t\tif ( jQuery( this ).attr( 'class' ) == 'cml_doc_release_date_label' ) { sorttype = 'date'; }\n";
		$output .= "\t\tif ( jQuery( this ).attr( 'class' ) == 'cml_doc_type_label' ) { sorttype = 'wpdmcategory'; }\n";
		$output .= "\t\tif ( jQuery( this ).attr( 'class' ) == 'cml_doc_industry_label' ) { sorttype = 'cml_doc_industries'; }\n";
		$output .= "\t\tif ( jQuery( this ).attr( 'class' ) == 'cml_doc_region_label' ) { sorttype = 'cml_doc_regions'; }\n";
		$output .= "\t\tif ( jQuery( '#cml_doc_sort_field' ).val() != sorttype ) {\n";
		$output .= "\t\t\tjQuery( '#cml_doc_sort_field' ).val( sorttype );\n";
		$output .= "\t\t\tjQuery( '#cml_doc_sort_order' ).val( 'ASC' );\n";
		$output .= "\t\t} else {\n";
		$output .= "\t\t\tif ( jQuery( '#cml_doc_sort_order' ).val() == 'ASC' ) {;\n";
		$output .= "\t\t\t\tjQuery( '#cml_doc_sort_order' ).val( 'DESC' );\n";
		$output .= "\t\t\t} else {\n";
		$output .= "\t\t\t\tjQuery( '#cml_doc_sort_order' ).val( 'ASC' );\n";
		$output .= "\t\t\t};\n";
		$output .= "\t\t};\n";
		$output .= "\t\tupdate_list( 'full' );\n";
		$output .= "\t});\n";
		
		$output .= "\tjQuery( document ).on( 'click', '.cml_doc_title, .cml_doc_date, .cml_doc_size, .cml_doc_type, .cml_doc_industry, .cml_doc_icon, .cml_doc_region', function() {\n";
		$output .= "\t\tdocid = jQuery( this ).attr('id');\n";
		$output .= "\t\tjQuery( '#cml_doc_extra_info_' + docid ).slideToggle();";
		$output .= "\t});\n";
		
		$output .= "});\n";		
		$output .= "</script>\n";
	}	
		
	$output .= '<div class="cml_doc_output">';
	
	// Check if any posts were returned by the query
	if ( $doc_query->have_posts() ) {	
		if ( !isset( $_GET['ajaxupdate'] ) || ( isset( $_GET['ajaxupdate'] ) && 1 == $docpage ) ) {	
			$output .= '<div class="cml_doc_item_headers">';
		
			$output .= '<div class="cml_doc_title_label" id="' . get_the_ID() . '">Title ';
			
			if ( 'title' == $sorttype ) {
				if ( 'ASC' == $sortorder ) {
					$output .= '<img src="' . plugins_url( 'icons/Ascending.png', __FILE__ ) . '" />';
				} else {
					$output .= '<img src="' . plugins_url( 'icons/Descending.png', __FILE__ ) . '" />';
				}
			}
			
			$output .= '</div>';		
			$output .= '<div class="cml_doc_release_date_label" id="' . get_the_ID() . '">Release Date ';
			
			if ( 'date' == $sorttype ) {
				if ( 'ASC' == $sortorder ) {
					$output .= '<img src="' . plugins_url( 'icons/Ascending.png', __FILE__ ) . '" />';
				} else {
					$output .= '<img src="' . plugins_url( 'icons/Descending.png', __FILE__ ) . '" />';
				}			
			}
			
			$output .= '</div>';		
			$output .= '<div class="cml_doc_type_label" id="' . get_the_ID() . '">Type ';
			
			if ( 'wpdmcategory' == $sorttype ) {
				if ( 'ASC' == $sortorder ) {
					$output .= '<img src="' . plugins_url( 'icons/Ascending.png', __FILE__ ) . '" />';
				} else {
					$output .= '<img src="' . plugins_url( 'icons/Descending.png', __FILE__ ) . '" />';
				}			
			}
			
			$output .= '</div>';
			$output .= '<div class="cml_doc_industry_label" id="' . get_the_ID() . '">Industry ';
			
			if ( 'cml_doc_industries' == $sorttype ) {
				if ( 'ASC' == $sortorder ) {
					$output .= '<img src="' . plugins_url( 'icons/Ascending.png', __FILE__ ) . '" />';
				} else {
					$output .= '<img src="' . plugins_url( 'icons/Descending.png', __FILE__ ) . '" />';
				}			
			}
			
			$output .= '</div>';
			$output .= '<div class="cml_doc_region_label" id="' . get_the_ID() . '">Language ';
			
			if ( 'cml_doc_regions' == $sorttype ) {
				if ( 'ASC' == $sortorder ) {
					$output .= '<img src="' . plugins_url( 'icons/Ascending.png', __FILE__ ) . '" />';
				} else {
					$output .= '<img src="' . plugins_url( 'icons/Descending.png', __FILE__ ) . '" />';
				}			
			}
			
			$output .= '</div>';		
			
			$output .= '<div style="clear:both;"></div>';
				
			$output .= '</div>'; // End of cml_doc_item	
		}		
	
		$doc_counter = 0;
		$max_val = DOCS_PER_PAGE * $docpage;
		if ( $docpage > 1 ) {
			$min_val = ( DOCS_PER_PAGE * ( $docpage - 1 ) ) + 1 ;
		} else {
			$min_val = 1;
		}
			
		// Cycle through all items retrieved
		while ( $doc_query->have_posts() ) {
			$doc_counter++;
			$doc_query->the_post();
						
			if ( $doc_counter >= $min_val && $doc_counter <= $max_val ) {
			
				$iconpath = get_post_meta( get_the_ID(), '__wpdm_icon', true );
				$label = get_post_meta( get_the_ID(), '__wpdm_link_label', true );
				
				$package_size = get_post_meta( get_the_ID(), '__wpdm_package_size', true );
				
				$doc_type_terms = wp_get_post_terms( get_the_ID(), 'wpdmcategory' );
				
				$release_date_timestamp = get_post_meta( get_the_ID(), 'release_date', true );
				$human_date = date( 'F j, Y', $release_date_timestamp );
				
				$industry_terms = wp_get_post_terms( get_the_ID(), 'cml_doc_industries' );
				$region_terms = wp_get_post_terms( get_the_ID(), 'cml_doc_regions' );
				
				$output .= '<div class="cml_doc_item" id="cml_doc_item_' . get_the_id() . '">';
				
				if ( !empty( $iconpath ) ) {
					$output .= '<div class="cml_doc_icon" id="' . get_the_ID() . '"><img src="' . $iconpath . '" /></div>';
				}
				
				$title_to_display = get_the_title( get_the_ID() );
				$content_to_display = get_the_content();	
				$document_type = $doc_type_terms[0]->name;
				
				$external_url = esc_url( get_post_meta( get_the_ID(), 'external_url', true ) );
				$size_override = esc_html( get_post_meta( get_the_ID(), 'size_override', true ) );
				
				if ( !empty( $external_url ) ) {
					$download_link = $external_url;
				} else {
					$download_link = home_url() . '?wpdmdl=' . get_the_ID();
				}			
				
				if ( !empty( $_GET['searchstring'] ) ) {
					$content_to_display = cml_wpdm_highlightWords( $content_to_display, $_GET['searchstring'], 'span' );
					$title_to_display = cml_wpdm_highlightWords( $title_to_display, $_GET['searchstring'], 'div' );
					$document_type = cml_wpdm_highlightWords( $document_type, $_GET['searchstring'], 'span' );								
					$human_date = cml_wpdm_highlightWords( $human_date, $_GET['searchstring'], 'span' );
				}			
				
				$output .= '<div class="cml_doc_title" id="' . get_the_ID() . '">' . $title_to_display . '</div>';
				
				$output .= '<div class="cml_doc_date" id="' . get_the_ID() . '">' . $human_date . '</div>';
				
				$output .= '<div class="cml_doc_type" id="' . get_the_ID() . '">' . $document_type . '</div>';
				
				$output .= '<div class="cml_doc_industry" id="' . get_the_ID() . '">';
				if ( !empty( $industry_terms ) ) {
					$first_term = 1;
					foreach ( $industry_terms as $industry_term ) {
						if ( $first_term != 1 ) {
							$output .= ", ";
						}
						if ( !empty( $_GET['searchstring'] ) ) {
							$industry_cat = cml_wpdm_highlightWords( $industry_term->name, $_GET['searchstring'], 'span' );
						} else {
							$industry_cat = $industry_term->name;
						}
						$output .= $industry_cat;
						$first_term = 0;
					}
				}
				$output .= '</div>';
				
				$output .= '<div class="cml_doc_region" id="' . get_the_ID() . '">';
				if ( !empty( $region_terms ) ) {
					$first_term = 1;
					foreach ( $region_terms as $region_term ) {
						if ( $first_term != 1 ) {
							$output .= ", ";
						}
						if ( !empty( $_GET['searchstring'] ) ) {
							$region_cat = cml_wpdm_highlightWords( $region_term->name, $_GET['searchstring'], 'span' );
						} else {
							$region_cat = $region_term->name;
						}
						$output .= $region_cat;
						$first_term = 0;
					}
				}
				$output .= '</div>';		
							
				$output .= '<div class="cml_download_link"><a href="' . $download_link . '"><img src="' . plugins_url( 'images/Downloads-32.png', __FILE__ ) . '"></a></div>';
				
				$output .= '<div class="cml_doc_size" id="' . get_the_ID() . '">';
				
				if ( !empty( $size_override ) ) {
					$output .= $size_override;
				} else {
					$output .= $package_size;
				}
				
				$output .= '</div>';
				
				$output .= '<div class="cml_doc_extra_info" id="cml_doc_extra_info_' . get_the_ID() . '">';
				
				$doc_image = get_the_post_thumbnail( get_the_ID(), 'medium' );
				if ( !empty(  $doc_image ) ) {
					$output .= '<div class="cml_doc_image';
				
					if ( $border == 'true' ) {
						$output .= ' cml_doc_image_border';
					}
					
					$output .= '">' . $doc_image . '</div>';
				}			
							
				$output .= '<div class="cml_doc_content">' . $content_to_display . '</div>';
				
				$output .= '<div class="cml_download_text_link"><a href="' . $download_link . '">Download</a></div>';
				
				if ( current_user_can( 'edit_pages' ) ) {
					$output .= '<div class="cml_doc_edit"><a href="' . add_query_arg( array( 'post' => get_the_ID(), 'action' => 'edit' ), admin_url( 'post.php' ) ) . '">Edit</a></div>';
				}
				
				$output .= '<div style="clear:both;"></div>';
				
				$output .= '</div>'; // End of cml_doc_extra_info_
				
				$output .= '<div style="clear:both;"></div>';
				
				$output .= '</div>'; // End of cml_doc_item
			}			
		}
		
		$output .= '<div class="doc_list_footer">';
				
		$output .= "<script type='text/javascript'>\n";
	
		$output .= "jQuery( document ).ready( function() {\n";
		
		$output .= "\tjQuery( '.nextpage' ).click( function() {\n";
		$output .= "\t\tupdate_list( 'page', jQuery( '.nextpage' ).attr('id') );\n";
		$output .= "\t});\n";
				
		$output .= "});\n";		
		$output .= "</script>\n";
		
		if ( $doc_counter++ > $docpage * DOCS_PER_PAGE ) {
			$output .= '<span class="nextpage" id="' . ( $docpage + 1 ) . '">View More Items</span>';
		}
		
		$max_page = ceil( $doc_counter++ / DOCS_PER_PAGE );
		if ( $docpage == 1 ) {
			$page_label = ' 1';
		} else {
			$page_label = '(s) 1-' . $docpage;
		}
		$output .= '<span class="doccount">Showing page' . $page_label . ' of ' . $max_page . '</span>';		
		$output .= '</div>';
				
		
	} else {
		$output .= 'No results found based on current filters.';		
	}
	
	$output .= '</div>';
		
	// Reset post data query
	wp_reset_postdata();

	if ( isset( $_GET['ajaxupdate'] ) ) {
		echo $output;
		exit;
	} else {
		return $output;
	}
}

add_shortcode ( 'wpdm_cmlabs_documents', 'cmlabs_wpdm_documents' );

function cmlabs_wpdm_documents_recent( $atts ) {
	
	extract( shortcode_atts( array(
		'parentcat' => '',
		'border' => 'false'
	), $atts ) );
	
	$permalink = get_permalink();
	
	$doc_type = '';
	$industry = '';
	$region = '';
	$docpage = '';
	$searchstring = '';
	$direct_query_args = array();

	// Preparation of query array to retrieve 5 book reviews
	$query_params = array( 'post_type' => 'wpdmpro',
		'post_status' => 'publish',
		'posts_per_page' => 5,
		'post__not_in' => array(814,812) );
		
	$query_params['orderby'] = 'meta_value_num';
	$query_params['meta_key'] = 'release_date';
	$query_params['order'] = 'DESC';
	$sorttype = 'date';
	$direct_query_args['sort'] = 'date';
				
	// Execution of post query
	$doc_query = new WP_Query;
	$doc_query->query( $query_params );
	
	$output = '';
		
	$output .= '<div class="cml_doc_output_recent">';
	
	// Check if any posts were returned by the query
	if ( $doc_query->have_posts() ) {		
		// Cycle through all items retrieved
		while ( $doc_query->have_posts() ) {
			$doc_query->the_post();
			
			$iconpath = get_post_meta( get_the_ID(), '__wpdm_icon', true );
			$label = get_post_meta( get_the_ID(), '__wpdm_link_label', true );
			
			$doc_type_terms = wp_get_post_terms( get_the_ID(), 'wpdmcategory' );
			
			if ( !empty( $external_url ) ) {
				$download_link = $external_url;
			} else {
				$download_link = home_url() . '?wpdmdl=' . get_the_ID();
			}			
			
			$release_date_timestamp = get_post_meta( get_the_ID(), 'release_date', true );
			$human_date = date( 'F j, Y', $release_date_timestamp );
						
			$output .= '<div class="cml_doc_item" id="cml_doc_item_' . get_the_id() . '">';
			
			if ( !empty( $iconpath ) ) {
				$output .= '<a href="' . $download_link . '"><img class="cml_doc_icon_recent" src="' . $iconpath . '" /></a>';
			}
			
			$title_to_display = get_the_title( get_the_ID() );
			$content_to_display = get_the_content();	
			$document_type = $doc_type_terms[0]->name;
			
			$external_url = esc_url( get_post_meta( get_the_ID(), 'external_url', true ) );
			
			$output .= '<p class="cml_doc_title_recent" id="' . get_the_ID() . '"><a href="' . $download_link . '">' . $title_to_display . '</a></p>';
									
			$output .= '<div style="clear:both;"></div>';
			
			$output .= '</div>'; // End of cml_doc_item
		}		
		
	} else {
		$output .= 'No results found based on current filters.';		
	}
	
	$output .= '<span class="view_all"><a href="/sales/download-centre/">View All Downloads</a></span>';
	
	$output .= '</div>';
		
	// Reset post data query
	wp_reset_postdata();

	return $output;
}

add_shortcode ( 'wpdm_cmlabs_documents_recent', 'cmlabs_wpdm_documents_recent' );

add_action( 'admin_init', 'cmlabs_wpdm_admin_init' );

function cmlabs_wpdm_admin_init() {
	add_meta_box( 'dbox_download_details',
		'Download CM Labs Details',
		'cmlabs_download_details',
		'wpdmpro', 'normal', 'high' );
}

function cmlabs_download_details( $download_item ) {
	$wpdm_data = get_post_meta( $download_item->ID, '__wpdmpro_custom_fields', true );
	$release_date_timestamp = get_post_meta( $download_item->ID, 'release_date', true );
	if ( false != $release_date_timestamp && !empty( $release_date_timestamp ) ) {
		$human_date = date( 'm/d/Y', $release_date_timestamp );
	} else {
		$human_date = date( 'm/d/Y' );
	}
	$external_url = esc_url( get_post_meta( $download_item->ID, 'external_url', true ) );
	$size_override = esc_html( get_post_meta( $download_item->ID, 'size_override', true ) );
	
	?>

	<table>
		<tr>
			<td style="width: 100px">Release Date</td>
			<td><input type="text" size="20"
			           name="release_date" id="release_date"
			           value="<?php echo $human_date; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 100px">External URL</td>
			<td><input type="text" size="100"
			           name="external_url" id="external_url"
			           value="<?php echo $external_url; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 100px">Size Override</td>
			<td><input type="text" size="16"
			           name="size_override" id="size_override"
			           value="<?php echo $size_override; ?>" /></td>
		</tr>
		<tr>
			<td>Item Icon</td>
			<td>
				<?php
					$iconpath = get_post_meta( $download_item->ID, '__wpdm_icon', true );
					
					if ( !empty( $iconpath ) ) {
						$link = $iconpath;
						$link_array = explode( '/', $link );
						$array_size = count( $link_array );
						
						$filename = $link_array[$array_size - 1];
						$periodpos = strpos( $filename, '.' );
						if ( false !== $periodpos ) {
							$filename = substr( $filename, 0, $periodpos );
						}
					}
					
					$icons_array = array( 'vortex-dark', 'vortex-light', 'pdf-icon', 'mpg-icon', 'doc-icon', 'ppt-icon', 'xls-icon', 'Apps-Package-icon', 'book-icon', 'image-icon', 'brochure-icon', 'notes-icon', 'documentation-icon', 'story-icon', 'key-icon', 'webinar-icon', 'Apps-Actions-Cut-icon' );
					foreach ( $icons_array as $icon_name ) { ?>
						<div class="wpdm_cml_icons<?php echo ( $filename == $icon_name ? ' wpdm_cml_icon_selected' : '' ); ?>" id="<?php echo $icon_name; ?>"><img src="<?php echo plugins_url( 'images/' . $icon_name . '.png', __FILE__ ); ?>" /></div>
				<?php } ?> 
			</td>
		</tr>
	</table>

	<script type='text/javascript'>
		jQuery(document).ready(function() {
			jQuery('#release_date').datepicker({dateFormat: 'mm/dd/yy', showOn: 'both', constrainInput: true, buttonImage: '<?php echo plugins_url( 'icons/calendar.png', __FILE__ ); ?>'});
						
			jQuery('.wpdm_cml_icons').click( function() {
				filename = '<?php echo plugins_url( '', __FILE__ ); ?>/images/' + jQuery( this ).attr('id') + '.png';
				jQuery( '#wpdmiconurl' ).val( filename );
				jQuery( '.wpdm_cml_icons' ).each( function() {
					jQuery( this ).removeClass( 'wpdm_cml_icon_selected' );
				});
				jQuery( this ).addClass( 'wpdm_cml_icon_selected' );
			});
		});
	</script>
	<?php
}

add_action( 'admin_enqueue_scripts', 'cmlabs_wpdm_admin_scripts' );

function cmlabs_wpdm_admin_scripts() {
	wp_enqueue_script( 'datepicker', plugins_url( 'js/ui.datepicker.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_style( 'datepickercss', plugins_url( 'css/ui-lightness/jquery-ui-1.8.4.custom.css', __FILE__ ) );
	wp_enqueue_style( 'cml_wpdm_admin_style', plugins_url( 'css/adminstyle.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'cmlabs_wpdm_scripts' );

function cmlabs_wpdm_scripts() {
	wp_enqueue_style( 'cml_wpdm_style', plugins_url( 'css/cml_wpdm_style.css', __FILE__ ) );
}

add_action( 'save_post', 'cmlabs_wpdm_add_fields', 30, 2 );

function human_filesize($size, $precision = 2) {
    $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision). ' ' . $units[$i];
}

function cmlabs_wpdm_add_fields( $download_id, $download ) {

	// Check post type for book reviews
	if ( $download->post_type == 'wpdmpro' ) {
		// Store data in post meta table if present in post data
		if ( isset( $_POST['release_date'] ) && ! empty( $_POST['release_date'] ) ) {
			$date_timestamp = strtotime( $_POST['release_date'] );
			update_post_meta( $download_id, 'release_date', $date_timestamp );
		}
		
		if ( isset( $_POST['external_url'] ) ) {
			update_post_meta( $download_id, 'external_url', esc_url( $_POST['external_url'] ) );
		}
		
		if ( isset( $_POST['size_override'] ) ) {
			update_post_meta( $download_id, 'size_override', esc_html( $_POST['size_override'] ) );
		}
		
		$access_rights = get_post_meta( $download_id, '__wpdm_access', true );
		
		if ( 1 == count( $access_rights ) && 'guest' == $access_rights[0] ) {
			$new_access_rights = array( 'subscriber', 'contributor', 'author', 'editor', 'administrator' );
			update_post_meta( $download_id, '__wpdm_access', $new_access_rights );
		}
		
		$package_size = get_post_meta( $download_id, '__wpdm_package_size', true );
		$files_list = get_post_meta( $download_id, '__wpdm_files', true );
		
		if ( empty( $package_size ) && !empty( $files_list ) ) {			
			foreach ( $files_list as $index => $file ) {
				$upload_dir = wp_upload_dir();
				$file_path = $upload_dir['basedir'] . '/download-manager-files/' . $file;
				$byte_size = filesize( $file_path );
				$human_size = human_filesize( $byte_size, 2 );
								
				update_post_meta( $download_id, '__wpdm_package_size', $human_size );
			}
		}
		
		if ( !empty( $files_list ) ) {
			foreach ( $files_list as $index => $file ) {
				$upload_dir = wp_upload_dir();
				$file_path = $upload_dir['basedir'] . '/download-manager-files/' . $file;
				
				$marker_pos = strpos( $file, 'wpdm_' );
				
				if ( false !== $marker_pos ) {
					$shorter_file = substr( $file, $marker_pos + 5 );
					if ( !empty( $shorter_file ) ) {
						$files_list[$index] = $shorter_file;
						$shorter_file_path = $upload_dir['basedir'] . '/download-manager-files/' . $shorter_file;
						rename( $file_path, $shorter_file_path );
					}
				}
			}
		}
			
		update_post_meta( $download_id, '__wpdm_files', $files_list );
		
		global $wpdb;
		
		$group_results = $wpdb->get_results( 'select * from wp_uam_accessgroup_to_object where object_id = ' . $download_id );
		
		if ( empty( $group_results ) ) {
			$wpdb->insert( 'wp_uam_accessgroup_to_object', array( 'object_id' => $download_id, 'object_type' => 'wpdmpro', 'group_id' => 1 ) );
			$wpdb->insert( 'wp_uam_accessgroup_to_object', array( 'object_id' => $download_id, 'object_type' => 'wpdmpro', 'group_id' => 2 ) );
		}
		
		//wp_uam_accessgroup_to_object table
		//object_id -> post_id
		// object_type -> wpdmpro
		// group_id -> 1 = sales, 2 = resellers, 3 = partners
	}
}

add_filter( 'wpdm_check_lock', 'cmlabs_wpdm_lock', 10, 2 );

function cmlabs_wpdm_lock( $lock, $id ) {

	if ( !is_user_logged_in() ) {
		wp_redirect( home_url() );
		exit;
	}
			
	// Preparation of query array to retrieve 5 book reviews
	$query_params = array( 'post_type' => 'wpdmpro',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'p' => $id 
		 );
		 
	// Execution of post query
	$doc_query = new WP_Query;
	$doc_query->query( $query_params );
	
	if ( $doc_query->found_posts > 0 ) {		
		return $lock;
	} else {
		return true;
	}
	
	wp_reset_query();
}

register_activation_hook(__FILE__, 'cml_wpdm_activation');

function cml_wpdm_activation() {
	wp_schedule_event( time(), 'hourly', 'cmlabs_wpdm_demo_licenses_update' );
}

add_action( 'cmlabs_wpdm_demo_licenses_update', 'cmlabs_update_licenses' );

function cmlabs_update_licenses() {
	$date_timestamp = current_time( 'timestamp' ); 
	update_post_meta( 814, 'release_date', $date_timestamp );
	update_post_meta( 812, 'release_date', $date_timestamp );	
	update_post_meta( 1869, 'release_date', $date_timestamp );
	update_post_meta( 1867, 'release_date', $date_timestamp );	
}

register_deactivation_hook(__FILE__, 'cml_wpdm_activation');

function my_deactivation() {
	wp_clear_scheduled_hook('cmlabs_wpdm_demo_licenses_update');
}

add_action( 'restrict_manage_posts', 'cml_wpdm_doc_type_filter_list' );

function cml_wpdm_doc_type_filter_list() {
	$screen = get_current_screen();
	global $wp_query;

	if ( $screen->post_type == 'wpdmpro' ) {
		wp_dropdown_categories(array(
			'show_option_all' => 'Show All Document Types',
			'taxonomy' => 'wpdmcategory',
			'name' => 'wpdmcategory',
			'orderby' => 'name',
			'selected' =>
				( isset( $wp_query->query['wpdmcategory'] ) ?
					$wp_query->query['wpdmcategory'] : '' ),
			'hierarchical' => false,
			'depth' => 3,
			'show_count' => false,
			'hide_empty' => true,
			)
		);
	}
}

add_filter( 'parse_query', 'cml_wpdm_perform_doc_type_filtering' );

function cml_wpdm_perform_doc_type_filtering( $query ) {
	$qv = &$query->query_vars;
	
	if ( !empty( $qv['wpdmcategory'] ) && is_numeric( $qv['wpdmcategory'] ) ) {	
		$term = get_term_by( 'id', $qv['wpdmcategory'], 'wpdmcategory' );
		$qv['wpdmcategory'] = $term->slug;
	}
}

function array_insert(&$array, $position, $insert)
{
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}

add_filter( 'manage_edit-wpdmpro_columns', 'cml_wpdm_add_columns' );

function cml_wpdm_add_columns( $columns ) {
	unset( $columns['wpdmshortcode'] );
	unset( $columns['author'] );
	unset( $columns['tags'] );
	unset( $columns['comments'] );
	unset( $columns['download_count'] );
	
	$newcolumns['download_per_group'] = 'Downloads per group';
	
	$columns = array_merge( array_slice( $columns, 0, 3 ), $newcolumns, array_slice( $columns, 3 ) );
	
	return $columns;
}

add_action( 'wpdm_onstart_download', 'cml_wpdm_onstart_download' );

function cml_wpdm_onstart_download( $package ) {

	$current_user = wp_get_current_user();
	
	if ( !empty( $current_user ) ) {
		global $wpdb;
			
		$query = 'select group_id from wp_uam_accessgroup_to_object where object_type = "user" and object_id = ' . $current_user->ID;
		
		$group_id = $wpdb->get_var( $query );
		
		if ( !empty( $group_id ) ) {
			$meta_key = 'download_count_' . $group_id;
			$download_count = intval( get_post_meta( $package['ID'], $meta_key, true ) );
			$download_count++;
			update_post_meta( $package['ID'], $meta_key, $download_count );
		}		
	}
}

add_action( 'manage_posts_custom_column', 'cml_wpdm_populate_columns' );

function cml_wpdm_populate_columns( $column ) {
	if ( 'download_per_group' == $column ) {
		global $wpdb;
		
		$group_query = 'select * from wp_uam_accessgroups';
		$group_list = $wpdb->get_results( $group_query );
		
		$entry_index = 0;		
		if ( !empty( $group_list ) ) {
			foreach ( $group_list as $group ) {
				$download_count = intval( get_post_meta( get_the_ID(), 'download_count_' . $group->ID, true ) );
				
				if ( $entry_index != 0 ) {
					echo ' / ';
				}
				
				echo '<span title="' . $group->groupname . '">';
				if ( !empty( $download_count ) ) {
					echo $download_count;
				} else {
					echo '0';
				}
				echo '</span>';
				$entry_index++;
			}
		}
	} 
}

//add_filter( 'wpdm_after_upload_file', 'cml_after_upload_file' );

function cml_after_upload_file ( $filename ) {
	$prefixpos = strpos( $filename, 'wpdm_' );
	if ( false !== $prefixpos ) {
		$filename = substr( $filename, $prefixpos + 5 );
	}
	
	return $filename;
}