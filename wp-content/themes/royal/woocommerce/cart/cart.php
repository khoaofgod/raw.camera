<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="table-responsive shop-table">
<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table table-bordered" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-name"><?php esc_html_e( 'Product', 'royal' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'royal' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'royal' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'royal' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn remove-item" title="%s">X</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), esc_html__( 'Remove this item', 'royal' ) ), $cart_item_key );
						?>
					</td>

					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'royal' ); ?>">
                        <div class="product-thumbnail">
                            <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                    if ( ! $_product->is_visible() )
                                            echo $thumbnail;
                                    else
                                            printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                            ?>
                        </div>
                            <div class="cart-item-details">
                                <?php
                                    if ( ! $_product->is_visible() ){
                                        echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                    } else {
                                        echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_name() ), $cart_item, $cart_item_key );
                                    }

                                    // Meta data
                                    echo WC()->cart->get_item_data( $cart_item );

	                        		// Backorder notification
	                        		if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
	                                echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'royal' ) . '</p>';
                                ?>
                                <div class="mobile-price">
                            		<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );?>
                           		</div>
                            </div>
                            
					</td>

					<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'royal' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'royal' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->get_max_purchase_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>


					<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'royal' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
</div>
<div class="actions">

	<input type="submit" class="btn gray big" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'royal' ); ?>" /> 

	<?php wp_nonce_field( 'woocommerce-cart' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart_table' ); ?>



</form>



<div class="row">
	<div class="col-md-6">
		<?php if ( WC()->cart->coupons_enabled() ) { ?>
			<h3 class="underlined"><?php esc_html_e( 'Have a coupon?', 'royal' ); ?></h3>
			<form class="checkout_coupon" method="post">
				<div class="coupon">

					<input type="text" name="coupon_code" class="input-text pull-left col-lg-8 col-md-7" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'royal' ); ?>" /> <input type="submit" class="btn pull-right big" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'royal' ); ?>" />

					<?php do_action('woocommerce_cart_coupon'); ?>

				</div>
			</form>
		<?php } ?>
		<div class="space3"></div>
	</div>

	<div class="col-md-6">
		<div class="row">
			<div class="col-xs-12">
				<div class="cart-collaterals">

					<?php do_action( 'woocommerce_cart_collaterals' ); ?>

				</div>
			</div>
		</div>
	</div>
</div>


<?php woocommerce_cross_sell_display(); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>