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

final class WooCommerce
{
	/**
	 * @access public
	 * @param mixed $id
	 * @return array
	 */
	public static function getProduct($id)
	{
		return wc_get_product(intval($id));
	}
	
	/**
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public static function addInput($data = [])
	{
  		woocommerce_wp_text_input($data);
	}

	/**
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public static function addCheckbox($data = [])
	{
  		woocommerce_wp_checkbox($data);
	}

	/**
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public static function addSelect($data = [])
	{
  		woocommerce_wp_select($data);
	}
	
	/**
	 * @access public
	 * @param int $key
	 * @param mixed $product
	 * @return mixed
	 */
	public static function getMeta($key, $product)
	{
		if ( is_object($product) ) {
			$product = self::getProduct($product->get_id());
			
		} else {
			$product = (object)self::getProduct($product);
		}
  		return $product->get_meta($key);
	}

	/**
	 * @access public
	 * @param int $key
	 * @param mixed $product
	 * @return int
	 */
	public static function updateMeta($key, $value, $product)
	{
		if ( is_object($product) ) {
			$product = self::getProduct($product->get_id());

		} else {
			$product = self::getProduct($product);
		}
    	$product->update_meta_data($key, sanitize_text_field($value));
    	return $product->save();
	}
}
