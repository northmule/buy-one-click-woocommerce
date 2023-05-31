<?php

namespace Coderun\BuyOneClick\Repository;

use Coderun\BuyOneClick\Entity\Order as OrderEntity;
use Coderun\BuyOneClick\Hydrator\CommonHydrator;
use Coderun\BuyOneClick\Utils\Hooks;
use Coderun\BuyOneClick\Utils\Product as ProductUtils;
use Exception;
use WC_Order;
use WC_Order_Item;
use WC_Order_Item_Product;

class Order
{
    /** @var Order  */
    protected static $_instance = null;
    /** @var string  */
    protected $order_table = 'wp_coderun_oneclickwoo_orders';

    /**
     * Singletone
     *
     * @return Order
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Создаёт необходимый объект заказа
     *
     * @param $params
     *
     * @return WC_Order|\WP_Error
     * @throws \WC_Data_Exception
     */
    public function create_order($params)
    {
        $default_params = [
            'first_name'          => '',
            'last_name'           => '',
            'company'             => '',
            'email'               => '',
            'phone'               => '',
            'address_1'           => '',
            'address_2'           => '',
            'city'                => '',
            'state'               => '',
            'postcode'            => '',
            'country'             => '',
            'order_status'        => 'processing', //Статус заказа который будет установлен
            'message_notes_order' => __('Quick order form', 'coderun-oneclickwoo'), //Сообщение в заказе
            'qty'                 => 1,
            'product_id'          => 0, //ИД товара Woo или ИД вариации
        ];

        $params = wp_parse_args($params, $default_params);
        $product = wc_get_product($params['product_id']);
        $order = wc_create_order($params); //создаём новый заказ
        $product_params = [
            'name'         => $product->get_name(),
            'tax_class'    => $product->get_tax_class(),
            'product_id'   => $product->is_type('variation') ? $product->get_parent_id() : $product->get_id(),
            'variation_id' => $params['product_id'],
            'variation'    => $product->is_type('variation') ? $product->get_attributes() : [],
            'subtotal'     => ProductUtils::getProductPrice($product) * intval($params['qty']),
            'total'        => ProductUtils::getProductPrice($product) * intval($params['qty']),
            'quantity'     => $params['qty'],
        ];
        $order->add_product($product, $params['qty'], $product_params);
        $order->set_billing_first_name($params['first_name']);
        $order->set_billing_last_name($params['last_name']);
        $order->set_billing_company($params['company']);
        $order->set_billing_email($params['email']);
        $order->set_billing_phone($params['phone']);
        $order->set_billing_address_1($params['address_1']);
        $order->set_billing_address_2($params['address_2']);
        $order->set_billing_city($params['city']);
        $order->set_billing_state($params['state']);
        $order->set_billing_postcode($params['postcode']);
        $order->set_billing_country($params['country']);

        $order->set_shipping_first_name($params['first_name']);
        $order->set_shipping_last_name($params['last_name']);
        $order->set_shipping_company($params['company']);
        $order->set_shipping_address_1($params['address_1']);
        $order->set_shipping_address_2($params['address_2']);
        $order->set_shipping_city($params['city']);
        $order->set_shipping_state($params['state']);
        $order->set_shipping_postcode($params['postcode']);
        $order->set_shipping_country($params['country']);

        $order->set_customer_id(get_current_user_id());
        return $order;
    }

    /**
     * Объект заказа WooCommerce для расчёта цены за 1-у еденицу
     *
     * @param array<string, mixed> $params
     *
     * @return WC_Order
     * @throws \WC_Data_Exception
     */
    public function createWooCommerceOrderWithoutSaving(int $productId): WC_Order
    {
        $product = wc_get_product($productId);
        $order = wc_create_order(); //создаём новый заказ
        $productItem = new WC_Order_Item_Product();
        $productItem->set_product($product);
        $productItem->set_quantity(1);
        $order->add_item($productItem);
        return $order;
    }

