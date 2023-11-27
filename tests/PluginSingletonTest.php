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
use winamaz_woocommerce\core\system\Plugin;

final class PluginSingletonTest extends TestCase
{
	public function testInstance()
	{
		// Force test with casting 3 instances
		$count = 0;
		$instance = new Plugin();
		$instance = (array)$instance;
		$instance = $instance["\0VanillePlugin\lib\PluginOptions\0global"];
		if ( !is_null($instance) ) {
			$count++;
		}
		$instance = new Plugin();
		$instance = (array)$instance;
		$instance = $instance["\0VanillePlugin\lib\PluginOptions\0global"];
		if ( !is_null($instance) ) {
			$count++;
		}
		$instance = new Plugin();
		$instance = (array)$instance;
		$instance = $instance["\0VanillePlugin\lib\PluginOptions\0global"];
		if ( !is_null($instance) ) {
			$count++;
		}
		$this->assertSame($count,0);
	}

	public function testStart()
	{
		$this->assertTrue(
			is_null(Plugin::start())
		);
	}
}
