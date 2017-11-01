<?php
/**
 * WP REST API Functionality
 *
 * @since	2.6
 * @version	2.6
 */
class wflux_display_restapi {


	/**
	 * Adds new 'wfx_post_class' data to WP REST API JSON output containing array of post classes.
	 *
	 * @filter wflux_restapi_post_types - array of post types to add post classes to.
	 *
	 * @since	2.6
	 * @version	2.6
	 *
	 * @param	none
	 */
	function wf_rest_add_post_classes() {

		// Get relevant post types
		$post_types = get_post_types( array( 'public' => true, '_builtin' => false, 'show_in_rest' => true), 'names' );
		$post_types_core = get_post_types( array( 'public' => true, '_builtin' => true, 'show_in_rest' => true), 'names' );
		$all_rest_post_types = apply_filters( 'wflux_restapi_post_types',  array_merge( $post_types, $post_types_core ) );

		foreach ( $all_rest_post_types as $pt ) {

			// register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
			register_rest_field( esc_attr( $pt ), 'wfx_post_class', array(
					'get_callback'	=> array( $this, 'wf_rest_get_post_classes' ),
					'schema'		=> null,
				)
			);

		}

	}


	/**
	 * Gets the standard WP post classes for post
	 *
	 * @since	2.6
	 * @version	2.6
	 *
	 * @param	[string] $object		Post object
	 */
	function wf_rest_get_post_classes( $object ) {

		$post_id = $object['id'];
		return get_post_class( $post_id );

	}


}