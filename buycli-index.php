<?php

/*
 * Plugin Name: Buy one click WooCommerce
 * Plugin URI: http://zixn.ru/plagin-zakazat-v-odin-klik-dlya-woocommerce.html
 * Description: Buy in one click for WooCommerce. The best plugin that adds to your online store purchase button in one click
 * Version: 1.17.1
 * Author: Djo
 * Author URI: https://zixn.ru
 * WC requires at least: 3.9
 * WC tested up to: 5.6
 * Requires at least: 5.1
 * Tested up to: 5.8
 * Text Domain: coderun-oneclickwoo
 * Domain Path: /languages
 */

/*  Copyright 2021  Djo  (email: izm@zixn.ru)

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

define('CODERUN_ONECLICKWOO_PLUGIN_VERSION','1.16.1');


/**
 * Инициализация всего плагина
 */
function coderun_buy_plugin_init_core() {
    
    
    load_plugin_textdomain(
        'coderun-oneclickwoo', false, dirname(plugin_basename(__FILE__)) . '/languages'
    );
    
    require_once (CODERUN_ONECLICKWOO_PLUGIN_DIR . '/vendor/autoload.php');

    $core = Coderun\BuyOneClick\Core::getInstance();

    register_deactivation_hook(__FILE__, array($core, 'deactivationPlugin'));
    register_activation_hook(__FILE__, array($core, 'addOptions'));
    
    /** сервисные операции */
    if (get_option( 'wp_coderun_oneclickwoo_db_version',0) !=  Coderun\BuyOneClick\PluginUpdate::DB_VERSION) {
        Coderun\BuyOneClick\PluginUpdate::createOrderTable();
    }
    
}

coderun_buy_plugin_init_core();

