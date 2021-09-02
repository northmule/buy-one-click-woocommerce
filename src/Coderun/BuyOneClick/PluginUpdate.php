<?php

namespace Coderun\BuyOneClick;


/**
 * Только для обновления плагина, каки-то сервисные операции
 */
class PluginUpdate
{
    const DB_VERSION = 2;
    
    public static function createOrderTable() {
        global $wpdb;
        $query = <<<EOT
                        CREATE TABLE `wp_coderun_oneclickwoo_orders` (
                            `id` BIGINT(10) NOT NULL AUTO_INCREMENT,
                            `plugin_version` VARCHAR(50) NOT NULL DEFAULT '0',
                            `active` TINYINT(1) NULL DEFAULT '1',
                            `status` TINYINT(1) NULL DEFAULT '2',
                            `product_id` INT(11) NULL DEFAULT '0',
                            `product_name` VARCHAR(300) NULL DEFAULT NULL,
                            `product_meta` TEXT(65535) NULL DEFAULT NULL,
                            `product_price` DOUBLE(10,2) NULL DEFAULT '0.00',
                            `product_quantity` INT(11) NULL DEFAULT NULL,
                            `form` LONGTEXT NULL DEFAULT NULL,
                            `sms_log` VARCHAR(400) NULL DEFAULT '',
                            `woo_order_id` BIGINT(20) NULL DEFAULT '0',
                            `user_id` BIGINT(20) NULL DEFAULT '0',
                            `date_update` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                            `date_create` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`) USING BTREE
                        )
                        COMMENT='Заказы плагина Buy one click WooCommerce'
                        COLLATE='utf8_general_ci'
                        ENGINE=InnoDB
                        AUTO_INCREMENT=1;


EOT;
        if ($wpdb->get_var("SHOW TABLES LIKE 'wp_coderun_oneclickwoo_orders'") != 'wp_coderun_oneclickwoo_orders'){
            $wpdb->query($query);
            update_option('wp_coderun_oneclickwoo_db_version',self::DB_VERSION);
        }
        
    }
    
}