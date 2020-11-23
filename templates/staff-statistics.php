<?php
/**
 * Staff Statistics
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version   2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//if ( 'no' === get_option( 'sportspress_player_show_statistics', 'yes' ) && 'no' === get_option( 'sportspress_player_show_total', 'no' ) ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$staff = new SP_Staff( $id );

$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
$show_career_totals = 'yes' === get_option( 'sportspress_player_show_career_total', 'no' ) ? true : false;

$show_teams = apply_filters( 'sportspress_player_team_statistics', true );
$leagues = array_filter( ( array ) get_the_terms( $id, 'sp_league' ) );

// Sort Leagues by User Defined Order (PHP5.2 supported)
foreach ( $leagues as $key => $league ) {
	$leagues[ $key ]->sp_order = get_term_meta ( $league->term_id , 'sp_order', true );
}
if ( ! function_exists( 'sortByOrder' ) ) { 
	function sortByOrder($a, $b) {
		return (int) $a->sp_order - (int) $b->sp_order;
	}
}
usort($leagues, 'sortByOrder');


// Loop through statistics for each league
if ( is_array( $leagues ) ):
		
	if ( sizeof( $leagues ) > 1 ) {
		printf( '<h3 class="sp-post-caption sp-staff-statistics-section">%s</h3>', $section_label );
	}
	
	foreach ( $leagues as $league ):
		$caption = $league->name;

		$args = array(
			'data' => $staff->data( $league->term_id, false ),
			'caption' => $caption,
			'scrollable' => $scrollable,
			'league_id' => $league->term_id,
		);
		if ( ! $show_teams ) {
			$args['hide_teams'] = true;
		}
		sp_get_template( 'staff-statistics-league.php', $args );
	endforeach;

	if ( $show_career_totals ) {
		sp_get_template( 'staff-statistics-league.php', array(
			'data' => $staff->data( 0, false, $section_id ),
			'caption' => __( 'Career Total', 'sportspress' ),
			'scrollable' => $scrollable,
			'hide_teams' => true,
		) );
	}
	
endif;
