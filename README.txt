WONDERFLUX WORDPRESS THEME FRAMEWORK
VERSION v0.931

Copyright (C) 2011 Jonny Allbut / Team Wonderflux - http://wonderflux.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You may have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

You may also view the license online at http://www.gnu.org/licenses/gpl-2.0.html

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

Welcome to the Wonderflux - a free, Open Source theme framework for WordPress.

Wonderflux is distributed under the GPL v2 license just like WordPress
(yes - that means FREE basically!). There are no plans to ever charge
for Wonderflux, it will always remain GPL v2 license.

Just to clarify then - YES, you can use Wonderflux on as many commercial,
non-commercial, personal WordPress sites as you wish, without any fee
or subscription required.

Any developers are welcome to offer code contributions - the project is 
open to any ideas and improvements;) The goal is to develop a framework 
for everyone to use, for free, to make amazing WordPress sites!

Drop by:
http://wonderflux-framework.googlecode.com/

Keep informed on Wonderflux development:
- Following @Wonderflux on Twitter
- Subscribing to beta mailing list at: http://feedburner.google.com/fb/a/mailverify?uri=wonderflux-beta-testing
Drop by http://www.wonderflux.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

HELP AND DOCUMENTATION

Wonderflux Quick start guides - http://wonderflux.com/guide/doc/
Hooks - http://wonderflux.com/guide/hook/
Functions - http://wonderflux.com/guide/function/
Filters - http://wonderflux.com/guide/filter/
Constants - http://wonderflux.com/guide/constant/
Files - http://wonderflux.com/guide/file/

DEMO CHILD THEME DOWNLOAD
WFX Girder is a child theme designed for Wonderflux that can be downloaded from:
http://wonderflux-girder-theme.googlecode.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

UPGRADE NOTES

38 patches have been applied to the previous release upto revision 157!

IMPORTANT - SOME THINGS HAVE CHANGED! IF YOU HAVE CREATED YOUR OWN WONDERFLUX CHILD THEME - PLEASE CHECK THE CHANGE NOTES AND REVISION REFERENCES BELOW BEFORE DEPLOYING THE NEW VERSION OF WONDERFLUX TO ANY LIVE SERVER!!

The best thing to do is download the <a href="http://code.google.com/p/wonderflux-girder-theme/">Girder v0.7 example child theme</a> and take a look at the functions.php file.

CHANGE NOTES

Full development track can be found at http://code.google.com/p/wonderflux-framework/updates/list

r157
- Update core Wonderflux admin page text string with space.

r156
- Close link in wfx_display_credit() function

r155
- Correct translation .pot contact details

r154
- First .pot file prepared ready for translators. 
- More work required to complete translation functionality (scheduled for next release of Wonderflux).

r153
- Correct typo in wfx_debug_performance() function

r152
- Cleanup theme display functions for translation.

r151
- Cleanup theme display functions for translation.

r150
- Cleanup theme core functions for translation.

r149
- Cleanup admin functions for translation.

r148
- Tidy 'Main' admin page and setup for translation.

r147
- Setup the 'Style Lab' admin page for translation.

r146
- Setup the 'system' admin page for translation.

r145
- Amend wf_edit_meta() to function better inside and outside the loop.

r144
- Update wf_edit_meta function to include 'edit this content' link if static page shown on front page.

r143
- Minor bug fix for when no options saved.

r142
- Amend theme building options to allow for future extendability and better efficiency (less repeated code). 
- New notice when options updated in Wonderflux theme options pages. 
- Corrects potential bug when options either not saved or on split-up across pages.

r141
- Reconfigure JQuery function for more flexibility. 
- New 'host' option for Microsoft CDN. - New 'host' option for JQuery CDN hosting. 
- New parameter 'https' support for https CDN version (fixes security warning on https site access). 
- Cycle - Improve compatibility. - New 'host' parameter for Microsoft CDN hosting.

r140
- Improve Open Graph meta data to detect and correctly tag homepage as 'website' for 'og:type' meta as per Open Graph recommendations. 
- Populate Open Graph meta data with fallback content if no post data available.

r139
- Simplify Core CSS ID names for footer and header. 

r138
- New display functions for social sharing. Each supports asynchronous loading for fast page rendering and multiple individually controllable buttons on one page. 
- wfx_social_google_plus_1() 
- Configurable Google+1 button and associated Javascript. 
- wfx_social_facebook_like() 
- Configurable Facebook like and share buttons with associated XML namespace definitions and Open Graph meta tags. 
- wfx_social_twitter_share() 
- Configurable Twitter share button and associated Javascript. 
- New function wfx_social_meta() 
- Builds and inserts social media related meta tags (can be over- ride in child theme). 
- New admin controls for Facebook account and application ID under advanced tab. 
- New document definition available 
- XHTML+RDFa 1.0 
- New filter wf_head_meta_xml_namespace_main 
- Control output of default XML namespace definition. 
- Tidying of internal head building functions. 
- Fixed bug in wf_head_open internal function - rogue character!

r137
- New parameter for wfx_excerpt() function 
- 'trim' (values 'Y'/'N' - default 'Y'). Allows trimming off of punctuation. 
- Visual enhancement so if you set excerpt_end to '...' you don't ever get ',...' or '....'.

r136
- Minor typo in update fail notice check notice, end sentence with full stop!

r135
- Correct bug in layout position for static CSS file style-framework.css output (advanced tab) when using WF_THEME_FRAMEWORK_REPLACE constant.

r134
- Amends to help information for when Wonderflux is activated directly.

