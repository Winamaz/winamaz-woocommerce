<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework,
 * Allowed to edit for plugin customization.
 */

namespace winamaz_woocommerce\core\system\functions\front;

use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use winamaz_woocommerce\core\system\functions\inc\Provider;
use VanillePlugin\lib\View;

final class FrontHelper extends View
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());

		/**
		 * Action: woocommerce_single_variation
		 * Method: woocommerce_single_variation_add_to_cart_button
		 *
		 * @property priority 20
		 * @property count 0
		 */
		$this->removeAction('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);

		/**
		 * Action: woocommerce_single_product_summary
		 *
		 * @see self@initBestprice
		 * @property priority 20
		 * @property count 0
		 */
		$this->addAction('woocommerce_single_product_summary', [$this, 'initBestprice'], 20);

		/**
		 * Action: woocommerce_after_single_product_summary
		 *
		 * @see self@initCompare
		 * @property priority 20
		 * @property count 0
		 */
		$this->addAction('woocommerce_after_single_product_summary', [$this, 'initCompare'], 20);
		
		/**
		 * Filter: woocommerce_is_purchasable
		 *
		 * @property priority 20
		 * @property count 0
		 */
		$this->addFilter('woocommerce_is_purchasable', '__return_false', 20);

		/**
		 * Filter: woocommerce_get_price_html
		 *
		 * @property priority 20
		 * @property count 0
		 */
		$this->addFilter('woocommerce_get_price_html', '__return_false', 20);

		/**
		 * Filter: woocommerce_out_of_stock_message
		 *
		 * @property priority 20
		 * @property count 0
		 */
		$this->addFilter('woocommerce_out_of_stock_message', '__return_false', 20);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initBestprice()
	{
	  	$this->render([
	  		'displayPrice' => Provider::displayPrice(),
	  		'compareText'  => $this->translateString(Provider::compareText())
	  	], 'front/bestprice');
	}

	/**
	 * Init compare using EAN.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initCompare()
	{
	  	if ( ($ean = Provider::ean()) ) {
	  		$this->render([
	  			'keyword'         => $ean,
	  			'pluginUrl'       => $this->getPluginUrl('winamaz'),
	  			'lang'            => $this->getLanguage(),
	  			'currency'        => Provider::currency(),
	  			'isCloaking'      => Provider::isCloaking(),
	  			'displayPrice'    => Provider::displayPrice(),
	  			'displayCompare'  => Provider::displayCompare(),
	  			'displayUpdate'   => Provider::displayUpdate(),
	  			'buttonText'      => $this->translateString(Provider::buttonText()),
	  			'unavailableText' => $this->translateString(Provider::unavailableText())
	  		], 'front/compare');
	  	}
	}
}
