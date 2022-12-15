<?php
/*
Plugin Name: Nacelle CPT & Tax
Plugin URI: https://wp.tutsplus.com/
Description: Creates custom post types and taxonomies for Nacelle.
Version: 1.0
Author: Luke Carl Hartman
Author URI: https://github.com/luukee
License: GPLv2
Link: https://code.tutsplus.com/tutorials/a-guide-to-wordpress-custom-post-types-creation-display-and-meta-boxes--wp-27645
*/

defined( 'ABSPATH' ) or die( 'Hey what you doing?' );

class NacelleCreateCPTandTax {

	function __construct() {
		add_action( 'init', array( $this, 'nacelle_custom_post_type' ) );
		add_action( 'init', array( $this, 'nacelle_register_my_taxes' ) );
	}

	function activate() {
		$this->nacelle_custom_post_type();
		$this->nacelle_register_my_taxes();
		flush_rewrite_rules();
	}

	function deactivate() {
		// flush rewrite rules
		flush_rewrite_rules();
	}

	function nacelle_custom_post_type() {

		/**
		 * Post Type: Products.
		 */

		$labels = array(
			'name'                     => __( 'Products', 'wp-rig' ),
			'singular_name'            => __( 'Product', 'wp-rig' ),
			'menu_name'                => __( 'Products', 'wp-rig' ),
			'all_items'                => __( 'All Products', 'wp-rig' ),
			'add_new'                  => __( 'Add new', 'wp-rig' ),
			'add_new_item'             => __( 'Add new Product', 'wp-rig' ),
			'edit_item'                => __( 'Edit Product', 'wp-rig' ),
			'new_item'                 => __( 'New Product', 'wp-rig' ),
			'view_item'                => __( 'View Product', 'wp-rig' ),
			'view_items'               => __( 'View Products', 'wp-rig' ),
			'search_items'             => __( 'Search Products', 'wp-rig' ),
			'not_found'                => __( 'No Products found', 'wp-rig' ),
			'not_found_in_trash'       => __( 'No Products found in trash', 'wp-rig' ),
			'parent'                   => __( 'Parent Product:', 'wp-rig' ),
			'featured_image'           => __( 'Featured image for this Product', 'wp-rig' ),
			'set_featured_image'       => __( 'Set featured image for this Product', 'wp-rig' ),
			'remove_featured_image'    => __( 'Remove featured image for this Product', 'wp-rig' ),
			'use_featured_image'       => __( 'Use as featured image for this Product', 'wp-rig' ),
			'archives'                 => __( 'Product archives', 'wp-rig' ),
			'insert_into_item'         => __( 'Insert into Product', 'wp-rig' ),
			'uploaded_to_this_item'    => __( 'Upload to this Product', 'wp-rig' ),
			'filter_items_list'        => __( 'Filter Products list', 'wp-rig' ),
			'items_list_navigation'    => __( 'Products list navigation', 'wp-rig' ),
			'items_list'               => __( 'Products list', 'wp-rig' ),
			'attributes'               => __( 'Products attributes', 'wp-rig' ),
			'name_admin_bar'           => __( 'Product', 'wp-rig' ),
			'item_published'           => __( 'Product published', 'wp-rig' ),
			'item_published_privately' => __( 'Product published privately.', 'wp-rig' ),
			'item_reverted_to_draft'   => __( 'Product reverted to draft.', 'wp-rig' ),
			'item_scheduled'           => __( 'Product scheduled', 'wp-rig' ),
			'item_updated'             => __( 'Product updated.', 'wp-rig' ),
			'parent_item_colon'        => __( 'Parent Product:', 'wp-rig' ),
		);

		$args = array(
			'label'                 => __( 'Products', 'wp-rig' ),
			'labels'                => $labels,
			'description'           => 'Nacelle product archive.',
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => 'catalog',
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => true,
			'rewrite'               => array(
				'slug'       => 'catalog',
				'with_front' => false,
			),
			'query_var'             => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-album',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),
			'taxonomies'            => array( 'category', 'main_talent', 'genre', 'producers', 'media_category', 'directors', 'writers', 'cd_category' ),
			'show_in_graphql'       => false,
		);

		register_post_type( 'catalog', $args );

		/**
		 * Post Type: Press.
		 */

		$labels = array(
			'name'          => __( 'Press', 'wp-rig' ),
			'singular_name' => __( 'Press', 'wp-rig' ),
		);

		$args = array(
			'label'                 => __( 'Press', 'wp-rig' ),
			'labels'                => $labels,
			'description'           => 'Nacelle press posts.',
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'page',
			'map_meta_cap'          => true,
			'hierarchical'          => false,
			'rewrite'               => array(
				'slug'       => 'press',
				'with_front' => true,
			),
			'query_var'             => true,
			'menu_position'         => 4,
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'show_in_graphql'       => false,
		);

		register_post_type( 'press', $args );
	}

