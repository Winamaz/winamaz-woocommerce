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

namespace winamaz_woocommerce\core\system\lib;

use VanillePlugin\int\PluginNameSpaceInterface;

/**
 * @see Generated using: https://jakiboy.github.io/VanillePlugin-Installer/
 */
class PluginNameSpace implements PluginNameSpaceInterface
{
	/**
     * Get plugin namespace (slug),
     * Should be the same as '{pluginDir}/{pluginMain}.php'.
	 *
	 * @access public
	 * @param void
	 * @return string
	 * @throws NamepsaceException
	 */
	public function getNameSpace()
	{
		return 'winamaz-woocommerce';
	}
}
