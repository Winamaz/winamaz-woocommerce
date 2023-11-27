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

namespace winamaz_woocommerce\core\system\functions\admin;

use winamaz\core\system\functions\api\WebserviceAPI as ParentWebserviceAPI;
use winamaz\core\system\functions\inc\Provider as ParentProvider;
use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use winamaz_woocommerce\core\system\functions\inc\WooCommerce;
use VanillePlugin\lib\View;
use VanillePlugin\lib\Updater;
use VanillePlugin\inc\HttpPost;
use VanillePlugin\inc\Stringify;

final class AdminHelper extends View
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());

		/**
		 * Plugin activate action.
		 * Action: {plugin}-activate
		 *
		 * @see activate@self
		 * @property priority 10
		 * @property count 0
		 */
		$this->addPluginAction('activate', [$this,'activate']);

		/**
		 * Plugin deactivate action.
		 * Action: {plugin}-deactivate
		 *
		 * @see deactivate@self
		 * @property priority 10
		 * @property count 0
		 */
		$this->addPluginAction('deactivate', [$this,'deactivate']);

		/**
		 * Plugin upgrade action.
		 * Action: {plugin}-upgrade
		 *
		 * @see upgrade@self
		 * @property priority 10
		 * @property count 0
		 */
		$this->addPluginAction('upgrade', [$this,'upgrade']);

		/**
		 * Plugin update action.
		 * Action: admin_init
		 *
		 * @see update@self
		 * @property priority 10
		 * @property count 0
		 */
		$this->addAction('admin_init', [$this,'update']);

		/**
		 * Plugin notice action.
		 * Action: admin_init
		 *
		 * @see notice@self
		 * @property priority 10
		 * @property count 0
		 */
		$this->addAction('admin_init', [$this,'notice']);

		/**
		 * Action: woocommerce_product_options_general_product_data
		 *
		 * @see self@addInput
		 * @property priority 10
		 * @property count 0
		 */
		$this->addAction('woocommerce_product_options_general_product_data', [$this,'addInput']);

		/**
		 * Action: woocommerce_process_product_meta
		 *
		 * @see self@saveInput
		 * @property priority 10
		 * @property count 0
		 */
		$this->addAction('woocommerce_process_product_meta', [$this,'saveInput']);

		/**
		 * Plugin action links.
		 * Filter: plugin-action
		 *
		 * @see action@self
		 * @property priority 10
		 * @property count 1
		 */
		$this->addPluginFilter('plugin-action', [$this,'action']);

		/**
		 * Disable Auto-update.
		 * Filter: auto_update_plugin
		 *
		 * @see disableAutoUpdate@self
		 * @property priority 10
		 * @property count 2
		 */
		$this->addFilter('auto_update_plugin', [$this,'disableAutoUpdate'], 10, 2);
	}

	/**
	 * Plugin activate action.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 * @todo Check
	 */
	public function activate() {}

	/**
	 * Plugin deactivate action.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function deactivate() {}
	
	/**
	 * Plugin upgrade action.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function upgrade()
	{
		// Set plugin as updated
		$this->setTransient('updated', 1);
	}
	
	/**
	 * Plugin update action.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function update()
	{
		// Plugin updater
		$auth = ParentProvider::auth();
		$baseUrl = Stringify::replace('/api/v1', '', ParentWebserviceAPI::ENDPOINT);
		new Updater(new PluginNameSpace(), ParentWebserviceAPI::ENDPOINT . '/plugin/update/', [
			'auth'           => [$auth['email'],$auth['password']],
			'license'        => ParentProvider::license(),
			'infoUrl'        => ParentWebserviceAPI::ENDPOINT . '/plugin/info/',
			'translationUrl' => ParentWebserviceAPI::ENDPOINT . '/plugin/translation/',
			'assetUrl'       => "{$baseUrl}/public/update/"
		]);

		// Plugin upgrader
		if ( intval($this->getTransient('updated')) ) {
			new Upgrader();
		}
	}

	/**
	 * Plugin notice action.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function notice() {}

	/**
	 * Plugin action links.
	 * 
	 * @access public
	 * @param array $links
	 * @return array
	 */
	public function action($links)
	{
    	return $links;
	}

	/**
	 * Disable auto-update.
	 * 
	 * @access public
	 * @param bool $update
	 * @param object $item
	 * @return mixed
	 */
	public function disableAutoUpdate($update, $item)
	{
		if ( $this->applyPluginFilter('disable-auto-update',false) ) {
			if ( $item->plugin === $this->getMainFile() ) {
				return false;
			}
		}
		return $update;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function addInput()
	{
  		WooCommerce::addInput([
      		'id'    	  => 'winamaz-woocommerce-product',
      		'placeholder' => $this->translateString('Keyword / ASIN / ISBN / EAN / UPC'),
      		'label'       => $this->translateString('Product (Winamaz)')
  		]);
	}

	/**
	 * @access public
	 * @param int $productID
	 * @return void
	 */
	public function saveInput($productID)
	{
    	$id = 'winamaz-woocommerce-product';
    	$value = HttpPost::isSetted($id) ? HttpPost::get($id) : '';
    	WooCommerce::updateMeta($id, $value, $productID);
	}
}