	function nacelle_register_my_taxes() {
		/**
		 * Taxonomy: Main Talent.
		 */

		$labels = array(
			'name'                       => __( 'Main Talents', 'custom-post-type-ui' ),
			'singular_name'              => __( 'Main Talent', 'custom-post-type-ui' ),
			'menu_name'                  => __( 'Main Talent', 'custom-post-type-ui' ),
			'all_items'                  => __( 'All Main Talents', 'custom-post-type-ui' ),
			'edit_item'                  => __( 'Edit Main Talent', 'custom-post-type-ui' ),
			'view_item'                  => __( 'View Main Talent', 'custom-post-type-ui' ),
			'update_item'                => __( 'Update Main Talent name', 'custom-post-type-ui' ),
			'add_new_item'               => __( 'Add new Main Talent', 'custom-post-type-ui' ),
			'new_item_name'              => __( 'New Main Talents name', 'custom-post-type-ui' ),
			'parent_item'                => __( 'Parent Main Talent', 'custom-post-type-ui' ),
			'parent_item_colon'          => __( 'Parent Main Talent:', 'custom-post-type-ui' ),
			'search_items'               => __( 'Search Main Talent', 'custom-post-type-ui' ),
			'popular_items'              => __( 'Popular Main Talent', 'custom-post-type-ui' ),
			'separate_items_with_commas' => __( 'Separate Main Talent with commas', 'custom-post-type-ui' ),
			'add_or_remove_items'        => __( 'Add or remove Main Talent', 'custom-post-type-ui' ),
			'choose_from_most_used'      => __( 'Choose from the most used Main Talent', 'custom-post-type-ui' ),
			'not_found'                  => __( 'No Main Talent found', 'custom-post-type-ui' ),
			'no_terms'                   => __( 'No Main Talent', 'custom-post-type-ui' ),
			'items_list_navigation'      => __( 'Main Talent list navigation', 'custom-post-type-ui' ),
			'items_list'                 => __( 'Main Talent list', 'custom-post-type-ui' ),
		);

		$args = array(
			'label'                 => __( 'Main Talent', 'custom-post-type-ui' ),
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'       => 'main-talent',
				'with_front' => true,
			),
			'show_admin_column'     => false,
			'show_in_rest'          => true,
			'rest_base'             => 'main_talent',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => true,
		);
		register_taxonomy( 'main_talent', array( 'catalog' ), $args );

		/**
		 * Taxonomy: Genre.
		 */

		$labels = array(
			'name'          => __( 'Genres', 'custom-post-type-ui' ),
			'singular_name' => __( 'Genre', 'custom-post-type-ui' ),
		);

