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

//Get all the winners of the specific trophy
//$trophy_data = get_post_meta( $id, 'sp_trophies', true );

//Create a unique identifier based on the current time in microseconds
$identifier = uniqid( 'table_' );

$output = '';

if ( $title )
	$output .= '<h4 class="sp-table-caption">' . $title . '</h4>';

$output .= '<div class="sp-table-wrapper">';

$output .= '<table class="sp-trophy-data sp-data-table' . ( $sortable ? ' sp-sortable-table' : '' ) . ( $responsive ? ' sp-responsive-table '.$identifier : '' ). ( $scrollable ? ' sp-scrollable-table' : '' ) . ( $paginated ? ' sp-paginated-table' : '' ) . '" data-sp-rows="' . $rows . '">' . '<thead>' . '<tr>';

$output .= '<th>' . __( 'Team', 'sportspress' ) . '</th>';
$output .= '<th>' . __( 'Seasons', 'sportspress' ) . '</th>';

$output .= '</tr>' . '</thead>' . '<tbody>';

foreach( $trophy_data as $team_id => $seasons ) {
	$logo = null;
	$team = sp_team_short_name( $team_id );
	$winnings = array();
	if ( $order === 'asc' )
		$seasons = array_reverse( $seasons );
	foreach ( $seasons as $season ) {
		$winning = $season['season_name'];
		if ( isset( $season['table_id'] ) && $season['table_id'] != -1 ) {
			$league_table_permalink = get_permalink( $season['table_id'] );
			$winning = '<a href="' . $league_table_permalink . '">' . $winning . '</a>';
		}elseif ( isset( $season['calendar_id'] ) && $season['calendar_id'] != -1 ) {
			$calendar_permalink = get_permalink( $season['calendar_id'] );
			$winning = '<a href="' . $calendar_permalink . '">' . $winning . '</a>';
		}
		$winnings[] = $winning;
	}
	if ( $show_team_logo ){
		if ( has_post_thumbnail( $team_id ) ) {
			$logo = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon' );
			$team = '<span class="team-logo">' . $logo . '</span>' . $team;
		}
	}
	if ( $link_teams ) {
		$team_permalink = get_permalink( $team_id );
		$team = '<a href="' . $team_permalink . '">' . $team . '</a>';
	}
	$winnings = implode( ', ', $winnings );

	$output .= '<tr> <td>' . $team . '</td> <td>' . $winnings . '</td> </tr>';
}

$output .= '</tbody>' . '</table>';
$output .= '</div>';
?>

<div class="sp-template sp-template-trophies">
	<?php echo $output; ?>
</div>