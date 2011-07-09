<?php
/*
 * Core Wonderflux searchform content template
 *
 * Customise this in your child theme by:
 * - Creating a file with the same name in your child theme - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<form action="<?php echo home_url(); ?>/" method="get" accept-charset="utf-8">
  <fieldset>
    <label for="search">Search site</label>
    <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
    <input type="submit" id="searchsubmit" value="Search" />
  </fieldset>
</form>