<?php
/**
 * Manage WooCommerce - LET IT BILL connexion
 *
 * @package   Let_It_Bill
 * @author    Valentin Carage <valentin.carage@let-it-bill.com>
 * @license   GPL-2.0+
 * @link      https://www.let-it-bill.com
 * @copyright 2015 Ideys SARL
 */

class Let_It_Bill {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @var     string
	 */
	const VERSION = '0.1';

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'let-it-bill';

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'load_plugin_text_domain' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 *@return    string		The plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_text_domain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	}

}
