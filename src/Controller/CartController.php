<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Controller;

use function add_action;
use function intval;

/**
 * Class CartController
 *
 * @package Coderun\BuyOneClick\Controller
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        add_action(
            'wp_ajax_buy_coderun_add_to_cart',
            [$this, 'addToCart']
        );

        add_action(
            'wp_ajax_nopriv_buy_coderun_add_to_cart',
            [$this, 'addToCart']
        );
    }

    /**
     * Добавляет товар в корзину
     *
     * @return void
     * @throws \Exception
     */
    public function addToCart(): void
    {
        $variation_id = intval($_POST['variation_selected'] ?? 0);
        $variations = [];
        $quantity = 1;
        if (isset($_POST['variation_attr'])) {
            $variation_attr = $_POST['variation_attr'];
            $arSelectVariation = explode('&', $variation_attr);
            foreach ($arSelectVariation as $values) {
                $params = explode('=', $values);
                if (stripos($params[0], 'attribute_pa') !== false) {
                    $variation_slug = str_replace('attribute_pa_', '', $params[0]);
                    $variation_value = $params[1];
                    $variations[$variation_slug] = $variation_value;
                }
                if (stripos($params[0], 'quantity') !== false) {
                    $quantity = $params[1];
                }
            }
        }
        if (!function_exists('WC')) {
            echo get_home_url();
            die();
        }
        $productid = intval($_POST['productid']);
        WC()->cart->add_to_cart($productid, $quantity, $variation_id, $variations);
        $url = get_permalink(get_option('woocommerce_checkout_page_id'));
        wp_send_json_success($url);
    }
}
