<?php

/*
Plugin Name: WooCommerce - Frontend Inventory
Plugin URI: http://www.mirkogrewing.eu/woocommerce-frontend-inventory/
Description: This plugin provides a template that can be applied to a page in order to show a full inventory of products in WooCommerce.
Version: 0.5
Author: Mirko Grewing
Author URI: http://www.mirkogrewing.eu

	Copyright: © 2013 Mirko Grewing (email : mirko@grewing.us)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	// Localization
	load_plugin_textdomain( 'woocommerce-frontend-inventory', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	// Class
	if ( ! class_exists( 'WC_WooFI' ) ) {
		class WC_WooFI {
			public function __construct() {
				
				$this->woofi_enabled = get_option( 'woofi_enable' ) == 'yes' ? true : false;
				
				// called only after woocommerce has finished loading
				add_action( 'init', array( $this, 'plugin_init' ) );
				
				// Init settings
				$this->settings = array(
					array(
						'name' => __( 'Frontend Inventory', 'woocommerce-frontend-inventory' ),
						'type' => 'title',
						'id' => 'woofi_options'
					),
					array(
						'name' => __( 'Frontend inventory', 'woocommerce-frontend-inventory' ),
						'desc' => __( 'Enable frontend inventory', 'woocommerce-frontend-inventory' ),
						'id' => 'woofi_enable',
						'type' => 'checkbox'
					),
					array(
						'name'     => __( 'Error Message', 'woocommerce-frontend-inventory' ),
						'desc_tip' => __( 'Enter the error message you want to show to any unauthorized visitors that should try opening the inventory page.', 'woocommerce-frontend-inventory' ),
						'id'       => 'woofi_error_message',
						'type'     => 'textarea',
						'css'      => 'min-width:400px;',
						'desc'     => __( 'Enter your error message', 'woocommerce-frontend-inventory' ),
					),
					array(
						'type' => 'sectionend',
						'id' => 'woofi_options'
					),
				);
				
				// Default options
				add_option( 'woofi_enable', 'yes' );
				add_option( 'woofi_error_message', 'Sorry you cannot access here!' );
				
				
				// Admin
				add_action( 'woocommerce_settings_image_options_after', array( $this, 'admin_settings' ), 20);
				add_action( 'woocommerce_update_options_inventory', array( $this, 'save_admin_settings' ) );
				
			}
			
			function plugin_init() {
				if ( $this->woofi_enabled ) {
					function get_inventory() {
						//$options = get_option('woofi_options');
						//$out = (!isset($options['errormessage_template']) || $options['errormessage_template']=="") ? 'Sorry you cannot access here!' : $options['errormessage_template'];
						$out = get_option('woofi_error_message', 'Sorry you cannot access here' );
						$user = wp_get_current_user();
						if ( empty( $user->ID ) ) {
								echo $out;
						}
						else {
							global $woocommerce;
							?>
							<style>
								#reviews {display:none}
							</style>
							<table width="100%" style="border: 1px solid #000; width: 100%;" cellspacing="0" cellpadding="2">
								<thead>
									<tr>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Product', 'woothemes'); ?></th>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('SKU', 'woothemes'); ?></th>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Stock', 'woothemes'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$args = array(
									'post_type'         => 'product',
									'post_status'       => 'publish',
									'posts_per_page'    => -1,
									'orderby'           => 'title',
									'order'             => 'ASC',
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
									$loop = new WP_Query( $args );
									while ( $loop->have_posts() ) : $loop->the_post();
									global $product;
									?>
										<tr>
											<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->get_title(); ?></td>
											<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->sku; ?></td>
											<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->stock; ?></td>
										</tr>
									<?php
									endwhile;
									?>
								</tbody>
							</table>
							<h2>Variations</h2>
							<table width="100%" style="border: 1px solid #000; width: 100%;" cellspacing="0" cellpadding="2">
								<thead>
									<tr>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;""><?php _e('Variation', 'woothemes'); ?></th>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Parent', 'woothemes'); ?></th>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('SKU', 'woothemes'); ?></th>
										<th scope="col" style="text-align:left; border: 1px solid #000; padding: 6px;"><?php _e('Stock', 'woothemes'); ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
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
								$loop = new WP_Query( $args );
								while ( $loop->have_posts() ) : $loop->the_post();
									$product = new WC_Product_Variation( $loop->post->ID );
								?>
									<tr>
										<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->get_title(); ?></td>
										<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo get_the_title( $loop->post->post_parent ); ?></td>
										<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->sku; ?></td>
										<td style="text-align:left; border: 1px solid #000; padding: 6px;"><?php echo $product->stock; ?></td>
									</tr>
								<?php
								endwhile;
								?>
								</tbody>
							</table>
					<?php	
						   }
					}
					add_shortcode( 'woofi', 'get_inventory' );
				}
			}
			
			// Load the settings
			function admin_settings() {
				woocommerce_admin_fields( $this->settings );
			}

			// Save the settings
			function save_admin_settings() {
				woocommerce_update_options( $this->settings );
			}
			
			//Add support links to plugin page
			public function add_support_links( $links, $file ) {
				if ( !current_user_can( 'install_plugins' ) ) {
					return $links;
				}
				if ( $file == WC_WooFI::$plugin_basefile ) {
					$links[] = '<a href="http://www.mirkogrewing.eu/woocommerce-frontend-inventory/" target="_blank" title="' . __( 'Homepage', 'woocommerce-frontend-inventory' ) . '">' . __( 'Homepage', 'woocommerce-frontend-inventory' ) . '</a>';
					//$links[] = '<a href="http://wordpress.org/support/plugin/woocommerce-delivery-notes" target="_blank" title="' . __( 'Support', 'woocommerce-delivery-notes' ) . '">' . __( 'Support', 'woocommerce-delivery-notes' ) . '</a>';
				}
				return $links;
			}
			
			//Add settings link to plugin page
			public function add_settings_link( $links ) {
				$settings = sprintf( '<a href="%s" title="%s">%s</a>' , admin_url( 'admin.php?page=woocommerce&tab=' . $this->settings->tab_name ) , __( 'Go to the settings page', 'woocommerce-delivery-notes' ) , __( 'Settings', 'woocommerce-delivery-notes' ) );
				array_unshift( $links, $settings );
				return $links;	
			}
			
		}
		// Instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_woofi'] = new WC_WooFI();
	}
	
} else {
	function check_woo_notices() {
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			ob_start();
			?><div class="error">
			<p><strong>WARNING</strong>: WooCommerce is not active and WooCommerce Front-End Inventory shortcode will not work!</p>
			</div><?php
			echo ob_get_clean();
		}
	}
	add_action('admin_notices', 'check_woo_notices');
}

?>