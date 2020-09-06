<?php
/**
 * Fixture exporter - export fixtures from SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Exporters
 * @version		2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 ?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form method="post" action="<?php //echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
		<ul id="post-filters" class="export-filters" style="display: block;">
			<li>
				<label><span class="label-responsive"><?php _e( 'League', 'sportspress' ); ?></span>
					<?php
					$args = array(
						'taxonomy' => 'sp_league',
						'name' => 'sp_league',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					?>
				</label>
			</li>
			<li>
				<label><span class="label-responsive"><?php _e( 'Season', 'sportspress' ); ?></span>
				<?php
					$args = array(
						'taxonomy' => 'sp_season',
						'name' => 'sp_season',
						'values' => 'slug',
						'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
					);
					?>
				</label>
			</li>
		</ul>
		<?php submit_button( __( 'Export', 'sportspress' ) );?>
	</form>
</div>
