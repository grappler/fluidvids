=== Fluidvids for WordPress ===
Contributors: grapplerulrich
Donate link: https://github.com/grappler/fluidvids
Tags: fluidvids, responsive, youtube, vimeo, iframe, video
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Standalone JavaScript for fluid YouTube/Vimeo iframe embeds. Easily add additional video players and selectors in the settings.

== Description ==

Standalone JavaScript for fluid YouTube/Vimeo iframe embeds. Add more video players and selectors easily in the media settings.

Description of the Fluidvids script
"Fluidvids is a 1KB standalone module that provides a fluid solution for video embeds. Fluidvids has the ability for custom players to be added as well as support for dynamically injected (XHR/Ajax/createElement) videos.

Check out a [demo of fluidvids](http://toddmotto.com/labs/fluidvids)"

== Credits ==

The plugin can also be found on [GitHub](https://github.com/grappler/fluidvids).

* Thank you [Todd Motto](http://toddmot.to/) for [fluidvids.js](https://github.com/toddmotto/fluidvids)
* Thank you [Tom McFarlin](http://tommcfarlin.com/) for the [WordPress Plugin Boilerplate](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)

== Installation ==

= Installing from the WordPress dashboard =

1. Navigate to the 'Add New' plugins dashboard
2. Search for 'Fluidvids for WordPress'
3. Click 'Install Now'
4. Activate the plugin on the WordPress Plugin dashboard

= Uploading in the WordPress dashboard =

1. Navigate to the 'Add New' plugins dashboard
2. Navigate to the 'Upload' area
3. Select `fluidvids.zip` from your computer
4. Upload
5. Activate the plugin on the WordPress Plugin dashboard

= Using FTP =

1. Download `fluidvids.zip`
2. Extract the `fluidvids` directory to your computer
3. Upload the `fluidvids` directory to your `wp-content/plugins` directory
4. Activate the plugin on the WordPress Plugins dashboard

= Configuration =

You can add additional players in the media settings.

== Screenshots ==

1. Plugin Settings

== Changelog ==

= 1.4.0 - 25nd June 2014 =
* Update Fluidvids to 2.4.0
 - Fix bug for comparing width/height attrs `> 1000`

= 1.3.0 - 21st June 2014 =
* Update Fluidvids to 2.3.0
 - Add support for videos where `height > width`
 - Multiple `selector` support and CSS change to unrestrict element type
 - Add `npm` entry point
 - Change `apply()` to `render()` for better naming
 - Use while loop for and improved loop perf
* Add option to set selectors

= 1.2.0 - 1st March 2014 =
* Update Fluidvids to 2.2.0
 - XHR/Ajax content support via new apply() method to requery DOM
 - Add AMD support

= 1.1.0 - 16th December 2013 =
* Update Fluidvids to 2.1.0
 - Fix IE8 bug

= 1.0.0 - 6th December 2013 =
* Initial release

== Upgrade Notice ==

= 1.4.0 =
* Update Fluidvids to 2.4.0

= 1.3.0 =
* Update Fluidvids to 2.3.0

= 1.2.0 =
* Update Fluidvids to 2.2.0

= 1.1.0 =
* Update Fluidvids to 2.1.0

= 1.0.0 =
* This is the first release.