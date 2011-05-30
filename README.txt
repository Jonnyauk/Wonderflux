!! WARNING !!

You have downloaded an Alpha development version of the working source code
from the trunk of the development code! THIS MAY NOT BE 100% STABLE!!

If you are using this for development purposes, You should probably make sure
you have the latest version available from:

http://code.google.com/p/wonderflux-girder-theme/source/checkout

The 3 directories are:

branches - Holds any advanced development and experiments
tags - Holds the released versions (same as zip files)
trunk - Holds the latest development version that is being worked on

Zip packages of stable release beta versions of Wonderflux are available from:
http://code.google.com/p/wonderflux-framework/downloads/

THIS VERSION IS FOR TESTING AND FEEDBACK ONLY, AND ALTHOUGH EVERYTHING
WILL PROBABLY BE FINE YOU PROBABLY SHOULD NOT RUN THIS VERSION ON LIVE SITES!

There may also be problems with child themes, for testing compatibility we run
the trunk version of WFX Girder theme, which is developed to use the latest
code, structure and functionality in development.

http://wonderflux-girder-theme.googlecode.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

WONDERFLUX WORDPRESS THEME FRAMEWORK
VERSION 0.93 ALPHA DEVELOPMENT VERSION

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

UPGRADE NOTES

IMPORTANT - BEFORE YOU UPGRADE ensure you have properly checked over the
change notes. some functionality has changed a-little and you should always
make sure your custom Wonderflux child theme is compatible with the latest
release on Wonderflux BEFORE upgrading any live site!

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

Keep informed on Wonderflux development:
- Following @Wonderflux on Twitter
- Subscribing to the beta RSS feed: http://feeds.feedburner.com/wonderflux-beta-testing
- Subscribing to beta mailing list at: http://feedburner.google.com/fb/a/mailverify?uri=wonderflux-beta-testing
Drop by http://www.wonderflux.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

!! WARNING !!

THIS IS A PRE-RELEASE BETA - there is still a-lot of cool stuff to build!
Although Wonderflux functions correctly, it currently lacks some
elements and refinements that can easily be added just like any other
WordPress theme - for instance menus and custom post thumbnails.

Although there are big plans for Wonderflux options and controls
for less experienced theme designers and users, the current
functionality is mostly contained in the child themes functions
functions.php file and style.css file. This structure will remain
in future versions for developers, but almost everything will move
to simple options panels within WordPress admin area.

In the next release some things are going to change... it is a
beta after-all! This should be just a simple search and replace
on your source code for reference to display function calls
and will be fully documented.

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
By using these functions you are able to create layouts that dynamically resize.

You will find reference to all hooks in themes/wonderflux/wf-includes/wf-display-hooks.php

You will find a number of more interesting display functions for you to use in your
themes in themes/wonderflux/wf-includes/wf_engine.php in the wflux_functions class

You will see a reference guide in the head of your document if you view source.
It is currently working in a very similar way to the Blueprint CSS framework, the same
rules and references apply - http://www.blueprintcss.org/ - NOTE this is likely to change!

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

v0.92 CHANGE NOTES

43 patches have been applied to the previous release upto revision 91!

Along with various bug fixes and internal code optimisation, some highlights of Wonderflux v0.92 include:

- New controls for complete document output control (including full HTML5 support), including language, character type and encoding.
- Much deeper background divs control for creative design effects, now inside containers for header, main content and footer background divs.
- JQuery - advanced function for using JQuery, including CDN and custom hosting support.
- JQuery Cycle - advanced configurable function to build rotating banners and other elements.
- Moreganic Tabs - JQuery tab script.

CHANGE NOTES

Full development track can be found at http://code.google.com/p/wonderflux-framework/updates/list