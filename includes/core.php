<?php
add_action('plugins_loaded', 'woocommerce_frontend_inventory_init', 0);

function woocommerce_frontend_inventory_init(){
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		if ( ! class_exists( 'WC_WooFI' ) ) {
			class WC_WooFI {
				public function __construct() {
					// called only after woocommerce has finished loading
					add_action( 'woocommerce_init', array( &$this, 'woocommerce_loaded' ) );
				}
				
				/**
				 * Take care of anything that needs woocommerce to be loaded.  
				 * For instance, if you need access to the $woocommerce global
				 */
				public function woocommerce_loaded() {
					function get_inventory() {
						$options = get_option('woofi_options');
						$out = (!isset($options['errormessage_template']) || $options['errormessage_template']=="") ? 'Sorry you cannot access here!' : $options['errormessage_template'];
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
				
				/**
				 * Take care of anything that needs all plugins to be loaded
				 */
				//public function plugins_loaded() {
					// ...
				//}
				
			}
		
		// finally instantiate our plugin class and add it to the set of globals
		$GLOBALS['wc_woofi'] = new WC_WooFI();
		
		}
	}
	else {
		function check_woo_notices() {
			if (!is_plugin_active('woocommerce/woocommerce.php')) {
				ob_start(); ?>
			<div class="error">
				<p><strong>WARNING</strong>: WooCommerce is not active and WooCommerce Front-End Inventory shortcode will not work!</p>
			</div>
			<?php
			echo ob_get_clean();
				}
		}
		add_action('admin_notices', 'check_woo_notices');
	}
}
?>