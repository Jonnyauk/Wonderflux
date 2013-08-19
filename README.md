Wonderflux WordPress theme framework
====================================
#### Verson v1.1BETA
#### Copyright (c)2013 Jonny Allbut 
#### http://jonnya.net / @Jonnyauk

This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; either version 2 of the License, 
or (at your option) any later version.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
See the GNU General Public License for more details.

You may have received a copy of the GNU General Public License along 
with this program; if not, write to the Free Software Foundation, Inc., 
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

You may also view the license online: http://www.gnu.org/licenses/gpl-2.0.html

Welcome to Wonderflux
----------------------------------------------------------------------

### A free, professional Open Source theme framework for WordPress & BuddyPress

Wonderflux is distributed under the GPL v2 license and just like WordPress is 
free to download, use and modify. YES, you can use Wonderflux on as many 
commercial, non-commercial and personal WordPress websites as you wish without 
any a fee, subscription or credit required (but it would be appreciated!)

**Anyone is welcome to suggest ideas or code on GitHub**, the goal is to 
develop a fantastic professional theme framework for everyone to use, for free, 
to make amazing WordPress sites!

* **Project development home:**
  https://github.com/Jonnyauk/Wonderflux
* **Ideas and bug reports:**
  https://github.com/Jonnyauk/Wonderflux/issues
* **Development release milestones:**
  https://github.com/Jonnyauk/Wonderflux/milestones
* **Follow Wonderflux on Twitter:**
  https://twitter.com/wonderflux

Help and documentation
----------------------------------------------------------------------

http://wonderflux.com/guide

The Wonderflux documentation site is a (slowly!) growing reference of all the 
functions, hooks and filters you can use in your child themes. Apologies, 
coding is much more fun than writing documentation, but there is fairly 
complete in-line code documentation in the wf-includes directory files for 
your reference if you dig around.

* **Hooks:** http://wonderflux.com/guide/hook/
* **Functions:** http://wonderflux.com/guide/function/
* **Filters:** http://wonderflux.com/guide/filter/
* **Constants:** http://wonderflux.com/guide/constant/
* **Files:** http://wonderflux.com/guide/file/

Getting started
----------------------------------------------------------------------

Wonderflux is a theme framework (or 'parent theme') that you use by building 
your own child themes. You simply have both Wonderflux and your child theme 
uploaded to your normal WordPress theme directory.

By activating your child theme in the WordPress admin area just like a normal 
theme, it will automatically use files and functionality from Wonderflux, 
giving you a powerful toolkit and dynamic CSS layout grid to rapidly develop 
bespoke WordPress themes for any purpose.

Child themes can be as simple as a single style.css file, or include any 
number of files that override or add functionality to Wonderflux or your 
website. A simple rule to remember is that if a core Wonderflux theme 
layout file exists in your child theme directory (eg header-content.php) 
it will be used instead of the core Wonderflux file. Read more about this 
in the 'Template parts' section below.

Only advanced developers will need to actually override other standard 
Wonderflux template files in their Wonderflux child theme, 
for example header.php or footer.php

**IF YOU OVERRIDE THESE YOU SHOULD DUPLICATE THE CORE FILE FROM 
WONDERFLUX INTO YOUR CHILD THEME DIRECTORY,**
this will ensure you retain the Wonderflux structure and hook system 
to give you the perfect 'starter' file to begin playing with!

The quickest way to begin learning how to use Wonderflux is to download the 
WFX Girder demonstration child theme and examine the file structure and code. 
There are lots of comments in the file to get you started.

### WFX Girder - a demonstration theme

https://github.com/Jonnyauk/wonderflux-girder

Template parts
----------------------------------------------------------------------

These important files contain very simple content and layout. Separating 
the surrounding layout logic and code from the actual content makes the 
files simpler to edit. They can be thought of as re-useable parts that 
help avoid repetition of code.

An example is loop-content.php - used to display the main content of your 
site. Instead of repeating common CSS classes and layout, just edit this one 
file, or see below on extending with 'location awareness' for more 
complex configurations.

If you have not worked with theme frameworks, parent themes or 
get_template_part() before - welcome to the future of WordPress 
theme development... and this barely scratches the surface of what 
Wonderflux can do for you!

**Any file that has '-content' in the name is a template part:**
* header-content-php
* loop-content.php (used to display main content)
* sidebar-content.php
* footer-content.php

### Extend template parts with location awareness

Wonderflux extends the principle of 'template parts' by adding 
'location awareness'. This automatically adds the ability to use 
files such as header-content-category.php which will override 
header-content.php when viewing a category archive.

