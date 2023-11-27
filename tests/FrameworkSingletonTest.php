<?php
/**
 * @author    : Winamaz Team(c)
 * @package   : Winamaz WooCommerce
 * @version   : 1.2.5
 * @copyright : (c) 2018 - 2023 Winamaz <contact@winamaz.com>
 * @link      : https://winamaz.com
 * @license   : MIT
 */

use PHPUnit\Framework\TestCase;
use winamaz_woocommerce\core\Framework;

final class FrameworkSingletonTest extends TestCase
{
	public function testInit()
	{
		$this->assertTrue(
			is_null(Framework::init())
		);
	}
}
