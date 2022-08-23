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
}
