<?php
/**
 *
 * @package   Let_It_Bill
 * @author    Valentin Carage <valentin.carage@let-it-bill.com>
 * @license   GPL-2.0+
 * @link      https://www.let-it-bill.com
 * @copyright 2015 Ideys SARL
 *
 * @wordpress-plugin
 * Plugin Name:       LET IT BILL for WooCommerce
 * Plugin URI:        https://www.let-it-bill.com
 * Description:       A plugin to synchronize WooCommerce data with a LET IT BILL account.
 * Version:           0.3
 * Author:            LET IT BILL
 * Author URI:        https://www.let-it-bill.com
 * Text Domain:       en_US
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/ideys/let-it-bill-wp-plugin
 * Depends:           WooCommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-let-it-bill.php' );

add_action( 'plugins_loaded', array( 'Let_It_Bill', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-let-it-bill-woo-commerce.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-let-it-bill-admin.php' );
	add_action( 'plugins_loaded', array( 'Let_It_Bill_Admin', 'get_instance' ) );

}
