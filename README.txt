= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

WONDERFLUX WORDPRESS THEME FRAMEWORK PUBLIC BETA
VERSION 0.91

Copyright (C) 2011 Jonny Allbut www.jonnya.net

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

You may also view the license online at http://www.gnu.org/licenses/gpl-2.0.html

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

WELCOME!

Welcome to the forth beta testing phase of Wonderflux, the new theme framework for WordPress.
Thank you for your kind offer of participating - we really appreciate it!

We would love to keep you informed on Wonderflux development and continue to let you know when new betas are released for testing.

Drop by www.wonderflux.com for more information

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

GET INVOLVED!

Wonderflux is distributed under the GPL license - just like WordPress (yes - that means FREE basically!).

As we move towards the next beta, we'd really appreciate ANY feedback, contributions or suggestions on things you would like to see.
We are building this for everyone to use - so tell us what you want!

Drop by www.wonderflux.com for more information

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

!! WARNING !!

THIS IS A PER-RELEASE BETA - we still have a-lot of work to complete and although it functions correctly it currently lacks some elements.
IT IS NOT RECOMMENDED FOR ANY SORT OF MISSION CRITIAL LIVE PRODUCTION WEBSITES and is provided purely for your feedback and heopfully development contributions.

Although we have big plans for Wonderflux options and controls for less experienced theme designers and WordPress users, the current functionality is mostly contained in the child themes functions file and style.css file. This structure will remain in future versions for developers (and to improve efficiency with less database activity), but almost everything will move to simple options panels within WordPress admin area.
Also, in the next version of Wonderflux we know that there is going to be a fairly major change in the PHP class/function structure, along with full support and optimisation for WordPress 3.1 (beta now out by the way!) - so some things are going to change.

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

QUICK START REFERENCE

Wonderflux is a theme framework that works with 'child themes'
IMPORTANT - WFX Girder is a child theme designed for Wonderflux that can be downloaded from http://code.google.com/p/wonderflux-girder-theme/

Although Wonderflux will function if activated directly, the best way to use Wonderflux is by activating a child theme.

Wonderflux is amazingly flexible, with many filters hooks and functions you can over-ride through your child theme functions file.

It also heavily uses the principle of 'template parts' - please see the WFX Girder theme structure for more on this.

> Start of with using the template parts as shown in the WF Girder child theme. Anything that has '-content' in the name is a template part, ie:

footer-content.php
header-content-php
loop-content-page.php (page loop content - only used on pages)
loop-content.php (general loop content - used everywhere else)

You now have a complete location aware get_template_part function, allowing unique content to be easily used when viewing different kinds of content:

home
category
tag
search
date
author
tax (taxonomy)
archive
attachment
single (single post)
page

So you would create the file footer-content-page.php in your child theme to hold unique content for when you are viewing a page, and footer-content.php that is used in all circumstances - cool!

This will work for:
header-content.php
sidebar-content.php
footer-content.php
loop-content.php

> IMPORTANT!! Remember the principle of child themes - if the template file exists in your child theme directory with the same name as Wonderflux - it replaces it!

1) Once you have activated WF Girder theme, you will see a new menu at the bottom of your sidebar in the admin area called 'Wonderflux' with various options

2) In this beta version, the only options active are under 'Style Lab'. This is where you can change the CSS dimensions and grid configuration of your Wonderflux installation.

3) IMPORTANT!! Currently there is no calculation performed on combinations of numbers, so it is possible to get fractional widths which may end-up with very odd display! For testing purposes, try out the following valid combinations - these all add up correctly:

* width=960 x cols=16 x colwidth=45
* width=960 x cols=36 x colwidth=15
* width=950 x cols=20 x colwidth=38
* width=950 x cols=24 x colwidth=30
* width=950 x cols=48 x colwidth=10
* width=760 x cols=24 x colwidth=24
* width=760 x cols=20 x colwidth=19

NOTE: Left and Right container padding are active, but currently don't effect the layout.

OTHER REFERENCE

> Note the use of PHP layout display functions to setup CSS layout divs in WFX Girder - They use a special definitions such as 'half' and 'quarter' to make layout easier.

> You will find reference to all hooks in themes/wonderflux/wf-includes/wf-display-hooks.php

> You will find a number of more interesting display functions for you to use in your themes in themes/wonderflux/wf-includes/wf-display-extras.php

> You will see a reference guide in the head of your document if you view source. It is currently working in a very similar way to the Blueprint CSS framework - the same rules and references apply - http://www.blueprintcss.org/ - NOTE this is likely to change!

> There are a-lot of transparent PNG images in wf-content/backgrounds ready for you to use in your CSS

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

0.91 - PUBLIC BETA RELEASE CHANGE NOTES

!!!! IMPORTANT CHANGES !!!!
Hook name changes (OLD -> NEW)
wfmain_after_home_last_content() -> wfmain_after_home_content()
wfmain_after_all_last_content() -> wfmain_after_all_content()
wfsidebar_before() -> wfsidebar_before_all()
wfsidebar_before_all_content() -> REPLACED WITH LOCATION SPECIFIC HOOKS ie  -> wfsidebar_before_category()
wfsidebar_after_all_content() -> REPLACED WITH LOCATION SPECIFIC HOOKS ie  -> wfsidebar_after_category()
wfsidebar_after() -> wfsidebar_after_all()

