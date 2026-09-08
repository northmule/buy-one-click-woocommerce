<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
declare(strict_types=1);

use Coderun\BuyOneClick\SimpleDataObjects\Product;

if (!defined('ABSPATH')) {
    exit;
}

$booc_view = [
    sprintf('<label for="quantity_product">%s</label>', __('Quantity', 'buy-one-click-woocommerce')),
];
$booc_view[] = '<div class = "quantity">';
/** @var Product $params */
if ($params->product === null) {
    $booc_view[] = '<input type="number" min="1" value="1" name="quantity_product"/>';
} else {
    if ( $params->product->is_sold_individually() ) {
        $booc_view[] = '1 <input type="hidden" name="quantity_product" value="1" />';
    } else {
        $booc_view[] = sprintf(
            '<input type="number" min="1" max="%s" value="1" name="quantity_product"/>',
            $params->product->get_max_purchase_quantity() == -1 ? '' : $params->product->get_max_purchase_quantity()
        );
    }
}
$booc_view[] = '</div>';

foreach ($booc_view as $booc_value) {
    echo wp_kses_post($booc_value);
}



