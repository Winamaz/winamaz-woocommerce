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

use winamaz_woocommerce\core\system\functions\admin\AdminHelper;
use winamaz_woocommerce\core\system\functions\admin\AdminConfig;
use VanillePlugin\lib\Ajax;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\lib\Requirement;
use VanillePlugin\int\AdminInterface;
use VanillePlugin\int\MenuInterface;
use VanillePlugin\int\SettingsInterface;

class Admin extends PluginOptions implements AdminInterface
{
	/**
	 * Admin setup.
	 *
	 * @param MenuInterface $menu
	 * @param SettingsInterface $settings
	 * @see use: isAdmin()
	 */
	public function __construct(MenuInterface $menu = null, SettingsInterface $settings = null)
	{
		if ( $this->isAdmin() ) {

			// Init plugin config
			$this->initConfig(new PluginNameSpace());

			/**
			 * Apply custom admin hooks.
			 * 
			 * @see __construct@AdminHelper
			 */
			new AdminHelper();

			/**
			 * Init plugin admin ajax.
			 * 
			 * @see __construct@AdminConfig
			 */
			if ( $this->getAjax() ) {
				new Ajax( new AdminConfig, new PluginNameSpace() );
			}

			/**
			 * Init plugin requirement.
			 * 
			 * @see requirement@config
			 */
			if ( $this->getRequirement() ) {
				new Requirement(new PluginNameSpace());
			}
		}
	}

	/**
	 * Add admin CSS.
	 * Action: admin_enqueue_scripts
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initCSS() {}

	/**
	 * Add admin JS.
	 * Action: admin_enqueue_scripts
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initJS() {}

	/**
	 * Add global admin CSS.
	 * Action: admin_enqueue_scripts
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function globalCSS() {}

	/**
	 * Add global admin JS.
	 * Action: admin_enqueue_scripts
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function globalJS() {}

	/**
	 * Override WordPress about and version.
	 * Action: admin_init
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function copyright() {}

	/**
	 * Add admin body class.
	 * Action: admin_body_class
	 * 
	 * @access public
	 * @param string $classes
	 * @return string
	 */
	public function addClass($classes = '') {}
}
