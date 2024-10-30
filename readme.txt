=== Plugin Name ===
Contributors: DaganLev, huntlyc
Tags: hotscot, Page Gallery, images
Donate link: http://hotscot.net
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: trunk

Allows you to add a collection of unrelated images to a page or post and then refer to them in your theme

== Description ==
Allows you to add a collection of unrelated images to a page or post and then refer to them in your theme

== Installation ==

To install this plugin please use the "install" feature from the WordPress site.

To make plugin work you can either use this line:
&lt;?php if(function_exists('hpg_paint_images')) echo(hpg_paint_images());	?&gt;
This will paint all the images with A tags (link) to the larger images

Or if you are feeling adventurous you can use the object function:
hpg_paint_images_obj();

Which will return an array of objcets with "Thumb", "Large" and "Title"...

== Changelog ==

1.0.0
=======
Init

1.0.1
=======
Fixed a few things to fit with 3.5

1.0.2
=======
core updates

== Upgrade Notice ==
None

== Screenshots ==
None

== Frequently Asked Questions ==
None
