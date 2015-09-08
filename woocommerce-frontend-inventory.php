<?php
/**
    Plugin Name: WooCommerce - Frontend Inventory
    Plugin URI: http://www.mirkogrewing.eu/woocommerce-frontend-inventory/
    Description: This plugin provides a template that can be applied to a page in order to show a full inventory of products in WooCommerce.
    Version: 0.7
    Author: Mirko Grewing
    Author URI: http://www.mirkogrewing.it

	Copyright: © 2013 Mirko Grewing (email : mirko@grewing.co.uk)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    load_plugin_textdomain('woocommerce-frontend-inventory', false, dirname(plugin_basename(__FILE__)) . '/languages/');	
    if (!class_exists('WC_WooFI')) {
        /**
         * Class to print out the inventory
         *
         * @category Plugin
         * @package  Class
         * @author   Mirko Grewing <mirko@grewing.co.uk>
         * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
         * @version  0.7
         * @link     http://www.mirkogrewing.it
         */
        class WC_WooFI
        {
            /**
                Public constructor function
             */
            public function __construct()
            {
                $this->woofi_enabled = get_option('woofi_enable') == 'yes' ? true : false;
                add_action('init', array($this, 'pluginInit'));
                $this->settings = array(
                    array(
                        'name'  => __('Frontend Inventory', 'woocommerce-frontend-inventory'),
                        'type'  => 'title',
                        'id'    => 'woofi_options'
                    ),
                    array(
                        'name'  => __('Frontend inventory', 'woocommerce-frontend-inventory'),
                        'desc'  => __('Enable frontend inventory', 'woocommerce-frontend-inventory'),
                        'id'    => 'woofi_enable',
                        'type'  => 'checkbox'
                    ),
                    array(
                        'name'     => __('Error Message', 'woocommerce-frontend-inventory'),
                        'desc_tip' => __('Enter the error message you want to show to any unauthorized visitors that should try opening the inventory page.', 'woocommerce-frontend-inventory'),
                        'id'       => 'woofi_error_message',
                        'type'     => 'textarea',
                        'css'      => 'min-width:400px;',
                        'desc'     => __('Enter your error message', 'woocommerce-frontend-inventory'),
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'woofi_options'
                    ),
                );
                
                add_option('woofi_enable', 'yes');
                add_option('woofi_error_message', __('Sorry you cannot access here!', 'woocommerce-frontend-inventory'));
                
                add_action('woocommerce_settings_image_options_after', array($this, 'adminSettings'), 20);
                add_action('woocommerce_update_options_inventory', array($this, 'saveAdminSettings'));
            }
            /**
             * Initialize the plugin
             * 
             * @return null
             */
            function pluginInit()
            {
                if ($this->woofi_enabled) {
                    /**
                     * Get the values
                     *
                     * @param array $atts List of attributes
                     *
                     * @return array
                     */
                    function getInventory($atts)
                    {
                        extract(
                            shortcode_atts(
                                array(
                                    'orderby'	=> 'title',
                                    'sort'		=> 'ASC',
                                    'user_id'	=> '',
                                ), $atts
                            )
                        );
                        $out = get_option('woofi_error_message', __('Sorry you cannot access here', 'woocommerce-frontend-inventory'));
                        $user = wp_get_current_user();
                        if (empty($user->ID)) {
                            echo $out;
                        } else {
                            global $woocommerce;
                            ?><style>
                                  #reviews {display:none}
                            </style>
                            <table width="100%" style="border: 1px solid #000; width: 100%;" cellspacing="0" cellpadding="2">
                                <thead>
                                <tr>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Product', 'woocommerce-frontend-inventory'); ?></th>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('SKU', 'woocommerce-frontend-inventory'); ?></th>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Stock', 'woocommerce-frontend-inventory'); ?></th>
                                </tr>
                                </thead>
                                <tbody><?php
                                $args = array(
                                    'post_type'         => 'product',
                                    'post_status'       => 'publish',
                                    'posts_per_page'    => -1,
                                    'orderby'           => $orderby,
                                    'order'             => $sort,
                                    'author'			=> $user_id,
                                    'meta_query'        => array(
                                                            array(
                                                                'key'   => '_manage_stock',
                                                                'value' => 'yes'
                                                            )
                                                           ),
                                    'tax_query'         => array(
                                                            array(
                                                                'taxonomy'  => 'product_type',
                                                                'field'     => 'slug',
                                                                'terms'     => array('simple'),
                                                                'operator'  => 'IN'
                                                            )
                                                           )
                                );
                                $loop = new WP_Query($args);
                            while ($loop->have_posts()) : $loop->the_post();
                                global $product;
                                ?><tr>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->get_title(); ?></td>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->sku; ?></td>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->stock; ?></td>
                                </tr><?php
                            endwhile;
                            ?></tbody>
                            </table>
                            <h2><?php _e('Variations', 'woocommerce-frontend-inventory'); ?></h2>
                            <table width="100%" style="border: 1px solid #000; width: 100%;" cellspacing="0" cellpadding="2">
                                <thead>
                                <tr>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;""><?php _e('Variation', 'woocommerce-frontend-inventory'); ?></th>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Parent', 'woocommerce-frontend-inventory'); ?></th>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('SKU', 'woocommerce-frontend-inventory'); ?></th>
                                    <th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Stock', 'woocommerce-frontend-inventory'); ?></th>
                                </tr>
                                </thead>
                                <tbody><?php
                            $args = array(
                                'post_type'         => 'product_variation',
                                'post_status'       => 'publish',
                                'posts_per_page'    => -1,
                                'orderby'           => 'title',
                                'order'             => 'ASC',
                                'meta_query'        => array(
                                    array(
                                        'key'   => '_stock',
                                        'value' => array('', false, null),
                                        'compare' => 'NOT IN'
                                    )
                                )
                            );
                            $loop = new WP_Query($args);
                            while ($loop->have_posts()) : $loop->the_post();
                                $product = new WC_Product_Variation($loop->post->ID);
                                ?><tr>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->get_title(); ?></td>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo get_the_title($loop->post->post_parent); ?></td>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->sku; ?></td>
                                    <td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->stock; ?></td>
                                </tr><?php
                            endwhile;
                            ?></tbody>
                            </table><?php
                        }
                    }
                    add_shortcode('woofi', 'getInventory');
                }
            }
            /**
             * Recall and handle settings
             *
             * @return array
             */
            function adminSettings()
            {
                woocommerce_admin_fields($this->settings);
            }
            /**
             * Save the settings
             *
             * @return null
             */
            function saveAdminSettings()
            {
                woocommerce_update_options($this->settings);
            }
            /**
             * Add direct link to the settings page from the plugin list
             * 
             * @param Link $links Link to the settings page
             *
             * @return string
             */
            public function addSettingsLink($links)
            {
                $settings = sprintf('<a href="%s" title="%s">%s</a>', admin_url('admin.php?page=woocommerce&tab=' . $this->settings->tab_name), __('Go to the settings page', 'woocommerce-frontend-inventory'), __('Settings', 'woocommerce-frontend-inventory'));
                array_unshift($links, $settings);
                return $links;
            }
        }

        $GLOBALS['wc_woofi'] = new WC_WooFI();
    }
} else {
    /**
     * Check if WooCommerce is up and running
     *
     * @return null
     */
    function checkWooNotices()
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            ob_start();
            ?><div class="error">
                <p><strong><?php _e('WARNING', 'woocommerce-frontend-inventory'); ?></strong>: <?php _e('WooCommerce is not active and WooCommerce Front-End Inventory shortcode will not work!', 'woocommerce-frontend-inventory'); ?></p>
            </div><?php
            echo ob_get_clean();
        }
    }
    add_action('admin_notices', 'checkWooNotices');
}
?>