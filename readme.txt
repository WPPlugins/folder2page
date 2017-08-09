=== Plugin Name ===
Contributors: beakersoft
Plugin Name: Folder2Page
Author URI: http://www.beakersoft.co.uk
Plugin URI: http://www.beakersoft.co.uk/Folder2Page
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5735325
Tags: images,gallery,image gallery,
Requires at least: 2.0.0
Tested up to: 2.9
Stable tag: 1.0

Folder2Page allows you to use images in a folder on your webserver as an image gallery

== Description ==
There are quite a lot of plugins that allow you to create image galleries from Flickr, picasa etc but i've never seen one that will allow you to 
use an existing folder of images on your web server.

Once the plugin is installed, you have to give it the url and the path on your web server to the folder where you images are, as well as a couple 
of other options. When these are saved, simply add `<?php echo Display_Images(); ?>` to the place where you want the gallery to display.

Now, anything in that folder that is a jpg, png or gif will be displayed in your gallery. You can use the included style sheet to change the look and
feel of the table the images are displayed in, as well as setting other options via the dashboard that include width of image, images per line and 
if you want the thumbnail image to be clickable to show the original

What i'm looking at Implementing - 

* At the moment it only works on one folder of images, will make it work with as many as user needs 
* Add a paging option, ie show x number of images per page
* Extract IPTC data from the images, and show along with thumbnail. Use option page to decide what is shown

== Installation ==

1. Upload the wordslice folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php echo Display_Images(); ?>` in the page/post you want to show the gallery from
1. Open up the folder2page plugin options page from your dashboard, and set your options
1. Alter the included folder2page.css style sheet to change the look of the table the images are placed in

== Frequently Asked Questions ==

= How can I use more than one folder full of images? =

At the moment you cant. If there are enough people interested i will add this into the next version

== Changelog ==

= 0.1 =
* Initial Release

== Screenshots ==

1. Plugin Settings page