!! WARNING !!

You have downloaded a development version of the working source code - this may not be stable!

Zip packages of stable release versions of Wonderflux are available from:
http://code.google.com/p/wonderflux-framework/downloads/

THIS IS FOR TESTING AND FEEDBACK ONLY,
AND ALTHOUGH EVERYTHING WILL PROBABLY BE FINE
YOU PROBABLY SHOULD NOT BE RUN ON LIVE SITES

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

WONDERFLUX WORDPRESS THEME FRAMEWORK PUBLIC BETA
VERSION 0.912 SVN TRUNK DEVELOPMENT BETA

Copyright (C) 2011 Jonny Allbut - Jonnya Freelance Creative www.jonnya.net

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

Welcome to the beta testing phase of Wonderflux, the new theme framework for WordPress.
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

THIS IS A PRE-RELEASE BETA - we still have a-lot of work to complete and although it functions correctly it currently lacks some elements.
IT IS NOT RECOMMENDED FOR ANY SORT OF MISSION CRITICAL LIVE PRODUCTION WEBSITES and is provided purely for your feedback and hopefully development contributions.

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

CHANGE NOTES - We just show the last two versions now, all previous reference can be found at http://code.google.com/p/wonderflux-framework/updates/list

0.912 - SVN TRUNK DEVELOPMENT BETA CHANGE NOTES

0.911 - PUBLIC BETA RELEASE CHANGE NOTES

- r8 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=8
- Missed the screenshot file when the tag as created, doh!

- r11 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=11
- Various data validation fixes and bug fix for sidebar position.

- r13 (committed by jonnya) http://code.google.com/p/wonderflux-framework/source/detail?r=13
- Update checker functions, fetches simple version feed from feedburner, no personal data gathered. 
- Its the cleanest off-server solution (that I can think of for the moment!) 
- Will be used later on for release update info/changes for developers and designers.