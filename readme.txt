=== WooCommerce Frontend Inventory ===
Contributors: rashef
Donate link: http://cl.ly/0m1h2L3u0K3Y
Tags: WooCommerce, inventory, stock, variations
Requires at least: 3.3
Tested up to: 3.5.2
Stable tag: 0.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to publish a full inventory on a frontend page with a simple shortcode. Every registerd user will be able to view it, while unregistered users will get a custom error message.

== Description ==

What is WooCommerce Frontend Inventory for?

Do you want to provide some users with a **full inventory** of your WooCommerce installation without giving them access to the WooCommerce configuration area in the Dashboard?  

This plugin allows you to publish a full inventory on a frontend page with a simple shortcode. Every registerd user will be able to view it, while unregistered users will get a custom error message.  

**Please note** this is an early stage version of the plugin and it is very limited at the moment. It just prints the inventory in a page inside the template you are using (it might actually conflict with it for a couple of styles I hard-coded into the file).   

Do you want to raise a bug, propose a change, request for support or simply congratulate me? The Official Page of the plugin is waiting for your voice!  

http://www.mirkogrewing.eu/woocommerce-frontend-inventory/   
http://support.mirkogrewing.eu/  

== Installation ==

1. Upload the plugin's folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[woofi]` in any page to show the inventory.

== Screenshots ==

1. The inventory on a frontpage.

== Frequently asked questions ==

= Is there anything I can configure? =

Just the error message. But hey... that's something! :P

= It messed up with my template, what I can do? =

You are unlucky. The plugin uses a simple table and a couple of minimal styles. Unless you edit the styles directly in the plugin, you may want to wait for further releases: customizable styles are on the roadmap...

= Hey, most of the visitors get an error message! =

Only registered users can see that page. You can modify the error message in the settings.

= I get only the header of the table but no product, why is that? =

Please notice that only product with limited stock units will be listed. If you don't get any product in the least, check whether there is at least one product with quantity set.

== Changelog ==

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

Nothing you should worry about! ;)

