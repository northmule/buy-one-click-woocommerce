<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Utils;

use Throwable;
use WC_Product;

use function class_exists;
use function is_array;
use function method_exists;
use function floatval;

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
     * Цена товара с учётом сторонних дополнений
     *
     * @param     $product
     * @param int $quantity
     *
     * @return mixed|string
     */
    public static function getProductPrice($product, $quantity = 1): float
    {
        if (!$product instanceof WC_Product) {
            return floatval(0);
        }
        $prices = [];
        try {
            // plugin - Woo Discount Rules
            if (class_exists('\Wdr\App\Controllers\ManageDiscount')
                && method_exists('Wdr\App\Controllers\ManageDiscount', 'calculateInitialAndDiscountedPrice')) {
                $prices = \Wdr\App\Controllers\ManageDiscount::calculateInitialAndDiscountedPrice($product, $quantity);
            }
        } catch (Throwable $e) {
         // ignore errors
        }

        if (is_array($prices) && !empty($prices['discounted_price'])) {
            return floatval($prices['discounted_price']);
        }
    
        return floatval($product->get_price());
    }
}
