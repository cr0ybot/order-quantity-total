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
if ( ! in_array(
	'woocommerce/woocommerce.php',
	apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
	true
) ) {
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

/**
 * Add order total item count to new order admin email.
 *
 * @param array    $total_rows Data for totals.
 * @param WC_Order $order Order object.
 * @param string   $tax_display Tax string.
 */
function oqt_get_order_item_totals( $total_rows, $order, $tax_display ) {
	$oqt_row    = array(
		'oqt' => array(
			'label' => __( 'Order Quantity Total:', 'order-quantity-total' ),
			'value' => strval( $order->get_item_count() ),
		),
	);
	$total_rows = array_merge( $oqt_row, $total_rows );

	return $total_rows;
}

/**
 * Include order total item count in admin email only.
 *
 * @param WC_Order $order Order object.
 * @param boolean  $sent_to_admin Whether the email is for admin.
 * @param string   $plain_text Email plain text.
 * @param string   $email Email HTML.
 */
function oqt_email_before_order_table( $order, $sent_to_admin, $plain_text, $email ) {
	if ( $sent_to_admin ) {
		add_filter( 'woocommerce_get_order_item_totals', 'oqt_get_order_item_totals', 99, 3 );
	}
}
add_action( 'woocommerce_email_before_order_table', 'oqt_email_before_order_table', 10, 4 );
