<?php
/**
 * Order Quantity Total
 *
 * @package           order-quantity-total
 * @author            Cory Hughart
 * @copyright         2022 Cory Hughart
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: Order Quantity Total for WooCommerce
 * Plugin URI: https://github.com/cr0ybot/order-quantity-total
 * Description: Display the total quantity on the order page.
 * Version: 0.1.0
 * Requires PHP: 5.0
 * Author: Cory Hughart
 * Author URI: https://coryhughart.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: order-quantity-total
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exit if WooCommerce is not active.
 */
if (
	! in_array(
		'woocommerce/woocommerce.php',
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
		true
	)
) {
	exit;
}

/**
 * Add order total item count to order details page.
 *
 * @param int $order_id Order ID.
 */
function oqt_admin_order_items_after_line_items( $order_id ) {
	$order = wc_get_order( $order_id );
	$count = $order->get_item_count();
	?>
<tr class="item oqt-line-item">
	<td class="thumb"></td>
	<td class="name" data-sort-value="zzzzzz" style="text-align: right;">
		<span class="oqt-line-item__label"><?php esc_html_e( 'Order Quantity Total:', 'order-quantity-total' ); ?></span>
	</td>
	<td class="item_cost" width="1%"  data-sort-value="zzzzzz"></td>
	<td class="quantity" width="1%" style="text-align: right;"  data-sort-value="zzzzzz"><b><?php echo esc_html( $count ); ?></b></td>
	<td class="line_cost" width="1%"  data-sort-value="zzzzzz"></td>
	<?php if ( wc_tax_enabled() ) : ?>
	<td class="line_tax" width="1%"  data-sort-value="zzzzzz"></td>
	<?php endif; ?>
	<td class="wc-order-edit-line-item" width="1%"></td>
</tr>
	<?php
}
add_action( 'woocommerce_admin_order_items_after_line_items', 'oqt_admin_order_items_after_line_items' );
