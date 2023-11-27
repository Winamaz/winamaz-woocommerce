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

namespace winamaz_woocommerce\core\system\functions\front;

use winamaz\core\system\functions\front\FrontCallable as ParentFrontCallable;
use winamaz\core\system\functions\inc\Shortcode as ParentShortcode;
use winamaz\core\system\functions\inc\Cache as ParentCache;
use winamaz\core\system\lib\PluginNameSpace as ParentPluginNameSpace;
use winamaz_woocommerce\core\system\lib\PluginNameSpace;
use VanillePlugin\lib\View;
use VanillePlugin\int\FrontAjaxInterface;
use VanillePlugin\inc\HttpGet;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\TypeCheck;
use VanillePlugin\lib\Queue as ParentQueue;
// use winamazVanillePlugin\lib\Queue as ParentQueue; // Injected

/**
 * The front Ajax main file,
 * Should be initialized in front and implements FrontAjaxInterface.
 */
class FrontAjax extends View implements FrontAjaxInterface
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		// Init plugin config
		$this->initConfig(new PluginNameSpace());

		// Add custom functions to view
		$view = new ParentFrontCallable();
		$this->setCallables($view->getCallables());
	}
	
	/**
	 * Get compare via Ajax.
	 *
	 * @access public
	 * @param void
	 * @return void
	 * @todo Remove Third-Party cache check
	 */
	public function doCompare()
	{
		if ( !ParentCache::hasThirdParty() ) {
			$this->checkToken('token');
		}
		
		// Check keyword
		if ( !($keyword = $this->check('keyword')) ) {
			$this->setHttpResponse('Undefined keyword', [], 'error');
		}

		// Check attributes
		if ( !($atts = $this->check('atts')) ) {
			$this->setHttpResponse('Undefined attributes', [], 'error');
		}

		// Check valid serialized attributes
		if ( !Stringify::isSerialized($atts) ) {
			$this->setHttpResponse('Invalid attributes', [
				'atts' => $atts
			], 'error');
		}

		// Init output
		$output = '';

		// Set unserialized attributes
		$atts = Stringify::unserialize(
			Stringify::unSlash($atts)
		);

		// Check valid unserialized attributes
		if ( !TypeCheck::isArray($atts) ) {
			$this->setHttpResponse('Invalid attributes', [
				'atts' => $atts
			], 'error');
		}

		// Run queue
		$queue = new ParentQueue(new ParentPluginNameSpace());
		if ( !$queue->has($keyword, 'compare') ) {

			// Add request to queue
			$queue->add($keyword, 'compare');

			// Get result (Ean : main inherit class)
			$product = ParentShortcode::i('ean', $keyword);
			$compare = $product->getCompare();

			// Filter result
			$compare = $this->applyFilter('winamaz-compare', $compare, $atts);

			// Assign result (Dynamic)
			if ( $compare ) {

		        $output .= $this->assign([
		        	'compare' => $compare,
		        	'atts'    => $atts
		        ], 'front/ajax-compare');

		        // Set output
			    $this->setHttpResponse('Success', [
			    	'output'    => $output,
			    	'bestprice' => $compare[0]
			    ], 'success');
			}

		} else {
			// Product in queue
			$this->setHttpResponse('In queue...', [], 'inqueue');
		}

		// Compare not found
		$this->setHttpResponse('Empty', [], 'empty');
	}

	/**
	 * Check request payload.
	 *
	 * @access private
	 * @param string $item
	 * @return mixed
	 */
	private function check($item)
	{
		if ( !HttpGet::isSetted($item) 
		  || !($value = HttpGet::get($item)) ) {
			return false;
		}
		return $value;
	}
}
