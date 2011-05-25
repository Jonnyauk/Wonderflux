<div class="wrap">

<?php
if ( is_multisite() && current_user_can('edit_themes') ) {
	?><div id="message0" class="updated"><p><?php printf( __('Administrator: new themes must be activated in the <a href="%s">Network Themes</a> screen before they appear here.'), admin_url( 'ms-themes.php') ); ?></p></div><?php
}
?>

<?php if ( ! validate_current_theme() ) : ?>
<div id="message1" class="updated"><p><?php _e('The active theme is broken.  Reverting to the default theme.'); ?></p></div>
<?php elseif ( isset($_GET['activated']) ) :
		if ( isset($wp_registered_sidebars) && count( (array) $wp_registered_sidebars ) && current_user_can('edit_theme_options') ) { ?>
<div id="message2" class="updated"><p><?php printf( __('New theme activated. This theme supports widgets, please visit the <a href="%s">widgets settings</a> screen to configure them.'), admin_url( 'widgets.php' ) ); ?></p></div><?php
		} else { ?>
<div id="message2" class="updated"><p><?php printf( __( 'New theme activated. <a href="%s">Visit site</a>' ), home_url( '/' ) ); ?></p></div><?php
		}
	elseif ( isset($_GET['deleted']) ) : ?>
<div id="message3" class="updated"><p><?php _e('Theme deleted.') ?></p></div>
<?php endif; ?>
<?php
$themes = get_allowed_themes();
$ct = current_theme_info();
unset($themes[$ct->name]);

//$themes = array_slice( $themes, $start, $per_page );
?>

<div class="wrap">
<h3><?php _e('Current Wonderflux Child Theme'); ?></h3>
<div id="current-theme">
<?php if ( $ct->screenshot ) : ?>
<img src="<?php echo $ct->theme_root_uri . '/' . $ct->stylesheet . '/' . $ct->screenshot; ?>" alt="<?php _e('Current theme preview'); ?>" />
<?php endif; ?>
<h4><?php
	/* translators: 1: theme title, 2: theme version, 3: theme author */
	printf(__('%1$s %2$s by %3$s'), $ct->title, $ct->version, $ct->author) ; ?></h4>
<p class="theme-description"><?php echo $ct->description; ?></p>
<?php if ( current_user_can('edit_themes') && $ct->parent_theme ) { ?>
	<p><?php printf(__('The template files are located in <code>%2$s</code>. The stylesheet files are located in <code>%3$s</code>. <strong>%4$s</strong> uses templates from <strong>%5$s</strong>. Changes made to the templates will affect both themes.'), $ct->title, str_replace( WP_CONTENT_DIR, '', $ct->template_dir ), str_replace( WP_CONTENT_DIR, '', $ct->stylesheet_dir ), $ct->title, $ct->parent_theme); ?></p>
<?php } else { ?>
	<p><?php printf(__('All of this theme&#8217;s files are located in <code>%2$s</code>.'), $ct->title, str_replace( WP_CONTENT_DIR, '', $ct->template_dir ), str_replace( WP_CONTENT_DIR, '', $ct->stylesheet_dir ) ); ?></p>
<?php } ?>

</div>

<div class="clear"></div>


<div class="icon32" id="icon-options-general"><br></div>
<h2><?php echo get_current_theme();?> display functions</h2>
<p>Theme designers can use these functions to configure Wonderflux child theme functions and effects. Once enabled, the function/script is setup automatically in every page of your site and ready to use.</p>
<p>NOTE: Some Wonderflux child themes use advanced display functions to achieve certain effects. If the option is greyed out and not changeable, it means that <strong>this function is REQUIRED</strong> for the <?php echo get_current_theme();?> Wonderflux child theme, which you currently using on for this site.</p>
<h3>Need more control?</h3>
<h2>Options to follow - this is why we are in beta, sorry!</h2>


</div>