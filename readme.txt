=== WooCommerce Frontend Inventory ===
Contributors: rashef
Donate link: http://cl.ly/0m1h2L3u0K3Y
Tags: WooCommerce, inventory, stock, variations
Requires at least: 3.3
Tested up to: 3.8.1
Stable tag: 0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to publish a full inventory on a frontend page with a simple shortcode. Unregistered users will get a custom error message.

== Description ==

What is WooCommerce Frontend Inventory for?

Do you want to provide some users with a **full inventory** of your WooCommerce installation without giving them access to the WooCommerce configuration area in the Dashboard? 

Place the shortcode, with the needed parameters where you want the inventory to appear. Syntax is:
`[woofi orderby={title|sku|stock} sort={ASC|DESC}]`

For example:

`[woofi]` will print the inventory, with the items ordered by title.
`[woofi orderby=sku]` will print the inventory ordered by SKU.
`[woofi orderby=sku sort=DESC]` will print the inventory sorteb by SKU in reverse order.

This plugin allows you to publish a full inventory on a frontend page with a simple shortcode. **Every registerd user** will be able to view it, while unregistered users will get an error message.

Special thanks to [Mike Jolley](http://profiles.wordpress.org/mikejolley/) who provided the [initial script](https://t.co/CtLxf1XCVN).

** Please Note ** 
This plugin is still a beta version and quite far from being completed. Even if I am doing my best to ensure it is working properly and to test it on different environments you might still find bugs.

Do you want to raise a bug, propose a change, request for support or simply say hi? The Official Page of the plugin is waiting for your voice!  

http://www.mirkogrewing.eu/woocommerce-frontend-inventory/   

== Installation ==

1. Upload the plugin's folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the shortcode in any page to show the inventory. 

== Screenshots ==

1. The inventory on a frontpage.
2. Custom error message in settings.

== Frequently asked questions ==

= Where I can find the FAQ? =
[Here!](http://support.mirkogrewing.it/kb/index.php "FAQ")

== Changelog ==

= v0.6 = 
* The shortcode now support parameters to sort items.

= v0.5 =
* Settings moved inside WooCommerce Settings > Catalog.
* Code optimization.

= v0.4.2 =
* Added links to support and official webpage.

= v0.4.1 =
* Minor bugfixes

= v0.4 =
* The plugin is now organized in a structured folder.
* New settings area.
* Customizable error message with HTML support.

= v0.3 =
* Minor fixes.

= v0.2 =
* Typos corrected.

= v0.1 =
* First release.

== Upgrade notice ==

Settings have been moved inside WooCommerce Settings, Catalog tab.