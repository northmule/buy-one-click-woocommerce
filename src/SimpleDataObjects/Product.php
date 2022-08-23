<?php

declare(strict_types=1);

namespace Coderun\BuyOneClick\SimpleDataObjects;

use WC_Product;

/**
 * Class Product
 *
 * @package Coderun\BuyOneClick\SimpleDataObjects
 */
class Product extends DataTransferObject
{
    /**
     * Товар WooCommerce
     *
     * @var WC_Product|null
     */
    public ?WC_Product $product;
}
