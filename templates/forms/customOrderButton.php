<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
/**
 * Кнопка для вызова формы заказа для шорткодов
 */
/** @var \Coderun\BuyOneClick\SimpleDataObjects\CustomOrderButton $fields */
/** @var \Coderun\BuyOneClick\Templates\OrderButton $render */
?>
<script><?php echo $fields->inlineScript; ?></script>
<style><?php echo $fields->inlineStyle; ?></style>
<button
        class="clickBuyButtonCustom button21 button alt ld-ext-left"
        href="#" data-productid="<?php echo $fields->productId; ?>"
        data-name="<?php echo $fields->productName ?>"
        data-count="<?php echo $fields->productId; ?>"
        data-price="<?php echo $fields->productPrice; ?>"
        data-priceHtml="<?php echo $fields->productPriceHtml; ?>">
    <span><?php echo \Coderun\BuyOneClick\Utils\Translation::translate($fields->buttonName); ?></span>
    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
</button>
