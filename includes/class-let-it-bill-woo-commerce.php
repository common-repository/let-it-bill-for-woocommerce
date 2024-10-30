<?php
/**
 * LET IT BILL WooCommerce Synchronization class
 *
 * @package   Let_It_Bill
 * @author    Valentin Carage <valentin.carage@let-it-bill.com>
 * @license   GPL-2.0+
 * @link      https://www.let-it-bill.com
 * @copyright 2015 Ideys SARL
 */

class Let_It_Bill_Woo_Commerce {

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * WooCommerce defined APIs.
	 *
	 * @var      array
	 */
	private $wcAPIs = [];

	/**
	 * LET IT BILL returned API informations.
	 *
	 * @var      array
	 */
	private $letItBillApiData = [];

	const WOO_COMMERCE_WEBSITE = 'http://www.woothemes.com/woocommerce/';

	/**
	 * WooCommerce API states.
	 */
	const WOO_COMMERCE_API_ACTIVE = 'wc.api.active';
	const WOO_COMMERCE_API_NOT_CONNECTED = 'wc.api.not.connected';
	const WOO_COMMERCE_API_NOT_DEFINED = 'wc.api.not.defined';
	const WOO_COMMERCE_PLUGIN_DISABLED = 'wc.plugin.disabled';

	/**
	 * Return an instance of this class.
	 *
	 * @return    Let_It_Bill_Woo_Commerce    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Return WooCommerce API state.
	 *
	 * @return string
	 */
	public function check_woo_commerce_api() {

		// WooCommerce plugin need to be installed and enabled
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			return self::WOO_COMMERCE_PLUGIN_DISABLED;
		}

		// WooCommerce plugin need to have an API endpoint defined for LET IT BILL
		$apis = $this->list_apis();
		if (empty($apis)) {
			return self::WOO_COMMERCE_API_NOT_DEFINED;
		}

		// The API key must be defined into LET IT BILL SaaS
		$apiConnected = $this->check_api_connexion();
		if (!$apiConnected) {
			return self::WOO_COMMERCE_API_NOT_CONNECTED;
		}

		// OK
		return self::WOO_COMMERCE_API_ACTIVE;
	}

	/**
	 * Return LET IT BILL current website related API informations.
	 *
	 * @return array|false Return false if data retrieving fail.
	 */
	private function get_let_it_bill_api_settings_info()
	{
		if (!empty($this->letItBillApiData)) {
			return $this->letItBillApiData;
		}

		$wpUrl = get_site_url();
        $baseUrl = 'https://let-it-bill.com';
       	$serviceApiUrl = $baseUrl . '/en/v/open-data/api/woo.commerce/connexion-info';
		$serviceApiUrl .= '?' . http_build_query(['platform' => 'WooCommerce', 'website' => $wpUrl]);

		$ch = curl_init($serviceApiUrl);

		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$content = curl_exec($ch);

		curl_close($ch);

		if (!$content) {
			return false;
		}

		$data = json_decode($content);

		if (!$data) {
			return false;
		}

		return $this->letItBillApiData = $data;
	}

    /**
     * Return the last
     *
     * @return bool|DateTime Return false if infos could not be retrieved.
     */
    public function get_last_api_connexion()
    {
        $infos = $this->get_let_it_bill_api_settings_info();

        if (!$infos) {
            return false;
        }

        if (isset($infos->last_sync))
            return new \DateTime($infos->last_sync);
        return new \DateTime();
    }

	/**
	 * Is woo commerce has an API key defined?
	 *
	 * @return array|false Return false if data retrieving fail.
	 */
	public function check_api_connexion()
	{
		$infos = $this->get_let_it_bill_api_settings_info();

		if (!$infos)
		    return false;
		else if ($infos->found == false) {
			return false;
		}

		return true;
	}

	/**
	 * List WooCommerce API defined.
	 *
	 * @return array|null|object
	 */
	private function list_apis() {

		if (empty($this->wcAPIs)) {
			global $wpdb;
			$prefix = $wpdb->prefix;
			$this->wcAPIs = $wpdb->get_results("SELECT * FROM {$prefix}woocommerce_api_keys ORDER BY description");
		}

		return $this->wcAPIs;
	}
}
