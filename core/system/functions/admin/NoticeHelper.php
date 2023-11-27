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

use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use VanillePlugin\lib\Notice;

final class NoticeHelper extends Notice
{
	/**
	 * @access private
	 * @param string $version, Parent version
	 */
	private $version;

	/**
	 * @param string $version
	 */
	public function __construct($version = null)
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());
		$this->version = $version;
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function parent()
	{
		$notice = 'Winamaz version v%s or higher is required';
		$this->render([
			'notice' => $this->translateVars($notice, [$this->version])
		], 'admin/inc/notice/requirement');
	}
}
