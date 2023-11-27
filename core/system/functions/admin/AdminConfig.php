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

use winamaz\core\system\functions\inc\Cache as ParentCache;
use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use winamaz_woocommerce\core\system\functions\front\FrontAjax;
use VanillePlugin\int\AdminAjaxInterface;
use VanillePlugin\lib\View;

/**
 * The admin Ajax main file,
 * Should be initialized in admin and implements AdminAjaxInterface.
 */
final class AdminConfig extends View implements AdminAjaxInterface
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 * @internal
	 * @todo Remove Third-Party cache check
	 */
	public function doCompare()
	{
		if ( !ParentCache::hasThirdParty() ) {
			$this->checkToken('token');
		}
		$ajax = new FrontAjax();
		$ajax->doCompare();
	}
}
