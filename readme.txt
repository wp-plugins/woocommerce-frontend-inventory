=== WooCommerce Frontend Inventory ===
Contributors: rashef
Donate link: http://cl.ly/2C2W181j1G2g
Tags: WooCommerce, inventory, stock, variations
Requires at least: 3.3
Tested up to: 4.3
Stable tag: 0.7
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows you to publish an inventory of your in-stock WooCommerce products on a frontend page with a simple shortcode.

== Description ==

What is WooCommerce Frontend Inventory for?

Do you want to provide some users with an inventory of your WooCommerce installation without giving them access to the WooCommerce configuration area in the Dashboard? 

Place the shortcode, with the needed parameters where you want the inventory to appear. Syntax is:
`[woofi orderby={title|sku|stock} sort={ASC|DESC} user_id={}]`

For example:

`[woofi]` will print the inventory, with the items ordered by title.
`[woofi orderby=sku]` will print the inventory ordered by SKU.
`[woofi orderby=sku sort=DESC]` will print the inventory sorted by SKU in reverse order.
`[woofi user_id=27]` will print the inventory including only products created by the user with id 27.
This latest feature is also exclusive:
`[woofi user_id=-33]` will print the inventory of all the products but the ones created by the user with id 33.
Be aware that **every registered user** will be able to view the inventory, regardless for the role, while unregistered users will get an error message, that you can customise in the dashboard.

Also note that the current version will not print any product marked in stock but with **UNDEFINED** quantity. A future version will consider this case.

Special thanks to [Mike Jolley](http://profiles.wordpress.org/mikejolley/) who provided the [initial script](https://t.co/CtLxf1XCVN).

== Installation ==

1. Upload the plugin's folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the shortcode in any page to show the inventory. 

== Screenshots ==

1. The inventory on a frontpage.
2. Custom error message in settings.

== Frequently asked questions ==

= Many products are not listed, why? =
Please check that you are managing a stock for them and you defined a quantity.

== Changelog ==

= v0.7 = 
* Added a new parameter to filter by user ID

= v0.6.1 = 
* Cleaning of the code to ensure best support for Wordpress 4.
* Added localization support.
* Added Italian localization.
* Added Spanish localization.

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

Great news! You can now filter by user ID - or exclude an user form the list.