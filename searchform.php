<?php
/**
 * Wonderflux searchform content template
 *
 * Customise this in your child theme by:
 * - Creating a file with the same name in your child theme - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<form id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
  <fieldset>
    <label for="search"><?php esc_attr_e( 'Search', 'wonderflux' ); ?></label>
    <input type="text" name="s" class="field" id="search" value="<?php the_search_query(); ?>" />
    <input type="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'wonderflux' ); ?>" />
  </fieldset>
</form>