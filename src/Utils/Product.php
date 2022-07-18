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
     * Собираем информацию о товаре, для формы
     * Этот вариант кнопки расположен в карточке товара или в категории и подразумевает заказ 1й еденицы
     * товара (покупка в один клик, минуя корзину)
     *
     * @return array 'article' - код товара, 'name'-наименование,'imageurl'-url картинки,'amount'-цена,
     * 'quantity' -количество
     */
    public static function getProductParam(int $productId): array
    {
        $product = wc_get_product($productId); // Класс Woo для работы с товаром
        $imageParam = [];
        if (method_exists($product, 'get_image_id')) {
            $imageParam = wp_get_attachment_image_src($product->get_image_id()); //Урл картинки товара
        }
        return [
            'article'  => $productId,
            'name'     => $product->get_name(),
            'imageurl' => $imageParam[0] ?? '',
            'amount'   => $product->get_price(),
            'quantity' => 1,
        ];
    }

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
