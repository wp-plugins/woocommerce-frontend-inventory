<?php

/*
Plugin Name: WooCommerce - Frontend Inventory
Plugin URI: 
Description: This plugin provides a template that can be applied to a page in order to show a full inventory of products in WooCommerce.
Version: 0.1
Author: Mirko Grewing
Author URI: http://www.mirkogrewing.eu
*/

function check_woo_notices() {
	if (!is_plugin_active('woocommerce/woocommerce.php')) {
        ob_start(); ?>
	<div class="error">
		<p><strong>WARNING</strong>: WooCommerce is not active and WooCommerce Front-End Inventory shortcode won't work!</p>
	</div>
	<?php
	echo ob_get_clean();
        }
}

add_action('admin_notices', 'check_woo_notices');

function get_inventory() {
	 $user = wp_get_current_user();
	if ( empty( $user->ID ) ) {
            echo 'Sorry you cannot access.';
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

?>