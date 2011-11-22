WONDERFLUX WORDPRESS THEME FRAMEWORK
VERSION v1.0 Release Candidate 1

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

Welcome to the Wonderflux - a free, Open Source theme framework for WordPress and BuddyPress.

Wonderflux is distributed under the GPL v2 license just like WordPress
(yes - that means FREE basically!). Just to clarify then - YES, you can 
use Wonderflux on as many commercial, non-commercial, and personal WordPress 
sites as you wish, without any fee or subscription required.

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

Wonderflux is amazingly flexible, with many filters hooks and
functions you can over-ride through your child theme functions file.

It also heavily uses the principle of 'template parts' - please see
the WFX Girder theme structure for more on this.

Only advanced developers will need to actually override the standard WordPress
template file structure in their Wonderflux child theme (ie footer.php or header.php)
IF YOU OVERRIDE THESE YOU WOULD BE STRONGLY ADVISED TO DUPLICATE THE CORE FILES
FROM WONDERFLUX INTO YOUR CHILD THEME DIRECTORY to retain the Wonderflux
structure and hook system.

The quickest way to start customsing your theme is by changing the template parts 
as shown in the WF Girder theme. Anything that has '-content' in the name is a template part, ie:

footer-content.php
header-content-php
loop-content-page.php (page loop content - only used on pages)
loop-content.php (general loop content - used everywhere else)

These files just contain very simple content - not all the surrounding code. 
If you have not worked with theme frameworks, child themes and get_template_part() before 
- welcome to the future of WordPress theme development!

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
IF THE FILE DOESN'T EXIST FOR THE LOCATION, it rolls back to the file footer-content.php

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