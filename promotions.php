<?php
/*
Plugin Name: WP Promotion manager
Plugin URI: http://kinexo.com
Description: Managing promotions has never been so easy!
Version: 0.5
Author: Mayco Barale
Author URI: https://www.linkedin.com/in/mayco-barale-2563815a/
Text Domain: knx-promotions
License: GPLv2 o posterior
*/

/*	Registro Custom Post */

if ( ! function_exists('knx_setup_promociones') ) {
	// Register Custom Post Type
	function knx_setup_promociones() {
		$labels = array(
			'name'                  => _x( 'Promotions', 'Post Type General Name', 'knx-promotions' ),
			'singular_name'         => _x( 'Promotion', 'Post Type Singular Name', 'knx-promotions' ),
			'menu_name'             => __( 'Promotions', 'knx-promotions' ),
			'name_admin_bar'        => __( 'Promotion', 'knx-promotions' ),
			'archives'              => __( 'Items archives', 'knx-promotions' ),
			'attributes'            => __( 'Item Attributes', 'knx-promotions' ),
			'parent_item_colon'     => __( 'Parent Item:', 'knx-promotions' ),
			'all_items'             => __( 'All items', 'knx-promotions' ),
			'add_new_item'          => __( 'Add new item', 'knx-promotions' ),
			'add_new'               => __( 'Add new', 'knx-promotions' ),
			'new_item'              => __( 'New item', 'knx-promotions' ),
			'edit_item'             => __( 'Edit item', 'knx-promotions' ),
			'update_item'           => __( 'Update item', 'knx-promotions' ),
			'view_item'             => __( 'View item', 'knx-promotions' ),
			'view_items'            => __( 'View items', 'knx-promotions' ),
			'search_items'          => __( 'Search items', 'knx-promotions' ),
			'not_found'             => __( 'Not found', 'knx-promotions' ),
			'not_found_in_trash'    => __( 'Not found in trash', 'knx-promotions' ),
			'featured_image'        => __( 'Featured image', 'knx-promotions' ),
			'set_featured_image'    => __( 'Set featured image', 'knx-promotions' ),
			'remove_featured_image' => __( 'Remove featured image', 'knx-promotions' ),
			'use_featured_image'    => __( 'Use featured image', 'knx-promotions' ),
			'insert_into_item'      => __( 'Insert in to item', 'knx-promotions' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'knx-promotions' ),
			'items_list'            => __( 'Items list', 'knx-promotions' ),
			'items_list_navigation' => __( 'Items list navigation', 'knx-promotions' ),
			'filter_items_list'     => __( 'Filter items list', 'knx-promotions' ),
		);
		$args = array(
			'label'                 => __( 'Promotion', 'knx-promotions' ),
			'description'           => __( 'To manage promotions', 'knx-promotions' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-controls-repeat',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'promocion', $args );
	}
	add_action( 'init', 'knx_setup_promociones', 0 );
}

/*	Agregamos metabox al CP */
add_action( 'cmb2_admin_init', 'cmb2_promocion_metaboxes' );
/** Define the metabox and field configurations. */
function cmb2_promocion_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_knx-promo_';

	/** Initiate the metabox	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'datos_sobre_la_promocion',
		'title'         => __( 'Promo information', 'cmb2' ),
		'object_types'  => array( 'promocion', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );

	// vencimiento field
	$cmb->add_field( array(
		'name' => __( 'End date', 'knx-promocion' ),
		'id'   => $prefix . 'valido_hasta',
		'type' => 'text_date_timestamp',
		'desc' => esc_html__( 'End date for the promotion.', 'cmb2' ),
	) );
}

// Add the custom columns to the promocion post type: redefined array of columns
// Mantenemos el nombre PROMOCION, porque el Custom Post ya estaba registrado 
add_filter( 'manage_promocion_posts_columns', 'promocion_columns' );
function promocion_columns( $columns ) {
    $columns = array(
      'cb' => $columns['cb'],
      'title' => __( 'Title' ),
      'end_date' => __( 'End date', 'knx-events' ),
      'date' => __( 'Date'),
    ); 
  return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_promocion_posts_custom_column' , 'custom_promocion_column', 10, 2 );
function custom_promocion_column( $column, $post_id ) {
  switch ( $column ) {
	  case 'end_date' :
      $terms = get_post_meta( $post_id, '_knx-promo_valido_hasta', true );
      echo gmdate('d/m/Y', $terms);
    break;
  }
}

// Make Column 'End Date' Sortable, add column to sortable columns array
add_filter( 'manage_edit-promocion_sortable_columns', 'promocion_sortable_column');
function promocion_sortable_column( $columns ) {
  $columns['end_date'] = '_knx-promo_valido_hasta';
  return $columns;
}

// Make query to order by '_knx-promo_valido_hasta', type of column set to 'text_date_timestamp'
add_action( 'pre_get_posts', 'promotion_end_date_posts_orderby' );
function promotion_end_date_posts_orderby( $query ) {
  if( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }
  if ( '_knx-promo_valido_hasta' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', '_knx-promo_valido_hasta' );
    $query->set( 'meta_type', 'text_date_timestamp' );
  }
}

/*	Registramos modulo a Visual Composer */
if( function_exists( 'vc_manager' ) ) {

	// Before VC Init
	add_action( 'vc_before_init', 'knx_vc_promociones' );
	function knx_vc_promociones() {
    // Require new custom Element
    require_once( plugin_dir_path( __FILE__ ) . 'vc_elements/promocion-shortcode.php' );
	}
}