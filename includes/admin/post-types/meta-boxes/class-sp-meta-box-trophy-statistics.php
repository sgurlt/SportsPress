<?php
/**
 * Trophy Statistics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Trophy_Statistics
 */
class SP_Meta_Box_Trophy_Statistics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$seasons = get_terms( array(
			'taxonomy' => 'sp_season',
			'hide_empty' => false,
			'orderby' => 'meta_value_num',
			'meta_query' => array(
								'relation' => 'OR',
								array(
									'key' => 'sp_order',
									'compare' => 'NOT EXISTS'
								),
								array(
									'key' => 'sp_order',
									'compare' => 'EXISTS'
								),
							),
			'order' => 'DESC',
		) );
		
		$winners_perseason = array_filter( (array)get_post_meta( $post->ID, 'sp_trophies', true ) );
		
		self::table( $post->ID, $seasons, $winners_perseason );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$winners_perseason = sp_array_value( $_POST, 'sp_trophies', array() );
		$teams = array();
		$winners = array();
		foreach ( $winners_perseason as $season ) {
			$teams[] = $season['team'];
			$winners[ $season['team'] ][] = $season['season'];
		}
		$teams = array_filter( $teams );
		$teams = array_unique( $teams );
		update_post_meta( $post_id, 'sp_trophies', $winners_perseason );
		update_post_meta( $post_id, 'sp_teams', $teams );
		update_post_meta( $post_id, 'sp_winners', $winners );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $id = null, $seasons = array(), $winners_perseason = array() ) {
		$selected_team = null;
		$selected_table = null;
		$selected_calendar = null;
		?>
		<div class="sp-data-table-container sp-table-values" id="sp-table-values">
			<table class="widefat sp-data-table sp-trophies-statistics-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<th><?php _e( 'Winner', 'sportspress' ); ?></th>
						<th><?php _e( 'Table', 'sportspress' ); ?></th>
						<th><?php _e( 'Calendar', 'sportspress' ); ?></th>
				</thead>
				<tbody>
				<?php 
				$i=0;
				foreach ( $seasons as $season ) {
				?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?> ">
					<td>
						<label>
							<input type="hidden" name="sp_trophies[<?php echo $season->term_id; ?>][season]" value="<?php echo $season->term_id; ?>">
							<?php echo $season->name; ?>
						</label>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[$season->term_id] ) )
						$selected_team = sp_array_value( $winners_perseason[$season->term_id], 'team', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_team',
							'name' => 'sp_trophies[' . $season->term_id . '][team]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'option_none_value' => false,
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_team,
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[$season->term_id] ) )
						$selected_table = sp_array_value( $winners_perseason[$season->term_id], 'table', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_table',
							'name' => 'sp_trophies[' . $season->term_id . '][table]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_table,
							'tax_query' => array(
												array(
													'taxonomy' => 'sp_season',
													'terms'    => $season->term_id,
												),
											),
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					<td>
					<?php 
					if ( isset( $winners_perseason[$season->term_id] ) )
						$selected_calendar = sp_array_value( $winners_perseason[$season->term_id], 'calendar', '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_calendar',
							'name' => 'sp_trophies[' . $season->term_id . '][calendar]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							'selected' => $selected_calendar,
							'tax_query' => array(
												array(
													'taxonomy' => 'sp_season',
													'terms'    => $season->term_id,
												),
											),
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					</tr>
				<?php
				$i++;
				} 
				?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
