<?php

declare(strict_types=1);

use Coderun\BuyOneClick\SimpleDataObjects\Product;

if (!defined('ABSPATH')) {
    exit;
}

$view = [
    sprintf('<label for="quantity_product">%s</label>', __('Quantity', 'coderun-oneclickwoo')),
];
$view[] = '<div class = "quantity">';
/** @var Product $params */
if ($params->product === null) {
    $view[] = '<input type="number" min="1" value="1" name="quantity_product"/>';
} else {
    if ( $params->product->is_sold_individually() ) {
        $view[] = '1 <input type="hidden" name="quantity_product" value="1" />';
    } else {
        $view[] = sprintf(
            '<input type="number" min="1" max="%s" value="1" name="quantity_product"/>',
            $params->product->get_max_purchase_quantity() == -1 ? '' : $params->product->get_max_purchase_quantity()
        );
    }
}
$view[] = '</div>';

foreach ($view as $value) {
    echo $value;
}



