<?php
/**
 * Plugin Name: Winamaz - Woocommerce (Add-on)
 * Version: 1.2.5
 * Plugin URI: https://winamaz.com
 * Description: Implements Winamaz into WooCommerce (Beta)
 * Author: Winamaz <contact@winamaz.com>
 * Author URI: https://winamaz.com
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Requires at least: 5.8
 * Tested up to: 6.5.0
 * Requires PHP: 7.2.5
 *
 * Text Domain: winamaz-woocommerce
 * Domain Path: /languages/
 *
 * WC requires at least: 7.0.0
 * WC tested up to: 7.5.0
 */

/*
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

include(__DIR__ . '/core/Framework.php');
\winamaz_woocommerce\core\Framework::init();
\winamaz_woocommerce\core\system\Plugin::start();