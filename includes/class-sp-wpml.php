<?php
/**
 * WPML Class
 *
 * The SportsPress WPML class handles all WPML-related localization hooks.
 *
 * @class 		SP_WPML
 * @version		1.8.2
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_WPML' ) ) :

/**
 * SP_WPML Class
 */
class SP_WPML {

	var $languages = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
		add_filter( 'the_title', array( $this, 'the_title' ), 5, 2 );
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 5, 3 );
		add_filter( 'icl_ls_languages', array( $this, 'ls' ) );
		add_filter( 'translatable_object_id', array( $this, 'translatable_object_id' ), 5, 2 );
	}

	public function init() {
		if ( function_exists( 'icl_get_languages' ) )
			$this->languages = icl_get_languages();
	}

	public static function the_title( $title, $id = null ) {
		if ( self::can_localize( $id, $id ) ):
			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );
			
			if ( $translated_id ):
				$post = get_post( $translated_id );
				if ( $post ) $title = $post->post_title;
			endif;
		endif;

		return $title;
	}

	public static function post_type_link( $url, $post = null, $leavename = false, $sample = false ) {
		if ( self::can_localize( $post ) ):
			if ( ! $post ) global $post;

			// Get post ID
			$id = $post->ID;

			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );

			if ( $translated_id && $translated_id != $id && get_the_ID() != $translated_id ):
				return get_post_permalink( $translated_id, $leavename, $sample );
			endif;
		endif;

		return $url;
	}

	public function ls( $languages ) {
		if ( ! function_exists( 'icl_object_id' ) || ! is_singular( 'sp_event' ) ) return $languages;

		// Get post ID
		$id = get_the_ID();

		if ( get_post_status( $id ) != 'future' ) return $languages;

		$active_languages = array();

        foreach ( $this->languages as $k => $v ):
			global $wpdb;

			// Get language code
        	$code = sp_array_value( $v, 'code' );

        	// Get URL
			$translated_id = icl_object_id( $id, 'any', false, $code );
			if ( ! $translated_id ) continue;
			$url = get_post_permalink( $translated_id, false, true );

			// Get native name;
        	$native_name = sp_array_value( $v, 'native_name' );

        	// Get encode URL
			$encode_url = $wpdb->get_var($wpdb->prepare("SELECT encode_url FROM {$wpdb->prefix}icl_languages WHERE code=%s", $code));
			
			// Get flag
			$flag = $wpdb->get_row( "SELECT flag, from_template FROM {$wpdb->prefix}icl_flags WHERE lang_code='{$code}'" );
			if ( $flag->from_template ) {
				$wp_upload_dir = wp_upload_dir();
				$flag_url      = $wp_upload_dir[ 'baseurl' ] . '/flags/' . $flag->flag;
			} else {
				$flag_url = ICL_PLUGIN_URL . '/res/flags/' . $flag->flag;
			}

			// Add language
        	$active_languages[ $k ] = array_merge( $v, array(
				'language_code' => $code,
				'active' => ICL_LANGUAGE_CODE == $code ? '1' : 0,
				'translated_name' => $native_name,
				'encode_url' => $encode_url,
				'country_flag_url' => $flag_url,
				'url' => $url,
			) );
        endforeach;

        // Add if translations exist
        if ( sizeof( $active_languages ) > 1 )
        	$languages = array_merge( $languages, $active_languages );

		return $languages;
	}

	public static function can_localize( $post, $id = null ) {
		return function_exists( 'icl_object_id' ) && is_sp_post_type( get_post_type( $post ) );
	}
	
	/**
	 * Returns the translated object ID(post_type or term) or original if missing
	 * Credits to https://wpml.org/wpml-hook/wpml_object_id/
	 * @param $object_id integer|string|array The ID/s of the objects to check and return
	 * @param $type the object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
	 * @return string or array of object ids
	 */
	public static function translatable_object_id( $object_id, $type ) {
		$current_language= apply_filters( 'wpml_current_language', NULL );
		// if array
		if( is_array( $object_id ) ){
			$translated_object_ids = array();
			foreach ( $object_id as $id ) {
				$translated_object_ids[] = apply_filters( 'wpml_object_id', $id, $type, true, $current_language );
			}
			return $translated_object_ids;
		}
		// if string
		elseif( is_string( $object_id ) ) {
			// check if we have a comma separated ID string
			$is_comma_separated = strpos( $object_id,"," );
	 
			if( $is_comma_separated !== FALSE ) {
				// explode the comma to create an array of IDs
				$object_id = explode( ',', $object_id );
	 
				$translated_object_ids = array();
				foreach ( $object_id as $id ) {
					$translated_object_ids[] = apply_filters ( 'wpml_object_id', $id, $type, true, $current_language );
				}
	 
				// make sure the output is a comma separated string (the same way it came in!)
				return implode ( ',', $translated_object_ids );
			}
			// if we don't find a comma in the string then this is a single ID
			else {
				return apply_filters( 'wpml_object_id', intval( $object_id ), $type, true, $current_language );
			}
		}
		// if int
		else {
			return apply_filters( 'wpml_object_id', $object_id, $type, true, $current_language );
		}
	}
}

endif;

return new SP_WPML();
