<?php
/**
 * Trophy Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Trophy
 * @version     2.8
 */
class SP_Shortcode_Trophy {

	/**
	 * Output the trophy shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'trophy-data.php', $atts );
	}
}
