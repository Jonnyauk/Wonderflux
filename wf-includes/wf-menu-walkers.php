<?php
/**
 * Custom menu walker
 * 1) Removes link around parent list item if it has a nested (sub-menu) list
 * 2) Duplicates parent list item as first item in nested with common (or filterable) title.
 *
 * Designed to be used as a 'click expand' menu where parent item is not a direct link to page.
 * Just create normal vanilla nested list items, no complex menus, duplicated links or special classes etcetc in menu editor ;)
 * Works with sub-sub-sub-sub list items (and likely more... but that's crazy right?)
 *
 * NOTE: Requires a-little JS to do anything 'pretty'!
 *
 * Filters available (in addition to all the normal WP ones):
 * wflux_menu_parent_d_text - array, has 3 keys: 'prepend' (default: ''), 'text' (default: link text set in WP menu), 'append' (default: ' Overview')
 *
 * @since 2.6
 * @version 2.6
 *
 * @param none
 * @return  [string]        Output of wp_nav_menu() param: 'walker' => new mywfx_walker_parent_duplicate
 *
 */
class wfx_walker_parent_duplicate extends Walker_Nav_Menu {

	private $this_node; /* Used to store post link setup */

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		///////// WFX CUSTOM WALKER START - Store post data for sub-menus

		$this->this_node = $item;

		///////// WFX CUSTOM WALKER END - Store post data for sub-menus

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		///////// WFX CUSTOM WALKER START - Remove top level parent link
		// NOTE: Could filter this, with nav_menu_item_title,
		// but lets keep everything together and do it here in one file

		// Check if menu item has children, easiest from WP added CSS class to save query
		//if ( property_exists( $item, 'classes' ) && is_array( $item[ 'classes' ] ) && in_array( 'menu-item-has-children', $item[ 'classes' ] ) ) {
		if ( property_exists( $item, 'classes' ) && is_array( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) ) {

			$item_output  = $args->before;
			$item_output .= $args->link_before . '<span class="menu-item-parent-title menu-item-parent-toggle">' . $title . '</span>' . $args->link_after;
			$item_output .= $args->after;

		} else {

			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

		}

		///////// WFX CUSTOM WALKER END - Remove top level parent link

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}


	function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		///////// WFX CUSTOM WALKER START - Add sub menu depth class
		// NOTE: Could filter this, with nav_menu_submenu_css_class,
		// but lets keep everything together and do it here in one file
		$classes[] = 'sub-menu-depth-' . ( $depth + 1 );
		///////// WFX CUSTOM WALKER END - Add sub menu depth class

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";

		///////// WFX CUSTOM WALKER START - Build out duplicate custom link

		$n_data = array();
		$n_data_clean = array();
		$n_data_out = '';

		// Data whitelist
		// NOTE: ALL REQUIRED TO BE SET and match keys from $this_node
		$d_list = array(
			'url',
			'title',
			'attr_title',
			'classes'
		);

		if ( is_object( $this->this_node ) ) {

			// Setup data/checking variable vars
			$n_data[] = get_object_vars( $this->this_node );

			foreach ( $d_list as $k => $v ) {
				$n_data_clean[ $v ] = '';
			}

			// Populate variable vars
			foreach( $n_data as $node ) {

			    foreach ( $node as $k => $v ) {

			    	if ( in_array( $k, $d_list ) && !empty( $v ) ) {

			    		$n_data_clean[ $k ] = ( is_array( $v ) ) ? $v : trim( $v );

			    	}

			    }

			}

			//// Build output
			if ( wfx_valid_url( $n_data_clean['url'] ) && !empty( $n_data_clean['title'] ) ) {

				// CSS Classes
				$n_data_clean['classes'] = array_diff( $n_data_clean[ 'classes' ], array( 'menu-item-has-children' ) );
				array_unshift( $n_data_clean['classes'], 'menu-item-duplicated-parent' );

				$classes_o = wfx_array_to_delimited_string( array(
					'values'	=> $n_data_clean['classes'],
					'seperator'	=> ' ',
					'echo'		=> 'N'
				));

				$n_data_list = array(
					'prepend',
					'text',
					'append'
				);

				//// Text
				$n_args = array(

					'prepend'	=>	'',
					'text'		=>	$n_data_clean[ 'title' ],
					'append'	=>	' Overview'

				);

				// Allow complete manipulation of text shown - note that 'text' contains title
				$n_args = apply_filters( 'wflux_menu_parent_d_text', $n_args );

				$title_o = '';
				$title_o .= ( array_key_exists( 'prepend', $n_args ) ) ? $n_args['prepend'] : '';
				$title_o .= ( array_key_exists( 'text', $n_args ) ) ? $n_args['text'] : '';
				$title_o .= ( array_key_exists( 'append', $n_args ) ) ? $n_args['append'] : '';

				//// Link title
				$title_link_o = '';
				$title_link_o = ( array_key_exists( 'attr_title', $n_data_clean ) && !empty( $n_data_clean['attr_title'] ) ) ? $n_data_clean['attr_title'] : $title_o;
				$title_link_o = ( !empty( $title_link_o ) ) ? ' title="' . esc_html( trim( $title_link_o ) ) . '"' : '';

				// Link output
				$n_data_out = '<li'
				. ' class="'
				. esc_attr( trim( $classes_o ) )
				. '"'
				. '>'
				. '<a'
				. ' href="'
				 . esc_url( $n_data_clean['url'] )
				. '"'
				. $title_link_o
				. '>'
				. esc_html( $title_o )
				. '</a>'
				. '</li>';

			}

		}

		$output .= $n_data_out;

		///////// WFX CUSTOM WALKER END - Build out duplicate custom link

	}

}