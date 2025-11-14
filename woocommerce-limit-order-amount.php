<?php
/**
 * Plugin Name: WooCommerce Limit Order Amount
 * Description: Limits the maximum order amount to R 10,000 on cart and checkout pages by disabling checkout buttons and payment section.
 * Version: 1.0
 * Author: Stephan Lombard
 * Text Domain: wc-limit-order-amount
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WC_Limit_Order_Amount {

    private $max_order_amount = 10000; // Maximum order amount in Rands

    public function __construct() {
        add_action( 'wp_footer', array( $this, 'enqueue_limit_script' ) );
        add_action( 'woocommerce_checkout_process', array( $this, 'server_side_limit_check' ) );
    }

    /**
     * Enqueue inline script to disable buttons and payment section on cart and checkout pages.
     */
    public function enqueue_limit_script() {
        if ( ! is_cart() && ! is_checkout() ) {
            return;
        }

        $max_amount = $this->max_order_amount;
        ?>
        <script type="text/javascript">
        (function($) {
            function formatNumber(text) {
                return parseFloat(text.replace(/[^\d.,]/g, '').replace(',', '.')) || 0;
            }

            function checkCartTotal() {
                var totalText = $('.order-total .woocommerce-Price-amount').first().text() || '';
                var totalNumber = formatNumber(totalText);
                var proceedButton = $('.wc-proceed-to-checkout a.checkout-button').first();
                var warning = $('#cart-total-warning');

                if (!proceedButton.length) return;

                if (totalNumber > <?php echo $max_amount; ?>) {
                    proceedButton.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    }).attr('title', 'Order total cannot exceed R <?php echo number_format($max_amount, 2); ?>');

                    if (!warning.length) {
                        warning = $('<p id="cart-total-warning" style="color:red; font-weight:bold; margin-bottom:10px;"></p>').text(
                            'Your cart total exceeds the maximum allowed amount of R <?php echo number_format($max_amount, 2); ?>. Please reduce your cart before proceeding to checkout.'
                        );
                        proceedButton.parent().prepend(warning);
                    }
                } else {
                    proceedButton.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    }).removeAttr('title');

                    if (warning.length) {
                        warning.remove();
                    }
                }
            }

            function checkCheckoutTotal() {
                var totalText = $('.order-total .woocommerce-Price-amount').first().text() || '';
                var totalNumber = formatNumber(totalText);
                var paymentSection = $('#payment');
                var placeOrderButton = $('#place_order');
                var warning = $('#checkout-total-warning');

                if (!paymentSection.length || !placeOrderButton.length) return;

                if (totalNumber > <?php echo $max_amount; ?>) {
                    paymentSection.css({
                        'pointer-events': 'none',
                        'opacity': '0.5'
                    });
                    placeOrderButton.prop('disabled', true).css({
                        'opacity': '0.5'
                    }).attr('title', 'Order total cannot exceed R <?php echo number_format($max_amount, 2); ?>');

                    if (!warning.length) {
                        warning = $('<p id="checkout-total-warning" style="color:red; font-weight:bold; margin-bottom:20px;"></p>').text(
                            'Your order total exceeds the maximum allowed amount of R <?php echo number_format($max_amount, 2); ?>. Please reduce your cart before placing the order.'
                        );
                        paymentSection.before(warning);
                    }
                } else {
                    paymentSection.css({
                        'pointer-events': 'auto',
                        'opacity': '1'
                    });
                    placeOrderButton.prop('disabled', false).css({
                        'opacity': '1'
                    }).removeAttr('title');

                    if (warning.length) {
                        warning.remove();
                    }
                }
            }

            $(document).ready(function() {
                if ($('body').hasClass('woocommerce-cart')) {
                    checkCartTotal();
                    $(document.body).on('updated_cart_totals', checkCartTotal);
                }

                if ($('body').hasClass('woocommerce-checkout')) {
                    checkCheckoutTotal();
                    $(document.body).on('updated_checkout', checkCheckoutTotal);
                }
            });
        })(jQuery);
        </script>
        <?php
    }

    /**
     * Server-side validation to prevent checkout if order total exceeds max amount.
     */
    public function server_side_limit_check() {
        $max_amount = $this->max_order_amount;
        $cart_total = WC()->cart->get_total('edit');

        if ( floatval($cart_total) > $max_amount ) {
            wc_add_notice( sprintf(
                /* translators: %s: max order amount */
                __('Your order total cannot exceed R %s. Please reduce your cart amount.', 'wc-limit-order-amount'),
                number_format($max_amount, 2)
            ), 'error' );
        }
    }
}

new WC_Limit_Order_Amount();
