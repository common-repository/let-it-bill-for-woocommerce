<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Let_It_Bill
 * @author    Valentin Carage <valentin.carage@let-it-bill.com>
 * @license   GPL-2.0+
 * @link      https://www.let-it-bill.com
 * @copyright 2015 Ideys SARL
 */
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<hr/>
<?php
	switch ($this->get_woo_commerce_manager()->check_woo_commerce_api()):

		/*-------------------------
		 * LET IT BILL connexion to WooCommerce is OK.
		 --------------------------*/

		case Let_It_Bill_Woo_Commerce::WOO_COMMERCE_API_ACTIVE:
			/** @param \DateTime */
			$lastConnexion = $this->get_woo_commerce_manager()->get_last_api_connexion();
			if ($lastConnexion instanceof \DateTime) {
				$syncDate = date_i18n( get_option( 'date_format' ), $lastConnexion->getTimestamp());
				$syncTime = $lastConnexion->format('H:i');
?>
				<p><?php printf(__('The last synchronization was performed the %s at %s.', 'let-it-bill'), $syncDate, $syncTime) ?></p>
<?php
			} else {
?>
				<p><?php _e('The synchronization have not been performed yet.', 'let-it-bill') ?></p>
<?php
			}
			break;

		/*-------------------------
		 * Connexion to WooCommerce from LET IT BILL fail.
		 --------------------------*/

		case Let_It_Bill_Woo_Commerce::WOO_COMMERCE_API_NOT_CONNECTED:
?>
			<div id="message" class="notice updated">
				<p><?php _e('LET IT BILL can\'t connect to the WooCommerce API.', 'let-it-bill') ?></p>
			</div>
			<p><?php _e('You must check your WooCommerce and LET IT BILL parameters to restore the synchronization.') ?></p>
			<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=api' ) ?>"><?php _e('Check the WooCommerce parameters', 'let-it-bill') ?></a>
<?php
			break;

		/*-------------------------
		 * WooCommerce has no API endpoint defined.
		 --------------------------*/

		case Let_It_Bill_Woo_Commerce::WOO_COMMERCE_API_NOT_DEFINED:
?>
			<div id="message" class="notice updated">
				<p><?php _e('None API key found for WooCommerce connexion.', 'let-it-bill') ?></p>
			</div>
			<p><?php _e('To connect WooCommerce to LET IT BILL you must create an API key and record it to the LET IT BILL exchange settings.', 'let-it-bill') ?></p>
			<a href="<?php echo admin_url('admin.php?page=wc-settings&tab=api&section=keys') ?>"><?php _e('Generate an API key', 'let-it-bill') ?></a>
<?php
			break;

		/*-------------------------
		 * WooCommerce is not installed or not enabled.
		 --------------------------*/

		case Let_It_Bill_Woo_Commerce::WOO_COMMERCE_PLUGIN_DISABLED:
?>
		<div id="message" class="notice updated">
			<p><?php _e('The WooCommerce plugin must be installed to enjoy the LET IT BILL accounting data synchronization.', 'let-it-bill') ?></p>
		</div>
		<a href="<?php Let_It_Bill_Woo_Commerce::WOO_COMMERCE_WEBSITE ?>" target="_blank"><?php _e('Visit WooCommerce website', 'let-it-bill') ?></a>
<?php
			break;

	endswitch;
?>
</div>
