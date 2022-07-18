<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use WC_Order;

use function wc_get_order;

/**
 * Class Order
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Order
{
    /**
     * Проверка существования заказа WooCommerce по переданному ИД
     *
     * @param int $orderId
     *
     * @return bool
     */
    public static function thereIsAWooCommerceOrder(int $orderId): bool
    {
        $order = wc_get_order($orderId);
        if (!$order instanceof WC_Order) {
            return false;
        }
        if (empty($order->get_id())) {
            return false;
        }
        if ($order->get_status() === 'trash') {
            return false;
        }
        return true;
    }
}
