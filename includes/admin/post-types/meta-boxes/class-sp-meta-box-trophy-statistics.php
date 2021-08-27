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
		$trophies = array_filter( get_post_meta( $post->ID, 'sp_trophies', false ) );
		
		self::table( $post->ID, $seasons, $trophies );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		var_dump($_POST);
		update_post_meta( $post_id, 'sp_trophies', sp_array_value( $_POST, 'sp_trophies', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $id = null, $seasons = array(), $trophies = array() ) {
		//var_dump($seasons);
		//var_dump($trophies);
		?>
		<div class="sp-data-table-container sp-table-values" id="sp-table-values">
			<table class="widefat sp-data-table sp-trophies-statistics-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<th><?php _e( 'Team', 'sportspress' ); ?></th>
						<th><?php _e( '*Table', 'sportspress' ); ?></th>
						<th><?php _e( '*Calendar', 'sportspress' ); ?></th>
				</thead>
				<tbody>
				<?php 
				$i=0;
				foreach ( $seasons as $season ) {
				?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?> ">
					<td>
						<label>
							<input type="hidden" name="sp_trophies[season]" value="<?php echo $season->term_id; ?>">
							<?php echo $season->name; ?>
						</label>
					</td>
					<td>
					<?php //$value = sp_array_value( $trophies, $season->term_id, '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_team',
							'name' => 'sp_trophies[team]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							//'selected' => $value,
						);
						if ( ! sp_dropdown_pages( $args ) ):
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
					?>
					</td>
					<td>
					<?php //$value = sp_array_value( $trophies, $season->term_id, '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_table',
							'name' => 'sp_trophies[table]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							//'selected' => $value,
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
					<?php //$value = sp_array_value( $trophies, $season->term_id, '-1' ); ?>
					<?php
						$args = array(
							'post_type' => 'sp_calendar',
							'name' => 'sp_trophies[calendar]',
							'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
							'sort_order'   => 'ASC',
							'sort_column'  => 'menu_order',
							'values' => 'ID',
							//'selected' => $value,
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
