<?php
/*
Plugin Name: SportsPress Flexible H2H
Plugin URI: https://themeboy.com/
Description: Add the option to the user to select in which order the H2H criteria will take effect.
Author: ThemeBoy
Author URI: https://themeboy.com/
Version: 2.8.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Flexible_H2H' ) ) :

/**
 * Main SportsPress Flexible H2H Class
 *
 * @class SportsPress_Flexible_H2H
 * @version	2.8.0
 */
class SportsPress_Flexible_H2H {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'sportspress_table_options', array( $this, 'add_settings' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_FLEXIBLE_H2H_VERSION' ) )
			define( 'SP_FLEXIBLE_H2H_VERSION', '2.8.0' );

		if ( !defined( 'SP_FLEXIBLE_H2H_URL' ) )
			define( 'SP_FLEXIBLE_H2H_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_FLEXIBLE_H2H_DIR' ) )
			define( 'SP_FLEXIBLE_H2H_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$args = array(
			'post_type' => 'sp_column',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );
		$priorities = array();
		$priorities['first'] = __( 'First', 'sportspress' );
		foreach ( $stats as $stat ) {
			$priority_stat = get_post_meta( $stat->ID, 'sp_priority', true );
			if ( $priority_stat )
				$priorities[ $stat->post_name ] = 'After ' . $stat->post_title;
		}
		$priorities['last'] = __( 'Last (Default)', 'sportspress' );
		//var_dump($stats);
		$settings[] = array(
						'title'     => __( 'Tiebreaker Order', 'sportspress' ),
						'id' 		=> 'sportspress_table_tiebreaker_order',
						'default'   => 'last',
						'type'      => 'select',
						'options'   => $priorities,
					);
		return $settings;
	}
}

endif;

new SportsPress_Flexible_H2H();
