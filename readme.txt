=== Custom Post Type Images ===
Contributors: yonisink
Donate link: http://shop.social-ink.net
Tags: custom post type, custom post type archives, image attachments, post type images, cpt images
Requires at least: 3
Tested up to: 3.3
Stable Tag: 0.6

Upload and attach a 'featured' image to any registered custom post types and call it via shortcode or template tag in your theme.

== Description ==

Custom Post Type Images makes it easy to attach an image to any custom post type, which you can then call as necessary in the archive page for that post type OR in the single itself, depending on how you use the plugin.  Think of it as a featured image for your post types, which you can use on overview or other pages.

== Installation ==

1. Upload `cptImages` directory to your  `/wp-content/plugins/` directory
2. Activate the Custom Post Type Images plugin through the 'Plugins' page in WordPress
3. Go to Tools > Manage Custom Post Type Images.
4. To use, select an image for the custom post type and attach. You can then use the image in various ways:

As a shortcode. Put [cptImage] wherever you want the image to appear. Not that in this usage (without a parameter) it will only appear when you are on a single item of that custom post type OR in the archive page itself. To call any attached image, use [cptImage cpt="Custom Post Type Name"].

In PHP in your template. To retrieve the image url to a variable, use get_cptimage_image($optionalname, $echo) (you can use get_cptimage_image("Custom Post Type Name") to retrieve a specific post type image, or get_cptimage_image("Custom Post Type Name", true) to echo the url rather than retrieving it. To merely display the image, call cptimage_image("Custom Post type Name") or simply cptimage_image(). Not that if you do not pass a post type name it will only appear when you are on a single item of that custom post type OR in the archive page itself. 

Please note, as with every plugin or theme modification, you should do a backup of your files beforehand.  Although we've tested this across many installs, we are not responsible for anything it does to your system and do not guarantee support.

== Frequently Asked Questions ==

= None so far =


== Screenshots ==

1. cptImages options page.

== Upgrade Notice ==

* 0.6

Added 'post' (basic WP type) as another image. You can now use it in categories, etc.

== Changelog ==

= 0.5 =
* Vou por ai a procurar rir pra não chorar.