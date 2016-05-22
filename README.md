![wonderflux](http://wonderflux.com/wonderflux-logo.png)

====================================
#### Wonderflux WordPress theme framework
#### Version v2.3 - DEVELOPMENT VERSION - NOT FOR LIVE PRODUCTION ENVIRONMENTS!
#### Stable/production releases: https://github.com/Jonnyauk/Wonderflux/releases
#### Copyright (c)2014 Jonny Allbut
#### [Web: http://jonnya.net](http://jonnya.net)
#### [GitHub: Jonnyauk](https://github.com/Jonnyauk/)
#### [Twitter: @Jonnyauk](https://twitter.com/jonnyauk/)
====================================

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

### A free, professional Open Source responsive theme framework for WordPress & BuddyPress

Wonderflux is distributed under the GPL v2 license and just like WordPress is
free to download, use and modify. YES, you can use Wonderflux on as many
commercial, non-commercial and personal WordPress websites as you wish without
any a fee, subscription or credit required (but it would be appreciated!)

**Anyone is welcome to suggest ideas or code on GitHub**, the goal is to
develop a fantastic professional theme framework for everyone to use, for free,
to make amazing WordPress sites! I appreciate any comments or patches - big or small!

* [Project development home](https://github.com/Jonnyauk/Wonderflux)
* [Ideas and bug reports](https://github.com/Jonnyauk/Wonderflux/issues)
* [Dev milestones](https://github.com/Jonnyauk/Wonderflux/issues/milestones)
* [Follow Wonderflux on Twitter](https://twitter.com/wonderflux)

Help and documentation
----------------------------------------------------------------------

The Wonderflux documentation site is a (slowly!) growing reference of all the
files, functions, hooks & filters you can use in your child themes. Apologies,
coding is much more fun than writing documentation, but there is complete
in-line code documentation (PHPDoc format) in functions.php to refer to.

Please note the documentation website is somewhat out of date - I've fallen behind
a little on working on this unfortunately, due to my normal (paid!) work. However,
once I've completed Wonderflux v2.0 I'll be turning my attention back to documentation!

* [All documentation](http://wonderflux.com/guide)
* [Hooks](http://wonderflux.com/guide/hook)
* [Functions](http://wonderflux.com/guide/function)
* [Filters](http://wonderflux.com/guide/filter)
* [Constants](http://wonderflux.com/guide/constant)
* [Files](http://wonderflux.com/guide/file)

Quickstart
----------------------------------------------------------------------

**Getting bored already and want to dive in - lets go!**

1.  [Download the latest stable release of Wonderflux.]
    (https://github.com/Jonnyauk/Wonderflux/releases)
    NOTE: Ensure directory is called *wonderflux*
    if downloading straight from GitHub master branch (not Wonderflux-master).
2.  [Download WFX Girder demo child theme.]
    (https://github.com/Jonnyauk/wonderflux-girder/releases)
    NOTE: You can rename this directory to anything you wish.
3.  Unzip and install both directories to wp-content/themes directory.
4.  Activate WFX Girder demo child theme.
5.  Take a look at the source code of WFX Girder child theme to learn more!

Layout and configuration options
----------------------------------------------------------------------

Any active child theme of Wonderflux automatically has access to a number of theme
configuration options (almost all are filterable on individual view too - cool!)

You'll find these in the admin area under 'Appearance'.

**Feeling brave?** Beta testers can try out the shiny new Customizer options by
installing the [WP Flux Layout plugin](https://github.com/Wider-uk/wp-flux-layout) plugin.
It automatically interacts with Wonderflux if you have a child theme active - cute!

* Number of vertical columns
* Main content width
* Main container position
* Content width
* Sidebar width
* Sidebar position
* Sidebar/main content responsive breakpoint for full width
* Sidebar display
* Media content width
* Hide Wonderflux page templates
* Document type
* Document language
* Document character set

Other features available in admin area:

* Generate a single compressed/minified Flux Layout CSS file, less than 6k Gzipped!
* Full server environmental system report
* Backup and restore Wonderflux options

Getting started
----------------------------------------------------------------------

If you've used WordPress much, you are going to be right at home here!
Wonderflux is developed to be similar to WordPress - it uses many of the
same principles like actions and filters. It also features strong compliance
with the latest WordPress theme standards (unlike most other frameworks!)
and does pretty-much everything the *WordPress way*.

Wonderflux is a theme framework (sometimes called a *parent theme*) that you
use by building your own child themes. You simply have both Wonderflux and
your child theme uploaded to your normal WordPress theme directory.

By activating your child theme in the WordPress admin area just like a normal
theme, it will automatically use files and functionality from Wonderflux,
giving you a powerful toolkit and dynamic CSS layout system to rapidly
develop bespoke WordPress themes for any purpose.

What makes a child theme work is a single line in the top section of
style.css - 'Template: wonderflux'. This lets WordPress know you want
to use Wonderflux as a parent theme.

Child themes can be as simple as a single style.css file, or include any
number of files that override or add functionality to Wonderflux or your
website. A simple rule to remember is that if a core Wonderflux theme
layout file exists in your child theme directory (eg header-content.php)
it will be used instead of the core Wonderflux file. Read more about this
in the *Template parts* section below.

Only advanced developers will need to actually override other standard
Wonderflux template files in their Wonderflux child theme,
for example header.php or footer.php

**IF YOU OVERRIDE THESE YOU SHOULD DUPLICATE THE CORE FILE FROM
WONDERFLUX INTO YOUR CHILD THEME DIRECTORY,**
this will ensure you retain the Wonderflux structure and hook system
to give you the perfect *starter* file to begin playing with!

### WFX Girder - a demonstration theme

The quickest way to begin learning how to use Wonderflux is to download the
WFX Girder demonstration child theme and examine the file structure and code.
There are lots of comments in each file to get you started.

WFX Girder is not only a good way to learn Wonderflux, but also provides a
solid base starter child theme for you to build your own themes with.

[Download WFX Girder theme](https://github.com/Jonnyauk/wonderflux-girder/releases)

[Live demo of WFX Girder theme](http://wonderflux.com/demo-girder/)

Template parts
----------------------------------------------------------------------

These important files contain very simple content and layout. Separating
the surrounding layout logic and code from the actual content makes the
files simpler to edit. They can be thought of as re-useable parts that
help avoid repetition of code.

An example is loop-content.php - used to display the main content of your
site. Instead of repeating common CSS classes and layout just edit this
one file, or see below on optionally extending with *location awareness*
for more complex configurations.

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

Wonderflux extends the principle of *template parts* by adding
*location awareness*. This automatically adds the ability to use
files such as header-content-category.php which will override
header-content.php when viewing a category archive.

**Location awareness works automatically** - if the file exists it gets used,
otherwise it falls back to using the default file (the last listed). You will
see the files shown below in the order, or *cascade* of files that will be
used in given locations. We are using template part loop-content.php as an
example template part, but this will work with any main template part, or
indeed your own files if you use the wfx_get_template_part() function.

New to Wonderflux v2.1 and above is expansion of loop-content-archive.php
template part, this is now used as a fallback when viewing category, tag,
taxonomy, date or other archive views for a consistent archive experience.

Also new to Wonderflux 2.1 and above is basic non-desktop/mobile/tablet detection.
Create unique non-desktop optimised screen alternative template parts by creating an
additional file with '-mobile' appended, like: loop-content-single-mobile.php which
will be used instead on non-desktop devices.

* **SINGLE POST (INCLUDING CUSTOM POST TYPES)**
  *NOTE: Normal 'post' post type uses loop-content-single.php
  NOT loop-content-single-post.php*
  1. loop-content-single-{POST-TYPE-SLUG}.php
  2. loop-content-single.php
  3. loop-content.php

* **CATEGORY ARCHIVE**
  1. loop-content-category-{CATEGORY-SLUG}.php
  2. loop-content-category.php
  3. loop-content-archive.php (common archive template)
  4. loop-content.php

* **TAXONOMY ARCHIVE**
  1. loop-content-taxonomy-{taxonomy-name}-{taxonomy-term}.php
  2. loop-content-taxonomy-{taxonomy-name}.php
  3. loop-content-taxonomy.php
  4. loop-content-archive.php (common archive template)
  5. loop-content.php

* **TAG ARCHIVE**
  1. loop-content-tag-{tag-slug}.php
  2. loop-content-tag.php
  3. loop-content-archive.php (common archive template)
  4. loop-content.php

* **DATE ARCHIVE**
  *NOTE: 4 digit year, 2 digit month with leading zero if less than 10*
  1. loop-content-date-{YEAR}-{MONTH}.php (4 digit year, 2 digit month with leading zero if less than 10).
  2. loop-content-date-{YEAR}.php (4 digit year)
  3. loop-content-date.php
  4. loop-content-archive.php (common archive template)
  5. loop-content.php

* **POST ARCHIVE**
  *NOTE: especially useful for custom post type archives - introduced in v2.0!*
	1. loop-content-archive-{post-type-slug}.php
	2. loop-content-archive.php (common archive template)
	3. loop-content.php

* **AUTHOR**
  1. loop-content-author.php
  2. loop-content.php

* **HOMEPAGE**
  1. loop-content-home.php
  2. loop-content.php

* **SEARCH**
  1. loop-content-search.php
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

Wonderflux generates a complete responsive CSS layout system built with
[Flux Layout](https://github.com/Jonnyauk/flux-layout). This replaces the
pixel based system in Wonderflux v1 and generates a number of useful media
queries based on your options (set in Appearance > Wonderflux).

Your Flux Layout system is generated from the following values
set on the Wonderflux Stylelab options page:

* Site container width unit (default size)
* Number of columns
* Site container position
* Main content width
* Sidebar 1 width
* Sidebar 1 position

### Column systems

Flux Layout deals with responsive layout design by generating a complete
custom grid system based on percentage values. In addition to the columns
you set, it also generates half, quarter, fifth, eighth and tenth column systems.

###  Responsive media queries

Wonderflux 2 also simplifies the creation of layout code in your theme files
compared to version 1. IMPORTANT: wfx_css() function is no longer required,
you simply use normal CSS rules as defined in the Flux Layout system.

It is built with two files, which are included by default when you activate
any Wonderflux child theme. Documentation to come, but in the meantime the
easiest thing to do is to have a look at the file output and look at WFX Girder
child theme demo. See the <head> output of your document:

* wf-css-flux-layout-core.css - basic rules and reset
* wf-css-flux-layout.php - dynamic generated CSS layout rules

I have some more development planned for the Flux Layout system and how it
integrates with Wonderflux. Also be aware that things may change a-little
following feedback from users and testing - this is a beta after all!

### More advanced development

The entire grid configuration and all wrapper output can be
[filtered](http://wonderflux.com/guide/filter/) and
[removed if required](http://wonderflux.com/guide/constant/wf_theme_framework_replace/).
Remember this is a framework! Oh and if you spot somewhere
that needs a filter or more flexibility, just let me know on the
[GitHub issues page](https://github.com/Jonnyauk/Wonderflux/issues).

DON'T hack Wonderflux!
----------------------------------------------------------------------

You should not modify the Wonderflux theme framework to avoid issues with
updates in the future. One of the main advantages of using a theme framework
is the ability to update the core framework to quickly and easily support
future versions of WordPress and improve performance and functionality.

**There are lots of ways to use Wonderflux from your child theme:**

1.	Filter the layout values to change the layout configuration
    at any time (including underlying CSS grid system).

2.	Create a main template file with the same name in your child
    theme - this will be used instead of the core Wonderflux file.

3.	Create a function with the same name as a core Wonderflux
    display function in your child theme - this will be used
    instead of the core Wonderflux function.

4.	Remove a core Wonderflux action in your child theme
    functions.php file with the remove_action().

5.	Add a filter to change or add to the code Wonderflux outputs.

6.	Use over 100 location-aware hooks to detect what type of
    content is being viewed and automatically add unique content.

Still feel the need to hack Wonderflux?
----------------------------------------------------------------------

If you still feel the need to hack the Wonderflux core code - that means that
there is probably something that needs changing! See somewhere you'd like a
filter, spotted a bug, or see a function that needs extra parameters? Why not get
involved with the project on GitHub, all feedback and contributions are welcome.

How to contribute - all patches welcome!
----------------------------------------------------------------------

Drop by the [GitHub Wonderflux project page](https://github.com/Jonnyauk/Wonderflux)
to find out more. Branches and patches are probably the best way to contribute, but
if you're not able to do this, do not worry - you can still send over your ideas,
just [create a new issue on GitHub](https://github.com/Jonnyauk/Wonderflux/issues)
and explain how you would like to see Wonderflux improved... and I'll do my best to
accommodate it thanks!
