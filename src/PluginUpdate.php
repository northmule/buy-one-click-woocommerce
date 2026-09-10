<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick;

/**
 * Только для обновления плагина, каки-то сервисные операции
 */
class PluginUpdate
{
    public const int DB_VERSION = 2;

    /**
     * Создание БД при необходимости
     *
     * @return void
     */
    public static function createOrderTable(): void
    {
        global $wpdb;
        // Static CREATE TABLE DDL for the plugin's own table; contains no user-supplied data.
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
        // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
        $createTable = "CREATE TABLE IF NOT EXISTS `wp_coderun_oneclickwoo_orders` (
                      `id` bigint(10) NOT NULL AUTO_INCREMENT,
                      `plugin_version` varchar(50) NOT NULL DEFAULT '0',
                      `active` tinyint(1) DEFAULT '1',
                      `status` tinyint(1) DEFAULT '2',
                      `product_id` int(11) DEFAULT '0',
                      `product_name` varchar(300) DEFAULT NULL,
                      `product_meta` mediumtext,
                      `product_price` double(10,2) DEFAULT '0.00',
                      `product_quantity` int(11) DEFAULT NULL,
                      `form` longtext,
                      `sms_log` varchar(400) DEFAULT '',
                      `woo_order_id` bigint(20) DEFAULT '0',
                      `user_id` bigint(20) DEFAULT '0',
                      `date_update` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                      `date_create` datetime DEFAULT CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`) USING BTREE,
                      KEY `wp_coderun_oneclickwoo_orders_product_id_IDX` (`product_id`) USING BTREE,
                      KEY `wp_coderun_oneclickwoo_orders_woo_order_id_IDX` (`woo_order_id`) USING BTREE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Orders from the plugin in One Click';";

        // Статичный DDL без пользовательских данных, подготовка не требуется
        $wpdb->query($createTable);
        update_option('wp_coderun_oneclickwoo_db_version', self::DB_VERSION);
    }
}
