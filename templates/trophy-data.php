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
var_dump($trophy_data);?>

<h4 class="sp-table-caption"><?php echo __( 'Winners of ', 'sportspress' ) . $title;?></h4>
<div class="sp-template sp-template-trophy-data">
	<div class="sp-table-wrapper">
		<table class="sp-trophy-data sp-data-table">
			<thead>
				<tr>
					<th>Season</th>
					<th>Winner</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach( $trophy_data as $season_id => $season ) {
				echo '<tr>';
				echo '<td>' . $season['season'] . '</td>';
				echo '<td>' . $season['team'] . '</td>';
				echo '</tr>';
			}?>
			</tbody>
		</table>
	</div>
</div>