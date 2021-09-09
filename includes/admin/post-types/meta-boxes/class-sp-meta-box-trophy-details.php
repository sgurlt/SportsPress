<?php
/**
 * Trophy Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version   2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Trophy_Details
 */
class SP_Meta_Box_Trophy_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
	}
}