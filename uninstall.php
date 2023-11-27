<?php
/**
 * @author    : Winamaz Team(c)
 * @package   : Winamaz WooCommerce
 * @version   : 1.2.5
 * @copyright : (c) 2018 - 2023 Winamaz <contact@winamaz.com>
 * @link      : https://winamaz.com
 * @license   : MIT
 */

// WordPress security basics
defined('WP_UNINSTALL_PLUGIN') || die('forbidden');

include(__DIR__ . '/core/Framework.php');
\winamaz_woocommerce\core\Framework::init();
\winamaz_woocommerce\core\system\Plugin::uninstall();