<?php
/*
Plugin Name: SportsPress Trophies
Plugin URI: http://themeboy.com/
Description: Add trophies feature to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.8.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Trophies' ) ) :

/**
 * Main SportsPress Trophies Class
 *
 * @class SportsPress_Trophies
 * @version	2.8.0
 */
class SportsPress_Trophies {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'init', array( $this, 'register_post_type' ) );


		// Filters
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TROPHIES_VERSION' ) )
			define( 'SP_TROPHIES_VERSION', '2.8.0' );

		if ( !defined( 'SP_TROPHIES_URL' ) )
			define( 'SP_TROPHIES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TROPHIES_DIR' ) )
			define( 'SP_TROPHIES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register league tables post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_trophy',
			apply_filters( 'sportspress_register_post_type_trophy',
				array(
					'labels' => array(
						'name' 					=> __( 'Trophies', 'sportspress' ),
						'singular_name' 		=> __( 'Trophy', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Trophy', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Trophy', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Trophy', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_trophy',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_trophy_slug', 'trophy' ) ),
					'supports' 				=> array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_team',
					'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'trophies',
				)
			)
		);
	}
	
	/**
	 * Add post type
	 */
	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_trophy';
		return $post_types;
	}
	
	/**
	 * Add meta boxes to trophies.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_trophy'] = array(
			'stats' => array(
				'title' => __( 'Statistics', 'sportspress' ),
				'save' => 'SP_Meta_Box_Trophy_Statistics::save',
				'output' => 'SP_Meta_Box_Trophy_Statistics::output',
				'context' => 'normal',
				'priority' => 'high',
			),
		);
		return $meta_boxes;
	}
}

endif;

if ( get_option( 'sportspress_load_trophies_module', 'yes' ) == 'yes' ) {
	new SportsPress_Trophies();
}
