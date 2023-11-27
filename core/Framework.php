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

namespace winamaz_woocommerce\core;

final class Framework
{
	/**
	 * @access private
	 * @var bool $initialized
	 */
	private static $initialized = false;

	/**
	 * Register plugin autoloader.
	 *
	 * @param void
	 */
	public function __construct()
	{
		// WordPress security basics
		defined('ABSPATH') || die('forbidden');

		if ( !static::$initialized ) {

			// Include composer dependencies
			require_once(wp_normalize_path(
				__DIR__ . '/vendor/autoload.php'
			));

			// Include internal components
			spl_autoload_register([__CLASS__,'autoload']);
			
			// Finish initialization
			static::$initialized = true;

		}
	}

	/**
	 * Unregister plugin autoloader.
	 */
	public function __destruct()
	{
		spl_autoload_unregister([__CLASS__,'autoload']);
	}

	/**
	 * Restrict object clone.
	 */
    public function __clone()
    {
        die(__METHOD__ . ': Clone denied');
    }

	/**
	 * Restrict object unserialize.
	 */
    public function __wakeup()
    {
        die(__METHOD__ . ': Unserialize denied');
    }

	/**
	 * Autoloader method.
	 * 
	 * @access private
	 * @param string $class __CLASS__
	 * @return void
	 * @see https://www.php-fig.org/psr/psr-4/
	 * @see https://www.php-fig.org/psr/psr-0/
	 */
	private function autoload($class)
	{
	    if ( strpos($class, __NAMESPACE__ . '\\') === 0 ) {
	        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
	        $class = str_replace('\\','/',$class);
	        $namespace = str_replace('\\', '/', __NAMESPACE__);
	        $namespace = str_replace('_', '-', __NAMESPACE__);
	        require_once(wp_normalize_path(
	        	WP_PLUGIN_DIR . "/{$namespace}/{$class}.php")
	    	);
	    }
	}

	/**
	 * Initialize framework.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function init()
	{
		if ( !static::$initialized ) {
			new static;
		}
	}
}
