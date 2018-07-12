<?php
/*
Plugin Name: Promociones - KNX
Text Domain: knx-promociones
Plugin URI: http://kinexo.com
Description: Administrar promociones nunca fue tan sencillo! - Requiere CMB2 Plugin
Version: 0.9
Author: Mayco
Author URI: https://www.linkedin.com/in/mayco-barale-2563815a/
License: GPLv2 o posterior
*/

/*	Registro Custom Post */
if ( ! function_exists('knx_setup_promociones') ) {
	// Register Custom Post Type
	function knx_setup_promociones() {
		$labels = array(
			'name'                  => _x( 'Promociones', 'Post Type General Name', 'knx-promociones' ),
			'singular_name'         => _x( 'Promocion', 'Post Type Singular Name', 'knx-promociones' ),
			'menu_name'             => __( 'Promociones', 'knx-promociones' ),
			'name_admin_bar'        => __( 'Promocion', 'knx-promociones' ),
			'archives'              => __( 'Items archives', 'knx-promociones' ),
			'attributes'            => __( 'Item Attributes', 'knx-promociones' ),
			'parent_item_colon'     => __( 'Parent Item:', 'knx-promociones' ),
			'all_items'             => __( 'Todos las promociones', 'knx-promociones' ),
			'add_new_item'          => __( 'Agregar nueva promocion', 'knx-promociones' ),
			'add_new'               => __( 'Agregar nueva', 'knx-promociones' ),
			'new_item'              => __( 'Nueva promocion', 'knx-promociones' ),
			'edit_item'             => __( 'Editar promocion', 'knx-promociones' ),
			'update_item'           => __( 'Actualizar promocion', 'knx-promociones' ),
			'view_item'             => __( 'Ver promocion', 'knx-promociones' ),
			'view_items'            => __( 'Ver promociones', 'knx-promociones' ),
			'search_items'          => __( 'Buscar promocion', 'knx-promociones' ),
			'not_found'             => __( 'No encontrado', 'knx-promociones' ),
			'not_found_in_trash'    => __( 'No encontrado en la papelera', 'knx-promociones' ),
			'featured_image'        => __( 'Imagen destacada', 'knx-promociones' ),
			'set_featured_image'    => __( 'Agregar imagen destacada', 'knx-promociones' ),
			'remove_featured_image' => __( 'Remover imagen destacada', 'knx-promociones' ),
			'use_featured_image'    => __( 'Usar como imagen destacada', 'knx-promociones' ),
			'insert_into_item'      => __( 'Insertar en promocion', 'knx-promociones' ),
			'uploaded_to_this_item' => __( 'Cargado a esta promocion', 'knx-promociones' ),
			'items_list'            => __( 'Promociones lista', 'knx-promociones' ),
			'items_list_navigation' => __( 'Promociones lista de navegacion', 'knx-promociones' ),
			'filter_items_list'     => __( 'Filtrar promociones', 'knx-promociones' ),
		);
		$args = array(
			'label'                 => __( 'Promocion', 'knx-promociones' ),
			'description'           => __( 'Para administrar promociones', 'knx-promociones' ),
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

/*	incluimos core de plugin cmb2 en el caso de que no este instalado como plugin en el WP	*/
// require_once plugin_dir_path( __DIR__ ) . 'cmb2/init.php';

/*	Agregamos metabox al CP */
add_action( 'cmb2_admin_init', 'cmb2_promocion_metaboxes' );
/** Define the metabox and field configurations. */
function cmb2_promocion_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_knx-promo_';

	/** Initiate the metabox	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'datos_sobre_la_promocion',
		'title'         => __( 'Datos sobre la promocion', 'cmb2' ),
		'object_types'  => array( 'promocion', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );

	// vencimiento field
	$cmb->add_field( array(
		'name' => __( 'Valido hasta', 'knx-promocion' ),
		'id'   => $prefix . 'valido_hasta',
		'type' => 'text_date_timestamp',
		'desc' => esc_html__( 'Fecha hasta la cual sera valida la promocion.', 'cmb2' ),
	) );
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