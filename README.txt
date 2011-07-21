!! WARNING !!

You have downloaded a development version of the working source code
from the trunk of the development code! THIS MAY NOT BE 100% STABLE!!

IT IS FOR TESTING AND FEEDBACK ONLY - IT MAY CONTAIN INCOMPLETE CODE.

If you are using this for development purposes, You should make sure
you have the latest development version available. Non-members may check
out a read-only working copy from the Google Code SVN:
http://wonderflux-framework.googlecode.com/svn/trunk/

If you wish to run the current latest public release, visit:
http://code.google.com/p/wonderflux-framework/downloads/

NOTE: This development version may contain new core functionality, for testing,
always run the trunk version of WFX Girder theme, which is developed to use the latest
Wonderflux code, structure and functionality. Refer to the functions.php file
in particular when upgrading your own themes:
http://wonderflux-girder-theme.googlecode.com

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

WONDERFLUX WORDPRESS THEME FRAMEWORK
VERSION 0.931alpha DEVELOPMENT VERSION

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

Welcome to Wonderflux
An advanced creative Open Source theme framework for WordPress.

UPGRADE NOTES

IMPORTANT - BEFORE YOU UPGRADE ensure you have properly checked over the
change notes. some functionality has changed a-little and you should always
make sure your custom Wonderflux child theme is compatible with the latest
release on Wonderflux BEFORE upgrading any live site!

Full change notes can be found at:
http://code.google.com/p/wonderflux-framework/updates/list

Drop by http://www.wonderflux.com
Follow @Wonderflux on Twitter for all the latest development updates

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

GET INVOLVED

Wonderflux is distributed under the GPL v2 license just like WordPress
(yes - that means FREE basically!). There are no plans to ever charge
for Wonderflux, it will always remain GPL v2 license.

Just to clarify then - YES, you can use Wonderflux on as many commercial,
non-commercial, client and personal WordPress sites as you wish, without any fee
or subscription required.

Any developers are welcome to offer code contributions - we are open to
any ideas and improvements;) The goal is to develop a framework for
everyone to use, for free, to make amazing WordPress sites!

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

QUICK START REFERENCE

Wonderflux is a theme framework that works with 'child themes'.

Although Wonderflux will function if activated directly,
the best way to use Wonderflux is by activating a child theme.

You should have BOTH the Wonderflux child theme (like WFX Girder) and Wonderflux
installed in your theme directory, then simply activate the Wonderflux child theme.

WFX Girder is a child theme designed for Wonderflux that can be downloaded from:
http://wonderflux-girder-theme.googlecode.com

If you take a look at the files of WFX Girder, you will see that with just a handful
of files (down to even just 1 or 2!) you can create your own WordPress theme
...and everything else is provided by Wonderflux! As with any child theme, if the file
(ie header.php) doesn't exist in your child theme folder, the 'parent theme' (Wonderflux) get's used.

Wonderflux is amazingly flexible, with many filters hooks and
functions you can over-ride through your child theme functions file.

Only advanced developers will need to actually override the standard WordPress
template file structure in their Wonderflux child theme (ie footer.php or header.php)
IF YOU OVERRIDE THESE YOU WOULD BE STRONGLY ADVISED TO DUPLICATE THE CORE FILES
FROM WONDERFLUX INTO YOUR CHILD THEME DIRECTORY to retain the Wonderflux
structure and hook system.

> Start of with using the template parts as shown in the WF Girder
child theme. Anything that has '-content' in the name is a template part, ie:

footer-content.php
header-content.php
loop-content-page.php (page loop content - only used on pages - see below for more information)
loop-content.php (general loop content - used everywhere else)
sidebar-content.php

These files just contain very simple content - just what you want displayed within
the given area of your site. All of the rest of the site code, structure and CSS is dealt
with for you by Wonderflux for you. If you have not worked with theme frameworks,
child themes and get_template_part() before - welcome to the future of
WordPress theme development!

You can of-course strip all this back to nothing and override 99% of Wonderflux,
developers can over-ride, filter and maipulate just about everything... or just the bits you need!

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