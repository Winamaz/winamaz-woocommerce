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
use VanillePlugin\lib\Notice;

final class Upgrader extends Notice
{
	/**
	 * @access private
	 * @var bool $silent
	 */	
	private $silent = false;

	/**
	 * @param void
	 */
	public function __construct($silent = false)
	{
		// Slient upgrade
		$this->silent = $silent;
		
		// Init plugin config
		$this->initConfig(new PluginNameSpace());
		$this->init([$this, 'doUpgrade']);
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function doUpgrade()
	{
		// Purge cache
		ParentCache::flush();
		ParentCache::purgeTransients();
		
		// Finish update
		$this->setTransient('updated', 0);

		// Set notice
		if ( !$this->silent ) {
			$this->render([
				'message' => $this->translateString('Winamaz WooCommerce was updated')
			], 'admin/inc/notice/upgrade');
		}
	}
}
