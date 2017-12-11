<?php
/**
 * Product loop sale flash
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;
?>
<div class="label-product">

    <?php if(etheme_product_is_new($post->id) && etheme_get_option('new_icon')) : ?>
		<div class="type-label-1">
			<div class="new"><?php esc_html_e( 'New', 'royal' ); ?></div>
		</div>
    <?php endif; ?>
	        
	<?php if ( $product->is_on_sale() && etheme_get_option('sale_icon')) : ?>
	
		<div class="type-label-2">
			<div class="sale"><?php esc_html_e('Sale', 'royal'); ?></div>
		</div>
	
	<?php endif; ?>
    <?php if ( !$product->is_in_stock() && etheme_get_option('out_of_icon')): ?>
		<div class="out-stock">
			<div class="wr-c">
				<div class="bigT"><?php esc_html_e('Out', 'royal') ?></div> <?php esc_html_e('of stock', 'royal') ?>
			</div>
		</div>  
	<?php endif; ?>

</div>