r133
- Deeper incorporation of Wonderflux guide links into admin pages. 
- More admin text strings setup ready for translation.

r132
- Simplify CSS ID names in core theme files. 
- 'footer-bg-content' changed to 'footer-content'. 
- 'header-bg-content' changed to 'header-content'.

r131
- Update contextual help dropdown with new Wonderflux guide link. 
- Revise general help text and links. 
- Incorporate relevant documentation into 'Advanced' tab.

r130
- Allow all core constants to be controlled from a Wonderflux child theme.

r129
- Update legacy code and improve readme.

r128
- Changed core header.php theme file to be more in-line with what WordPress theme designers expect and allow easier manipulation. 
- Bring wp_head() back to header.php template level to ensure hooked functions trigger correctly in 3rd party plugins (and pass silly theme validation!). 
- Split-up and amend core <head> meta functions, including wfx_display_head_close (depreciated) to allow for easier manipulation. 
- New wfx_display_body_tag() function in header.php - inserts dynamic opening <body> tag in ouput. Includes core WordPress CSS classes and additional Wonderflux layout classes (was previously bundled together in wfx_display_head_close() core function). 
- New wfx_display_head_char_set() function - inserts correctly formatted character set data - accomodates HTML5 (was previously bundled together in wfx_display_head_close() core function). 
- New hook 'wf_output_start' (at the very start of the page output). 
- New hook 'wf_after_head' (directly after the closing </head> tag).

r127
- Fixed wfmain_after_home_content and wfmain_after_home_container hooks not triggering when using a page for the 'front page' view.

r126
- Fixed wfmain_after_home_content and wfmain_after_home_container hooks not triggering when using a page for the 'front page' view.

r125
- Use WordPress valid tags to describe theme.

r124
- Remove redundant hook.

r123
- Update screenshot.

r122
- Readme update - v0.931alpha detail update

r121
Wonderflux v0.93 tag archive

r120
Wonderflux v0.92 tag archive

r119
Fix new wfx_get_dimensions() so it returns proper values.

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

QUICK START REFERENCE

Wonderflux is a theme framework that works with 'child themes'.

Although Wonderflux will function if activated directly,
the best way to use Wonderflux is by activating a child theme.

You should have BOTH the Wonderflux child theme (like WFX Girder) and Wonderflux
installed in your theme directory, then simply activate the Wonderflux child theme.

DEMO CHILD THEME DOWNLOAD
WFX Girder is a child theme designed for Wonderflux that can be downloaded from:
http://wonderflux-girder-theme.googlecode.com

This simply means that you can create a theme with just a small
handful of files and everything else is provided by Wonderflux!

Wonderflux is amazingly flexible, with many filters hooks and
functions you can over-ride through your child theme functions file.

It also heavily uses the principle of 'template parts' - please see
the WFX Girder theme structure for more on this.

Only advanced developers will need to actually override the standard WordPress
template file structure in their Wonderflux child theme (ie footer.php or header.php)
IF YOU OVERRIDE THESE YOU WOULD BE STRONGLY ADVISED TO DUPLICATE THE CORE FILES
FROM WONDERFLUX INTO YOUR CHILD THEME DIRECTORY to retain the Wonderflux
structure and hook system.

> Start of with using the template parts as shown in the WF Girder
child theme. Anything that has '-content' in the name is a template part, ie:

footer-content.php
header-content-php
loop-content-page.php (page loop content - only used on pages)
loop-content.php (general loop content - used everywhere else)

These files just contain very simple content - just what you want displayed within
the given area of your site. All of the rest of the site code, structure and CSS is dealt
with for you by Wonderflux for you. If you have not worked with theme frameworks,
child themes and get_template_part() before - welcome to the future of
WordPress theme development!

AN IMPORTANT FEATURE of Wonderflux is the location aware get_template_part() function,
allowing unique content to be easily used when using different views in your theme
for the following areas in your site:

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

So to show unique content in the footer when viewing a 'page' in WordPress,
simply create the file:

footer-content-page.php

in your child theme folder and this content will display only on this condition.
IF THE FILE DOESN'T EXIST FOR THE LOCATION, it rolls back to footer-content.php

This location aware functionality will also work for the following areas in your theme:
header-content.php
sidebar-content.php
footer-content.php
loop-content.php

!! DON'T HACK WONDERFLUX !!

You should not modify the Wonderflux theme framework to avoid issues with updates in the future.
You have lots of ways to manipulate this from your child theme! http://codex.wordpress.org/Child_Themes

1) Create a function with the same name as a core Wonderflux display function in your child theme.

2) Remove a core Wonderflux action in your child theme functions file with the code:
remove_action('wf_hook_name','wf_function_name',postitionnumber);

3) Add a filter to a display function.

4) Use over 100 location-aware hooks.

If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
Get involved at http://wonderflux-framework.googlecode.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

OTHER REFERENCE

Note the use of PHP layout display functions to setup CSS layout divs in WFX Girder.
They use a special definitions such as 'half' and 'quarter' to make layout easier.
By using these functions you are able to create layouts that dynamically resize.

You will find reference to all hooks in themes/wonderflux/wf-includes/wf-display-hooks.php

You will find a number of more interesting display functions for you to use in your
themes in themes/wonderflux/wf-includes/wf_engine.php in the wflux_functions class

You will see a reference guide in the head of your document if you view source.
It is currently working in a very similar way to the Blueprint CSS framework, the same
rules and references apply - http://www.blueprintcss.org/