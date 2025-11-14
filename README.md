# WooCommerce Limit Order Amount

A simple WooCommerce plugin to limit the maximum order amount to R 10,000 on the cart and checkout pages. It disables the "Proceed to checkout" button on the cart page and the payment section plus "Place order" button on the checkout page if the order total exceeds the limit. It also includes server-side validation to prevent orders above the limit.

## Features

- Disables the "Proceed to checkout" button on the cart page if the cart total exceeds R 10,000.
- Disables the payment methods section and "Place order" button on the checkout page if the order total exceeds R 10,000.
- Shows clear warning messages to users when limits are exceeded.
- Server-side validation to block checkout if the order total is above the limit.
- Works dynamically with WooCommerce AJAX updates on cart and checkout pages.

## Installation

1. Download or clone this repository.
2. Upload the `woocommerce-limit-order-amount.php` file to your WordPress `/wp-content/plugins/` directory.
3. Activate the plugin through the WordPress admin dashboard under **Plugins**.
4. The plugin will start enforcing the order amount limit immediately.

## Usage

- The maximum order amount is set to R 10,000 by default.
- To change the limit, edit the `$max_order_amount` property in the plugin file.

## Compatibility

- Requires WooCommerce plugin installed and activated.
- Tested with WooCommerce 7.x and WordPress 6.x.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/Berserk3rza/Woocommerce-disable-checkout-on-specific-total/).

## Author

Stephan Lombard

---

*This plugin is provided as-is without warranty. Use at your own risk.*