**Location awareness works automatically** - if the file exists, it gets used, 
if it doesn't it falls back to the default file. Listed below is the order, 
or 'cascade' of files that will be used in given locations, 
using template part loop-content.php as an example template part:

* **SINGLE POST (INCLUDING CUSTOM POST TYPES)**
  *NOTE: Normal 'post' post type uses loop-content-single.php 
  NOT loop-content-single-post.php*
  1. loop-content-single-{POST-TYPE-SLUG}.php
  2. loop-content-single.php
  3. loop-content.php

* **CATEGORY ARCHIVE**
  1. loop-content-category-{CATEGORY-SLUG}.php
  2. loop-content-category.php
  3. loop-content.php

* **TAXONOMY ARCHIVE**
  1. loop-content-taxonomy-{taxonomy-name}-{taxonomy-term}.php
  2. loop-content-taxonomy-{taxonomy-name}.php
  3. loop-content-taxonomy.php
  4. loop-content.php

* **TAG ARCHIVE**
  1. loop-content-tag-{tag-slug}.php
  2. loop-content-tag.php
  3. loop-content.php

* **DATE ARCHIVE**
  *NOTE: 4 digit year, 2 digit month with leading zero if less than 10*
  1. loop-content-date-{YEAR}-{MONTH}.php
  2. loop-content-date-{YEAR}.php
  3. loop-content-date.php
  4. loop-content.php

* **AUTHOR**
  1. loop-content-author.php
  2. loop-content.php

* **HOMEPAGE**
  1. loop-content-home.php
  2. loop-content.php

* **SEARCH**
  1. loop-content-search.php
  2. loop-content.php

* **ARCHIVE**
  1. loop-content-archive.php
  2. loop-content.php

* **ATTACHMENT**
  1. loop-content-attachment.php
  2. loop-content.php

* **PAGE**
  1. loop-content-page.php
  2. loop-content.php

* **404 ERROR PAGE**
  1. loop-content-404.php
  2. loop-content.php

The dynamic CSS grid layout system
----------------------------------------------------------------------

Wonderflux generates a complete CSS grid layout system based on Blueprint CSS, 
the difference is you can configure the number and width of columns, site 
container width and more by going to the Wonderflux options page.

The core CSS layout is generated by the following values:

* Site container width
* Number of columns
* Width of column
* Site container position
* Main content width
* Sidebar 1 width
* Sidebar 1 position

### Building smarter sized divs

Note the use of the wfx_css() layout display function to setup CSS layout divs 
in the WFX Girder demonstration theme. The 'size' parameter is a relative size 
description of the width of a div in relation to the width of the main content. 
See an example below of 2 quarters and one half div - making a complete row:

    <div <?php wfx_css('size=quarter'); ?>>
      <p>I'm quarter</p>
    </div>
	
    <div <?php wfx_css('size=quarter'); ?>>
      <p>I'm quarter</p>
    </div>
	
    <div <?php wfx_css('size=half&last=Y'); ?>>
      <p>I'm half, last in row so I need last=Y param</p>
	</div>

By using this function to define your div CSS classes, you can create easy to 
understand layout markup with unique layouts that dynamically resize as you 
change the core CSS grid configuration.

With the current CSS grid system, you MUST add the parameter 'last=Y' to the 
last div in a row, This will hopefully not be required with the new responsive 
grid system in development - *more soon!*

### More advanced development

The entire grid configuration and all 'wrapper' output can be filtered and 
removed if required, don't worry - remember this is a framework! Oh and if 
you spot somewhere that needs a filter or more flexibility, just let me know 
on the GitHub issues page!

DON'T HACK WONDERFLUX!
----------------------------------------------------------------------

You should not modify the Wonderflux theme framework to avoid issues with 
updates in the future. One of the main advantages of using a theme framework 
is the ability to update the core framework to quickly and easily support 
future versions of WordPress and improve performance and functionality.

**There are lots of ways to use Wondeflux from your child theme:**

1.	Filter the layout values to change the layout configuration 
	at any time (including underlying CSS grid system).

2.	Create a main template file with the same name in your child
	theme - this will be used instead of the core Wonderflux file.

3.	Create a function with the same name as a core Wonderflux 
	display function in your child theme - this will be used 
	instead of the core Wonderflux function.

4.	Remove a core Wonderflux action in your child theme 
	functions.php file with the remove_action():
	remove_action('wf_hook_name','wf_function_name',priority);

5.	Add a filter to change or add to the code Wonderflux outputs.

6.	Use over 100 location-aware hooks to detect what type of 
	content is being viewed and automatically add unique content.
	If you still feel the need to hack the Wonderflux core code, 
	why not submit a patch or suggestion? Get involved at 
	https://github.com/Jonnyauk/Wonderflux