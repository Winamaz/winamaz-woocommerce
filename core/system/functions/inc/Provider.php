<?php
/**
 * @author    : Winamaz Team(c)
 * @package   : Winamaz WooCommerce
 * @version   : 1.2.5
 * @copyright : (c) 2018 - 2023 Winamaz <contact@winamaz.com>
 * @link      : https://winamaz.com
 * @license   : MIT
 */

namespace winamaz_woocommerce\core\system\functions\inc;

use winamaz\core\system\functions\inc\Provider as ParentProvider;
use winamaz\core\system\functions\inc\Keyword as ParentKeyword;
use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use VanillePlugin\lib\PluginOptions;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\Post;

final class Provider extends PluginOptions
{
	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function ean()
	{
		$keyword = self::keyword();
		if ( ParentKeyword::isEAN($keyword) ) {
			return $keyword;
		}
		$api = new Search($keyword, [
			'page'  => 1,
			'items' => 1
		]);
		return $api->getEan();
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function currency()
	{
		return ParentProvider::currency();
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function timeout()
	{
		return ParentProvider::timeout();
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function isCloaking()
	{
		return ParentProvider::isCloaking();
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function displayPrice()
	{
		$settings = self::settings();
		$val = $settings['disableprice'] ?? 'false';
		return ($val !== 'true');
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function displayCompare()
	{
		$settings = self::settings();
		$val = $settings['disable-compare'] ?? 'false';
		return ($val !== 'true');
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function displayUpdate()
	{
		$settings = self::settings();
		$val = $settings['updateprice'] ?? 'false';
		return ($val == 'true');
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function buttonText()
	{
		$settings = self::customize();
		$val = $settings['affiliate']['text'] ?? '';
		return !empty($val) ? $val : 'Show';
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function unavailableText()
	{
		$settings = self::customize();
		$val = $settings['unavailable']['text'] ?? '';
		return !empty($val) ? $val : 'Check';
	}

	/**
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function compareText()
	{
		$settings = self::customize();
		$val = $settings['compare']['text'] ?? '';
		return !empty($val) ? $val : 'Show prices';
	}

	/**
	 * @access private
	 * @param void
	 * @return mixed
	 */
	private static function keyword()
	{
	  	$meta = 'winamaz-woocommerce-product';
	  	$keyword = !empty(WooCommerce::getMeta($meta, Post::getId()))
	  	? WooCommerce::getMeta($meta, Post::getId()) : false;
	  	if ( !$keyword ) {
	  		$keyword = Stringify::lowercase(Post::getTitle());
	  	}
	  	return $keyword;
	}

	/**
	 * @access private
	 * @param void
	 * @return array
	 */
	private static function amazon()
	{
		$plugin = parent::getStatic();
		$plugin->initConfig(new PluginNameSpace());
		return (array)$plugin->getOption('winamaz_amazon');
	}

	/**
	 * @access private
	 * @param void
	 * @return array
	 */
	private static function settings()
	{
		$plugin = parent::getStatic();
		$plugin->initConfig(new PluginNameSpace());
		return (array)$plugin->getOption('winamaz_settings');
	}

	/**
	 * @access private
	 * @param void
	 * @return array
	 */
	private static function customize()
	{
		$plugin = parent::getStatic();
		$plugin->initConfig(new PluginNameSpace());
		return (array)$plugin->getOption('winamaz_customize');
	}
}
