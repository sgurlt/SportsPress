<?php
/*
Plugin Name: SportsPress ical Event Status
Plugin URI: http://themeboy.com/
Description: Add the event status in the summary of the ical event feed.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.8
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Ical_Event_Status' ) ) :

/**
 * Main SportsPress Ical Event Status Class
 *
 * @class SportsPress_Ical_Event_Status
 * @version	2.8
 */
class SportsPress_Ical_Event_Status {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'sportspress_ical_feed_summary', array( $this, 'add_event_status' ), 10, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_ICAL_EVENT_STATUS_VERSION' ) )
			define( 'SP_ICAL_EVENT_STATUS_VERSION', '2.8' );

		if ( !defined( 'SP_ICAL_EVENT_STATUS_URL' ) )
			define( 'SP_ICAL_EVENT_STATUS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_ICAL_EVENT_STATUS_DIR' ) )
			define( 'SP_ICAL_EVENT_STATUS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add option to SportsPress General Settings.
	 */
	public function add_event_status( $summary, $event ) {
		// Get event status 
		$status = get_post_meta( $event->ID, 'sp_status', true );
		
		if ( $status && 'ok' != $status ) {
			$statuses = apply_filters( 'sportspress_event_statuses', array(
					'ok' => __( 'On time', 'sportspress' ),
					'tbd' => __( 'TBD', 'sportspress' ),
					'postponed' => __( 'Postponed', 'sportspress' ),
					'cancelled' => __( 'Canceled', 'sportspress' ),
				) );
			$summary .= ' (' . $statuses[ $status ] . ')';
		}
		return $summary;
	}
}

endif;

new SportsPress_Ical_Event_Status();
