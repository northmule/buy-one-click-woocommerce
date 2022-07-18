<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\Constant\Options;

/**
 * Class ButtonPosition
 *
 * @package Coderun\BuyOneClick\Constant\Options
 */
class ButtonPosition
{
    /**
     * Положение для карточки товара
     *
     * @var string
     */
    public const WOOCOMMERCE_AFTER_ADD_TO_CART_BUTTON = 'woocommerce_after_add_to_cart_button';
    /**
     * Положение для категории товаров
     *
     * @var string
     */
    public const WOOCOMMERCE_AFTER_SHOP_LOOP_ITEM = 'woocommerce_after_shop_loop_item';
}
