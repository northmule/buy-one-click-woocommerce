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
        $this->verifyFrontendNonce();
        $variation_id = isset($_POST['variation_selected']) ? intval(wp_unslash($_POST['variation_selected'])) : 0;
        $variations = [];
        $quantity = 1;
        if (isset($_POST['variation_attr'])) {
            $variation_attr = wp_unslash($_POST['variation_attr']);
            $arSelectVariation = explode('&', $variation_attr);
            foreach ($arSelectVariation as $values) {
                $params = explode('=', $values);
                if (count($params) < 2) {
                    continue;
                }
                if (stripos($params[0], 'attribute_pa') !== false) {
                    $variation_slug = str_replace('attribute_pa_', '', $params[0]);
                    $variation_value = $params[1];
                    $variations[sanitize_text_field($variation_slug)] = sanitize_text_field($variation_value);
                }
                if (stripos($params[0], 'quantity') !== false) {
                    $quantity = intval($params[1]);
                }
            }
        }
        if (!function_exists('WC')) {
            echo get_home_url();
            die();
        }
        $productid = isset($_POST['productid']) ? intval(wp_unslash($_POST['productid'])) : 0;
        WC()->cart->add_to_cart($productid, $quantity, $variation_id, $variations);
        $url = get_permalink(get_option('woocommerce_checkout_page_id'));
        wp_send_json_success($url);
    }
}
