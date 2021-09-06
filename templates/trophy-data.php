<?php
/**
 * Trophy Data
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $id ) )
	$id = get_the_ID();

$title = get_the_title( $id );

$trophy_data = get_post_meta( $id, 'sp_trophies', true );
?>

<h4 class="sp-table-caption"><?php echo __( 'Winners of ', 'sportspress' ) . $title;?></h4>
<div class="sp-template sp-template-trophy-data">
	<div class="sp-table-wrapper">
		<table class="sp-trophy-data sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Season', 'sportspress' );?></th>
					<th><?php _e( 'Winner', 'sportspress' );?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach( $trophy_data as $season_id => $trophy ) {
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
				echo '<tr>';
				echo '<td>' . $season . '</td>';
				echo '<td>' . $team . '</td>';
				echo '</tr>';
			}?>
			</tbody>
		</table>
	</div>
</div>