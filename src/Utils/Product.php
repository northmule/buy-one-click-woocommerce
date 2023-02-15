<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use WC_Product;

/**
 * Class Product
 *
 * @package Coderun\BuyOneClick\Utils
 */
class Product
{
    /**
     * Вернёт ИД товара или 0
     *
     * @return int
     */
    public static function getProductId(): int
    {
        global $product;

        $productId = 0;
        if ($product instanceof WC_Product) {
            $productId = $product->get_id();
        }

        return $productId;
    }
    
    /**
     * @param     $product
     * @param int $quantity
     *
     * @return mixed|string
     */
    public static function getProductPrice($product, $quantity = 1)
    {
        if (!$product instanceof \WC_Product) {
            return '';
        }
        $prices = [];
        if (class_exists('\Wdr\App\Controllers\ManageDiscount') && method_exists('Wdr\App\Controllers\ManageDiscount', 'calculateInitialAndDiscountedPrice')) {
            $prices = \Wdr\App\Controllers\ManageDiscount::calculateInitialAndDiscountedPrice($product, $quantity);
        }
        if (is_array($prices) && isset($prices['discounted_price'])) {
            return $prices['discounted_price'];
        }
    
        return $product->get_price() ?? '';
    }
}