		$args = array(
			'label'                 => __( 'Genre', 'custom-post-type-ui' ),
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => false,
			'show_ui'               => false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'       => 'genre',
				'with_front' => true,
			),
			'show_admin_column'     => true,
			'show_in_rest'          => true,
			'rest_base'             => 'genre',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => true,
		);
		register_taxonomy( 'genre', array( 'catalog' ), $args );

		/**
		 * Taxonomy: Producers.
		 */

		$labels = array(
			'name'                       => __( 'Producers', 'custom-post-type-ui' ),
			'singular_name'              => __( 'Producer', 'custom-post-type-ui' ),
			'menu_name'                  => __( 'Producers', 'custom-post-type-ui' ),
			'all_items'                  => __( 'All Producers', 'custom-post-type-ui' ),
			'edit_item'                  => __( 'Edit Producer', 'custom-post-type-ui' ),
			'view_item'                  => __( 'View Producer', 'custom-post-type-ui' ),
			'update_item'                => __( 'Update Producer name', 'custom-post-type-ui' ),
			'add_new_item'               => __( 'Add new Producer', 'custom-post-type-ui' ),
			'new_item_name'              => __( 'New Producer name', 'custom-post-type-ui' ),
			'parent_item'                => __( 'Parent Producer', 'custom-post-type-ui' ),
			'parent_item_colon'          => __( 'Parent Producer:', 'custom-post-type-ui' ),
			'search_items'               => __( 'Search Producers', 'custom-post-type-ui' ),
			'popular_items'              => __( 'Popular Producers', 'custom-post-type-ui' ),
			'separate_items_with_commas' => __( 'Separate Producers with commas', 'custom-post-type-ui' ),
			'add_or_remove_items'        => __( 'Add or remove Producers', 'custom-post-type-ui' ),
			'choose_from_most_used'      => __( 'Choose from the most used Producers', 'custom-post-type-ui' ),
			'not_found'                  => __( 'No Producers found', 'custom-post-type-ui' ),
			'no_terms'                   => __( 'No Producers', 'custom-post-type-ui' ),
			'items_list_navigation'      => __( 'Producers list navigation', 'custom-post-type-ui' ),
			'items_list'                 => __( 'Producers list', 'custom-post-type-ui' ),
		);

		$args = array(
			'label'                 => __( 'Producers', 'custom-post-type-ui' ),
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'       => 'producers',
				'with_front' => true,
			),
			'show_admin_column'     => true,
			'show_in_rest'          => false,
			'rest_base'             => 'producers',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => false,
		);
		register_taxonomy( 'producers', array( 'catalog' ), $args );

		/**
		 * Taxonomy: Directors.
		 */

		$labels = array(
			'name'                       => __( 'Directors', 'custom-post-type-ui' ),
			'singular_name'              => __( 'Director', 'custom-post-type-ui' ),
			'menu_name'                  => __( 'Directors', 'custom-post-type-ui' ),
			'all_items'                  => __( 'All Directors', 'custom-post-type-ui' ),
			'edit_item'                  => __( 'Edit Director', 'custom-post-type-ui' ),
			'view_item'                  => __( 'View Director', 'custom-post-type-ui' ),
			'update_item'                => __( 'Update Director name', 'custom-post-type-ui' ),
			'add_new_item'               => __( 'Add new Director', 'custom-post-type-ui' ),
			'new_item_name'              => __( 'New Director name', 'custom-post-type-ui' ),
			'parent_item'                => __( 'Parent Director', 'custom-post-type-ui' ),
			'parent_item_colon'          => __( 'Parent Director:', 'custom-post-type-ui' ),
			'search_items'               => __( 'Search Directors', 'custom-post-type-ui' ),
			'popular_items'              => __( 'Popular Directors', 'custom-post-type-ui' ),
			'separate_items_with_commas' => __( 'Separate Directors with commas', 'custom-post-type-ui' ),
			'add_or_remove_items'        => __( 'Add or remove Directors', 'custom-post-type-ui' ),
			'choose_from_most_used'      => __( 'Choose from the most used Directors', 'custom-post-type-ui' ),
			'not_found'                  => __( 'No Directors found', 'custom-post-type-ui' ),
			'no_terms'                   => __( 'No Directors', 'custom-post-type-ui' ),
			'items_list_navigation'      => __( 'Directors list navigation', 'custom-post-type-ui' ),
			'items_list'                 => __( 'Directors list', 'custom-post-type-ui' ),
		);

		$args = array(
			'label'                 => __( 'Directors', 'custom-post-type-ui' ),
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'       => 'directors',
				'with_front' => true,
			),
			'show_admin_column'     => true,
			'show_in_rest'          => false,
			'rest_base'             => 'directors',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => false,
		);
		register_taxonomy( 'directors', array( 'catalog' ), $args );

		/**
		 * Taxonomy: Writers.
		 */

		$labels = array(
			'name'          => __( 'Writers', 'custom-post-type-ui' ),
			'singular_name' => __( 'Writer', 'custom-post-type-ui' ),
		);

		$args = array(
			'label'                 => __( 'Writers', 'custom-post-type-ui' ),
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'       => 'writers',
				'with_front' => true,
			),
			'show_admin_column'     => true,
			'show_in_rest'          => false,
			'rest_base'             => 'writers',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => false,
		);
		register_taxonomy( 'writers', array( 'catalog' ), $args );

	}
}

if ( class_exists( 'NacelleCreateCPTandTax' ) ) {

	$nacelle_cpt_tax = new NacelleCreateCPTandTax();

}


// activation
register_activation_hook( __FILE__, array( $nacelle_cpt_tax, 'activate' ) );

// deactivation
register_deactivation_hook( __FILE__, array( $nacelle_cpt_tax, 'deactivate' ) );
