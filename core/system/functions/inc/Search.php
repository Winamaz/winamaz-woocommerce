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

use winamaz\core\system\functions\inc\Search as ParentSearch;

final class Search extends ParentSearch
{
	/**
	 * @param string $keyword
	 * @param array $params
	 */
	public function __construct($keyword = '', $params = [])
	{
		parent::__construct($keyword, $params);
	}

	/**
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function getEan()
	{
		$this->setOptions();
		$this->doSearch();
		if ( $this->result ) {
			foreach ($this->result as $merchant => $products) {
				if ( count($products) ) {
					return $products[0]['ean'];
					break;
				}
			}
		}
		return false;
	}
}
