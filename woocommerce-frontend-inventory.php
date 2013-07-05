<?php

/*
Plugin Name: WooCommerce - Frontend Inventory
Plugin URI: http://www.mirkogrewing.eu/woocommerce-frontend-inventory/
Description: This plugin provides a shortcode that shows a full inventory from WooCommerce, only to registered users.
Version: 0.4.2
Author: Mirko Grewing
Author URI: http://www.mirkogrewing.eu

	Copyright: © 2013 Mirko Grewing (email : mirko@grewing.us)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

define('WOOFI_DIR', plugin_dir_path(__FILE__));
define('WOOFI_URL', plugin_dir_url(__FILE__));

function woofi_load(){
		
    if(is_admin()) //load admin files only in admin
        require_once(WOOFI_DIR.'includes/admin.php');
        
    require_once(WOOFI_DIR.'includes/core.php');
}
woofi_load();

?>