    /**
     * Расчёт стоимости за 1-у позицию
     *
     * @param  WC_Order $order
     * @see    \WC_Abstract_Order
     * @return float
     * @throws \WC_Data_Exception
     */
    public function calculate_order_totals(WC_Order $order)
    {
        $cart_subtotal = 0;
        $cart_total = 0;
        $fee_total = 0;
        $shipping_total = 0;
        $cart_subtotal_tax = 0;
        $cart_total_tax = 0;

        foreach ($order->get_items() as $item) {
            $cart_subtotal += round($item->get_subtotal(), wc_get_price_decimals());
            $cart_total += round($item->get_total(), wc_get_price_decimals());
        }
        foreach ($order->get_shipping_methods() as $shipping) {
            $shipping_total += round($shipping->get_total(), wc_get_price_decimals());
        }

        $order->set_shipping_total($shipping_total);
        foreach ($order->get_fees() as $item) {
            $amount = $item->get_amount();

            if (0 > $amount) {
                $item->set_total($amount);
                $max_discount = round($cart_total + $fee_total + $shipping_total, wc_get_price_decimals()) * -1;

                if ($item->get_total() < $max_discount) {
                    $item->set_total($max_discount);
                }
            }

            $fee_total += $item->get_total();
        }
        foreach ($order->get_items() as $item) {
            $cart_subtotal_tax += $item->get_subtotal_tax();
            $cart_total_tax += $item->get_total_tax();
        }

        $order->set_discount_total($cart_subtotal - $cart_total);
        $order->set_discount_tax($cart_subtotal_tax - $cart_total_tax);
        $order->set_total(round($cart_total + $fee_total + $order->get_shipping_total() + $order->get_cart_tax() + $order->get_shipping_tax(), wc_get_price_decimals()));


        return $order->get_total();
    }


    /**
     * Создаёт заказ в WooCommerce
     *
     * @param array $params массив параметров аналогичный $default_params
     */
    public function set_order($params)
    {
        $order = $this->create_order($params);
        // Вызывается ниже по коду, что бы не запускать события раньше времени
        // $order->update_status($params['order_status'], $params['message_notes_order']);
        $order->calculate_totals();
        return $order->get_id();
    }


    /**
     * Сохраняет заказ в таблицу
     *
     * @param array $order
     *
     * @return int
     */
    public function save_order(array $order)
    {
        global $wpdb;

        $default_field = [
            'active'           => 1,
            'plugin_version'   => CODERUN_ONECLICKWOO_PLUGIN_VERSION,
            'status'           => 1,
            'product_id'       => null,
            'product_name'     => null,
            'product_meta'     => null,
            'product_price'    => null,
            'product_quantity' => 1,
            'form'             => null,
            'sms_log'          => null,
            'woo_order_id'     => null,
            'user_id'          => null,
        ];

        $order = array_merge($default_field, $order);
        $wpdb->insert($this->order_table, $order);
        Hooks::saveOrderToTable($wpdb->insert_id);
        if ($wpdb->last_error) {
            // todo
        }
        return $wpdb->insert_id;
    }

    public function get_order($order_id)
    {
        global $wpdb;
        $order_id = intval($order_id);
        return $wpdb->get_row("select * from {$this->order_table} where id={$order_id}", ARRAY_A);
    }

    /**
     * Вернуть заказ по ИД WooCommere заказа
     *
     * @param int $orderId
     *
     * @return OrderEntity|null
     * @throws Exception
     */
    public function findOneOrderByOrderWooCommerceId(int $orderId): ?OrderEntity
    {
        global $wpdb;

        $row = $wpdb->get_row(
            sprintf('select * from %s where woo_order_id = %s', $this->order_table, $orderId),
            ARRAY_A
        );
        if (!is_array($row)) {
            return null;
        }
        return (new CommonHydrator())->hydrateArrayToObject($row, new OrderEntity());
    }

    /**
     * Список заказов
     *
     * @return array<int, OrderEntity>
     * @throws Exception
     */
    public function getOrders(): array
    {
        global $wpdb;
        $rows = $wpdb->get_results(
            sprintf(
                'select * from %s where active = %d order by id asc',
                $this->order_table,
                1
            ),
            ARRAY_A
        );
        $result = [];
        $hydrator = new CommonHydrator();
        foreach ($rows as $row) {
            $result[] = $hydrator->hydrateArrayToObject($row, new OrderEntity());
        }
        return $result;
    }

    public function deactive_order($order_id)
    {
        global $wpdb;
        $wpdb->update($this->order_table, ['active' => 0], ['id' => $order_id]);
    }

    public function remove_order_all()
    {
        global $wpdb;
        $wpdb->query("truncate table {$this->order_table}");
    }

    public function update_status($order_id, $status)
    {
        global $wpdb;
        $wpdb->update($this->order_table, ['status' => $status], ['id' => $order_id]);
    }

    public function __clone()
    {
        throw new Exception('Forbiden instance __clone');
    }

    public function __wakeup()
    {
        throw new Exception('Forbiden instance __wakeup');
    }
}
