<?php

/*
 * Plugin Name: Buy one click WooCommerce
 * Plugin URI: https://github.com/northmule/buy-one-click-woocommerce
 * Description: Buy in one click for WooCommerce. The best plugin that adds to your online store purchase button in one click
 * Version: 2.2.9
 * Author: Djo
 * Author URI: https://zixn.ru
 * WC requires at least: 6.0
 * WC tested up to: 8.7
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Text Domain: coderun-oneclickwoo
 * Domain Path: /languages
 */

/*  Copyright 2024  Djo  (email: izm@zixn.ru)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

__('Buy one click WooCommerce');
__('Buy in one click for WooCommerce. The best plugin that adds to your online store purchase button in one click');

if (!defined('ABSPATH')) {
    exit;
}

define('CODERUN_ONECLICKWOO_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('CODERUN_ONECLICKWOO_TEMPLATES_PLUGIN_DIR', CODERUN_ONECLICKWOO_PLUGIN_DIR . '/templates');
define('CODERUN_ONECLICKWOO_PLUGIN_VERSION', '2.0.2');

/**
 * Инициализация всего плагина
 */
(function()
{
    load_plugin_textdomain(
        'coderun-oneclickwoo',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
    
    require_once(CODERUN_ONECLICKWOO_PLUGIN_DIR . '/vendor/autoload.php');
    
    $main = Coderun\BuyOneClick\Core::getInstance();
    $main->initializingPlugin();
    register_activation_hook(__FILE__, [$main, 'activationPlugin']);
    register_deactivation_hook(__FILE__, [$main, 'deactivationPlugin']);
    
    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables', __FILE__, true
            );
        }
    });
    
    
})();
