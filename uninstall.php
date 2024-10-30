<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Let_It_Bill
 * @author    Valentin Carage <valentin.carage@let-it-bill.com>
 * @license   GPL-2.0+
 * @link      https://www.let-it-bill.com
 * @copyright 2015 Ideys SARL
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
