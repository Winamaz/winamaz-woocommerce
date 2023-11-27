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

namespace winamaz_woocommerce\core\system;

use winamaz_woocommerce\core\system\functions\admin\AdminHelper;
use winamaz_woocommerce\core\system\functions\admin\NoticeHelper;
use winamaz_woocommerce\core\system\lib\Admin;
use winamaz_woocommerce\core\system\lib\Front;
use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\int\PluginInterface;

final class Plugin extends PluginOptions implements PluginInterface
{
	/**
	 * @access private
	 * @var bool $initialized
	 */
	private static $initialized = false;

	/**
	 * Setup plugin.
	 *
	 * @param void
	 */
	public function __construct()
	{
		if ( !static::$initialized ) {

			// Init plugin config
			$this->initConfig(new PluginNameSpace());

			// Hook plugin activation
			$this->registerActivation($this->getMainFile(), [$this,'activate']);

			// Hook plugin deactivation
			$this->registerDeactivation($this->getMainFile(), [$this,'deactivate']);

			// Hook plugin uninstall: using class name instead of $this
			$this->registerUninstall($this->getMainFile(), ['Plugin','uninstall']);

			// Hook plugin links
			$this->addFilter("plugin_action_links_{$this->getMainFile()}", [$this,'action']);

			// Hook plugin upgrader
			$this->addAction('upgrader_process_complete', [$this,'upgrade'], 10, 2);

			// Hook plugin load
			$this->addAction('plugins_loaded', [$this,'load']);

			// Finish initialization
			static::$initialized = true;
		}
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
	 * Plugin start action.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function start()
	{
		if ( !static::$initialized ) {
			new static;
		}
	}

	/**
	 * Plugin load action.
	 * Action: plugins_loaded
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function load()
	{
		// Check parent plugin
		$version = '1.3.0';

		// Load plugin translation
		$this->translate();

		if ( $this->isPluginVersion('winamaz/winamaz.php',$version) ) {

			// Load plugin Admin
			new Admin();

			// Load plugin Front
			new Front();

			// Hook after load
			$this->doPluginAction('load');

		} else {

			// Set Notice
			$notice = new NoticeHelper($version);
			$notice->init([$notice,'parent']);
		}
	}

	/**
	 * Plugin activation action.
	 * Action: activate_{plugin}
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function activate()
	{
		// Apply internal 'activate' hook
		new AdminHelper();

		// Hook after activate
		$this->doPluginAction('activate');
	}

	/**
	 * Plugin deactivation action.
	 * Action: deactivate_{plugin}
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function deactivate()
	{
		// Hook after deactivate
		$this->doPluginAction('deactivate');
	}

	/**
	 * Plugin upgrade action.
	 * Action: upgrader_process_complete
	 *
	 * @access public
	 * @param object $upgrader
	 * @param array $options
	 * @return void
	 */
	public function upgrade($upgrader, $options)
	{
	    if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
	    	if ( isset($options['plugins']) ) {
		        foreach($options['plugins'] as $plugin){
			        if ( $plugin == $this->getMainFile() ) {
						// Hook upgrade
						$this->doPluginAction('upgrade');
			        }
		        }
	    	}
	    }
	}

	/**
	 * Plugin uninstall action.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function uninstall()
	{
		$plugin = parent::getStatic();
		$plugin->initConfig(new PluginNameSpace());

		// Hook after uninstall
		$plugin->doPluginAction('uninstall');
	}

	/**
	 * Add plugin action links.
	 * Action: plugin_action_links_{plugin}
	 *
	 * @access public
	 * @param array $links
	 * @return array
	 */
	public function action($links)
	{
		return $this->applyPluginFilter('plugin-action',$links);
	}
}
