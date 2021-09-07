<?php
/**
 * Trophy Data
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( $show_title && false === $title && $id )
	$title = __( 'Winners of ', 'sportspress' ) . get_the_title( $id );

//Create a unique identifier based on the current time in microseconds
$identifier = uniqid( 'table_' );

$output = '';

if ( $title )
	$output .= '<h4 class="sp-table-caption">' . $title . '</h4>';

$output .= '<div class="sp-table-wrapper">';

$output .= '<table class="sp-trophy-data sp-data-table' . ( $sortable ? ' sp-sortable-table' : '' ) . ( $responsive ? ' sp-responsive-table '.$identifier : '' ). ( $scrollable ? ' sp-scrollable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';

$output .= '<th>' . __( 'Season', 'sportspress' ) . '</th>';
$output .= '<th>' . __( 'Winner', 'sportspress' ) . '</th>';

$output .= '</tr>' . '</thead>' . '<tbody>';

foreach( $trophy_data as $season_id => $trophy ) {
	$season = $trophy['season'];
	$team = sp_team_short_name( $trophy['team_id'] );
	if ( isset( $trophy['table_id'] ) && $trophy['table_id'] != -1 ) {
		$league_table_permalink = get_permalink( $trophy['table_id'] );
		$season = '<a href="' . $league_table_permalink . '">' . $season . '</a>';
	}elseif ( isset( $trophy['calendar_id'] ) && $trophy['calendar_id'] != -1 ) {
		$calendar_permalink = get_permalink( $trophy['calendar_id'] );
		$season = '<a href="' . $calendar_permalink . '">' . $season . '</a>';
	}
	if ( get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false ) {
		$team_permalink = get_permalink( $trophy['team_id'] );
		$team = '<a href="' . $team_permalink . '">' . $team . '</a>';
	}
	
	$output .= '<tr> <td>' . $season . '</td> <td>' . $team . '</td> </tr>';
}

$output .= '</tbody>' . '</table>';
$output .= '</div>';
?>

<div class="sp-template sp-template-trophies">
	<?php echo $output; ?>
</div>