<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use Coderun\BuyOneClick\Entity\Order as OrderEntity;
use Coderun\BuyOneClick\Help;
use Coderun\BuyOneClick\Repository\Order;
use Coderun\BuyOneClick\Utils\Order as UtilsOrder;
use WC_Order;

/**
 * Class AdminController
 *
 * @package Coderun\BuyOneClick\Controller
 */
class AdminController extends Controller
{
    /**
     * @inheritDoc
     *
     * @return mixed|void
     */
    public function init()
    {
        if (!is_admin()) {
            return;
        }

        add_action(
            'wp_ajax_removeorder',
            [$this, 'deleteOrderById']
        );
        add_action(
            'wp_ajax_updatestatus',
            [$this, 'updateOrderStatus']
        );
        add_action(
            'wp_ajax_removeorderall',
            [$this, 'deleteAllOrders']
        );
    }

    /**
     * Удаляет заказ из таблицы заказов
     *
     * @return void
     */
    public function deleteOrderById(): void
    {

        // Удаление записи журнала плагина
        if (!empty($_POST['text'])) {
            $order_id = intval($_POST['text']);
            Order::getInstance()->deactive_order($order_id);
            wp_send_json_success();
        } elseif (!empty($_POST['orderId']) && !empty($_POST['pluginId'])) { //Удаление заказа
            $order_id = intval($_POST['orderId']);
            $pluginOrder = Order::getInstance()->findOneOrderByOrderWooCommerceId($order_id);
            if ($pluginOrder instanceof OrderEntity) {
                if (!UtilsOrder::thereIsAWooCommerceOrder($pluginOrder->getWooOrderId() ?? 0)) {
                    wp_send_json_error();
                }
                $order = new WC_Order($pluginOrder->getWooOrderId());
                if ($order instanceof WC_Order && $order->delete()) {
                    wp_send_json_success();
                }
            }
        }

        wp_send_json_error();
    }

    /**
     *
     * Удаляет все заказы из таблицы
     * @return void
     */
    public function deleteAllOrders(): void
    {
        $nonce = $_POST['nonce']; // Массив URL и NONCE
        ob_end_clean();
        if (wp_verify_nonce($nonce['nonce'], 'superKey')) {
            Order::getInstance()->remove_order_all();
            wp_send_json_success('ok');
        } else {
            wp_send_json_error('error');
        }
    }

    /**
     *
     *
     * @return void
     */
    public function updateOrderStatus(): void
    {
        $text = $_POST['text'];
        $id = $text['id'];
        Order::getInstance()->update_status($id, intval($text['status']));
        wp_send_json_success();
    }
}
