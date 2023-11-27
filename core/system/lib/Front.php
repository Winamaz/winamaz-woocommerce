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

use winamaz_woocommerce\core\system\functions\inc\Provider;
use winamaz_woocommerce\core\system\functions\front\FrontHelper;
use winamaz_woocommerce\core\system\functions\front\FrontAjax;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\lib\Ajax;
use VanillePlugin\int\FrontInterface;
use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\thirdparty\AMP;

class Front extends PluginOptions implements FrontInterface
{
	/**
	 * Setup front.
	 *
	 * @param ShortcodeInterface $shortcode
	 * @see use: !isAdmin()
	 */
	public function __construct(ShortcodeInterface $shortcode = null)
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());

		if ( !$this->isAdmin() ) {

			/**
			 * Init plugin front.
			 * Action: wp
			 * 
			 * @see init@self
			 */
			$this->addAction('wp', [$this,'init']);
		}

		/**
		 * Apply custom front hooks.
		 * 
		 * @see outside: !isAdmin()
		 * @see __construct@FrontHelper
		 */
		new FrontHelper();

		/**
		 * Init plugin front ajax.
		 * 
		 * @see outside: !isAdmin()
		 * @see __construct@FrontAjax
		 */
		if ( $this->getFrontAjax() ) {
			new Ajax(new FrontAjax(), new PluginNameSpace());
		}
	}

	/**
	 * Init plugin front.
	 * Action: wp
	 * 
	 * @access public
	 * @param void
	 * @return void
	 * @see use: !AMP::isActive()
	 */
	public function init()
	{
		if ( !AMP::isActive() ) {

			/**
			 * Add front plugin CSS.
			 * Action: wp_enqueue_scripts
			 *
			 * @see initCSS@self
		 	 * @property priority 10
		 	 * @property count 0
			 */
			$this->addAction('wp_enqueue_scripts', [$this,'initCSS']);

			/**
			 * Add front plugin JS.
			 * Action: wp_enqueue_scripts
			 *
			 * @see initJS@self
		 	 * @property priority 10
		 	 * @property count 0
			 */
			$this->addAction('wp_enqueue_scripts', [$this,'initJS']);

			/**
			 * Add front body class.
			 * Action: body_class
			 *
			 * @see addClass@self
		 	 * @property priority 10
		 	 * @property count 1
			 */
			$this->addFilter('body_class', [$this,'addClass'], 10);
		}
	}

	/**
	 * Add front plugin CSS.
	 * Action: wp_enqueue_scripts
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initCSS()
	{
		// Add plugin main CSS: Disabled cache
		$this->addPluginMainCSS('/front/css/style.css', [], date('ymdhis'));
	}

	/**
	 * @access public
	 * @param void
	 * @return void
	 */
	public function initJS()
	{
		// Add plugin main JS: Disabled cache
		$this->addPluginMainJS('/front/js/main.js', ['jquery'], date('ymdhis'));

		// Setup plugin JS settings
		$this->localizePluginJS([
			'ajaxurl'    => $this->getAjaxUrl(),
			'pluginName' => $this->getPluginName(),
			'namespace'  => $this->getNameSpace(),
			'baseUrl'    => $this->getBaseUrl(),
			'strings'    => $this->loadStrings('front'),
			'timeout'    => Provider::timeout(),
			'currency'   => Provider::currency()
		]);
	}

	/**
	 * Add front body class.
	 * Action: body_class
	 * 
	 * @access public
	 * @param array $classes
	 * @return array
	 */
	public function addClass($classes)
	{
		$classes[] = "has-{$this->getNameSpace()}";
		return $classes;
	}
}
