=== Time inc RST ===
Contributors: rtweedie
Tags: coding-test
Requires at least: 4.3
Tested up to: 4.5.3
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 A plugin to fulfill the coding test for TIme Inc - create a widget to load results from a JSON API and display via a widget

== Description ==

 A plugin to fulfill the coding test for TIme Inc - create a widget to load results from a JSON API and display via a widget.

 The plugin uses Guzzle to handle the URL requests and uses transients for the caching. The reaason for this is
 so that the widget contents will be cached even if the caching is not enabled the site.

Please note that we are using composer for bringin in the twig dependancies, so composer will need to be run
on the plugin directory.

== Installation ==

1. Download the plugin folder.
2. Run `composer install` from within the plugin directory.
3. Activate the plugin through the 'Plugins' menu in the WordPress administration system

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0.0 =
* The first version of the plugin