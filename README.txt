WONDERFLUX WORDPRESS THEME FRAMEWORK
VERSION 0.913

Copyright (C) 2011 Jonny Allbut - Jonnya Freelance Creative www.jonnya.net

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

Welcome to the beta testing phase of Wonderflux
An advanced creative Open Source theme framework for WordPress.

Keep informed on Wonderflux development:
- Following @Wonderflux on Twitter
- Subscribing to the beta RSS feed: http://feeds.feedburner.com/wonderflux-beta-testing
- Subscribing to beta mailing list at: http://feedburner.google.com/fb/a/mailverify?uri=wonderflux-beta-testing
- Drop by http://www.wonderflux.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

GET INVOLVED

Wonderflux is distributed under the GPL v2 license just like WordPress
(yes - that means FREE basically!). There are no plans to ever charge
for Wonderflux, it will always remain GPL v2 license.

Just to clarify then - YES, you can use Wonderflux on as many commercial,
non-commercial, personal WordPress sites as you wish, without any fee
or subscription required.

Any developers are welcome to offer code contributions - we are open to
any ideas and improvements;) The goal is to develop a framework for
everyone to use, for free, to make amazing WordPress sites!

Drop by:
http://wonderflux-framework.googlecode.com/

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

!! WARNING !!

THIS IS A PRE-RELEASE BETA - there is still a-lot of cool stuff to build!
Although Wonderflux functions correctly, it currently lacks some
elements and refinements that can easily be added just like any other
WordPress theme - for instance menus, custom post thumbnails.

IT IS NOT RECOMMENDED FOR ANY SORT OF MISSION CRITICAL LIVE
PRODUCTION WEBSITES, and is provided purely for your feedback
and hopefully development contributions.

Although there are big plans for Wonderflux options and controls
for less experienced theme designers and users, the current
functionality is mostly contained in the child themes functions
functions.php file and style.css file. This structure will remain
in future versions for developers, but almost everything will move
to simple options panels within WordPress admin area.

Also, at version 0.92 there is likely to be a change in the PHP
class/function structure, along with full support and optimisation
for WordPress 3.1. So some things are going to change... it is a
beta after-all! This should be just a simple search and replace
on your source code for reference to display function calls
and will be fully documented.

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
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

3) Add a filter to a display function (documentation to come).

4) Use over 100 location-aware hooks (documentation to come).

If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
Get involved at http://wonderflux-framework.googlecode.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

OTHER REFERENCE

Note the use of PHP layout display functions to setup CSS layout divs in WFX Girder.
They use a special definitions such as 'half' and 'quarter' to make layout easier.

You will find reference to all hooks in themes/wonderflux/wf-includes/wf-display-hooks.php

You will find a number of more interesting display functions for you to use in your
themes in themes/wonderflux/wf-includes/wf_engine.php in the wflux_functions class

You will see a reference guide in the head of your document if you view source.
It is currently working in a very similar way to the Blueprint CSS framework, the same
rules and references apply - http://www.blueprintcss.org/ - NOTE this is likely to change!

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

CHANGE NOTES

We just show the last two versions, all previous reference can be found at
http://code.google.com/p/wonderflux-framework/updates/list

WONDERFLUX VERSION 0.913 - Feb 9th 2011

r46 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=46
- Code tidying for consistency, nothing major here!

r45 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=45
- More logical, shorter params on wfx_perma_img function

r44 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=44
- More logical, shorter params on wfx_perma_img function

r43 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=43
- New params for controlling div output around edit_meta output

r42 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=42
- Completed main reorganisation of core code structure.
- This should mean no more changes for child theme developers!

r41 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=41
- Minor bug catching

r40 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=40
- Cleanup core display files left from testing (no more over-rides when you don't want them!)

r39 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=39
- BIG UPDATE FOR CHILD THEME DEVELOPERS, PLEASE SEE LATEST VERSION OF WFX GIRDER FOR DEMO - Things just got a-lot simpler for you!
- Standardised ALL user functions with prefix wfx_
- Cleanup core theme files where required
- NO MORE $global in child themes!

r37-38 Versioning and typo updates

r36 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=36
- Version number update

r35 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=35
- First preparation for translation

r34 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=34
- Big update to internal structure.
- Testing solutions whist completing restructure of core (the guidance no longer applies - see later revisions)

r33 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=33
- Readme amends

r32 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=32
- Use get_template_directory_uri() instead

r31 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=31
- Correct escape of output bugs

r29-30 Versioning and number updates


WONDERFLUX VERSION 0.912 - Jan 23th 2011

r26-r28 - readme.txt updates.

r25 (submitted by Lee Willis) http://code.google.com/p/wonderflux-framework/source/detail?r=25
- Fix errors from uninitialised variables

r24 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=24
- Escape output in widget building function.
- Complete parameter documentation on function.

r23 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=23
- Versioning update for trunk alpha and small typos.

r22 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=22
- Fixed bug in Twitter display function - incorrect position of closing tag.

r21 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=21
- Move wf-theme-core.php call here so can be removed from child themes.
- Replace require() with core WordPress load_template()

r20 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=20
- Tidy core functions.
- Remove redundant code.
- Improve comments at top.
- Replace require() with core WordPress load_template()

r19 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=19
- Improve the data sanitisation function by only saving specific values in layout.
options array and not allow any other junk.

r18 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=18
- New validation function when saving core Wonderflux layout options which was
temporarily removed during beta testing. Uses whitelist technique - more fiddly
to maintain but stops any funny business!

r17 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=17
- Fixes bug in version check.

r14-r16 - trunk version number updates