- Debugged and completed all core template files completed with associated hooks
- Depreciated calls replaced with home_url()
- wf_head_close amended to include extended body class display
- Implemented some very light CSS formatting in core Wonderflux style.css
- Implemented core CSS styles as used in WordPress into wf-css-core-structure.css
- 2 new core functions that detect when Wonderflux directly activated and adds CSS layout definitions and widget areas
- Caught bugs in home.php - was using index hooks - new home specific hooks created
- Complete overhaul of hook system, including many, many new location/view specific hooks - eg for category or tag views
- New display extras function - wf_loginlogout - a simple (but better) login/logout link generator with redirects
- New display extras function - wf_static_highlight - used to generate CSS class in static coded links when viewing a specific page or category (or child of)
- New display extras function - wf_get_attachments() - soon to be super ninja attachment getter function!
- Expanded wf_edit_meta with a few new parameters, makes it more useful when using in 'single post' view rather than as site-wide admin bar.
- Changed priority on some core head functions to allow insertion of child theme functions right at top of output if required (after doc declaration).
- JQuery 1.4.1 now in Wonderflux includes
- Various new includes in JS folder (for future development)
- Checked WordPress 3.04 compatibility

0.90 - PRIVATE BETA RELEASE CHANGE NOTES
- New file wf-includes/wf-theme-core.php - To include all functions that need to be loaded right at the start of a child theme
- New wf_widgets function to build widgets and insert using Wonderflux template hooks
- New hook wfsidebar_before_all_content = inside sidebar, after layout CSS hook but before actual sidebar content
- New hook wfsidebar_after_all_content = inside sidebar, before closing layout CSS hook but after actual sidebar content
- Improve function commenting and documentation throughout wf-display-extras.php
- Remove old code in Wonderflux core theme layout files
- Rename wf_get_page to wf_get_single_content for clarification. It's not just for getting pages, its for posts too and other stuff!
- Amended wf_excerpt to trim off any unwanted space at the end of the excerpt output
- Rewrite of wf_get_page function, new parameters, more control and bugs caught
- Rewrite of wf_edit_meta function, new parameters, more control and bugs caught
- Rewrite of wf_perma_img function, new parameters, more control and bugs caught
- Rewrite of wf_png_ie6, new parameters, more control and bugs caught
- Rewrite of wf_twitter_feed, new parameters, more control and bugs caught (yes, all of them finally I think!)
- wf_edit_meta function 404 bug catch
- Update cufon-yui.js to v1.09i (IE9 compatibility)

0.89 - PRIVATE BETA RELEASE CHANGE NOTES

- IMPORTANT - New function in wflux_core class 'wf_info_location' - returns type of content viewed. Can be used by developers for easy conditional location tests.
- Optimisation of core loop.php using wf_get_template_part function
- sidebar-content template part low fully location aware for every view type - examples: sidebar-content-page.php and sidebar-content-category.php
- header-content template part low fully location aware for every view type - examples: sidebar-content-page.php and sidebar-content-category.php
- footer-content template part low fully location aware for every view type - examples: sidebar-content-page.php and sidebar-content-category.php
- wf_head re-written to include full flexibility for different doc types etc

- IMPORTANT - Extra parameters in wp_css for full div output when using in functions file, ability to add extra classes and also id's through arguments

- IMPORTANT - Rebuilt and optimised the wf_css function
 - Uses $args array for parameters
  - NOTE new way of using in templates. See Girder child theme for examples - template_parts ie footer-content.php and functions-layout.php
  - More CSS builder definitions like three_quarter etc
  - New 'last' css parameter, for adding 'last' as opposed to using definitions like 'half_last'.

- IMPORTANT- LOADS of transparent PNG background images and gradients in wp-content/themes/wonderflux/wf-content

- Fixed bugs in wf_head character encoding
- Fixed bug in wf_twitter when no date shown
- Fixed bug in wf_credit_footer_code filter
- wf_this_page_depth in helper functions
- wf_in_cat_parent in helper functions
- Admin panels now use core WordPRess theme tab styling
- wf_css_info revised, now actually useful! Shows a-lot of design information and warns if cant use certain definitions.
- Caught bugs in Wonderflux functions file - using old hooks for a couple of things
- Revised wf_display_credit_footer output - put in filtering
- Caught bug in wf_head_title function - Standard WP description on install is now 'Just another WordPress site' not 'Just another WordPress blog', which we dont want to displat if the user has not set a proper description
- wf_credit_footer_code updated with filter
- wf_debug_footer code updated
- Fixed bug in wf_twitter_feed when no tweets available - now falls back elegantly with nice message rather than producing error (now uses 'is_wp_error' as our function uses WP functionality)
- 2 new core WF template hooks in each template file (1=specific to template, 2=common to 'all') - inside 'main-content' div but after all sidebars and content.
  - wfmain_after_all_last_content (general)
  - wfmain_after_PAGETEMPLATENAMEHERE_last_content (specific)