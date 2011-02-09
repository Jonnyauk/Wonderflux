<?php
/**
 * The core search include
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
?>
<form action="<?php echo home_url(); ?>/" method="get" accept-charset="utf-8">
  <fieldset>
    <label for="search">Search site</label>
    <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
    <input type="submit" id="searchsubmit" value="Search" />
  </fieldset>
</form>