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
<script><?php echo $fields->inlineScript; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
<style><?php echo $fields->inlineStyle; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
<button
        class="clickBuyButtonCustom button21 button alt ld-ext-left"
        href="#" data-productid="<?php echo esc_attr($fields->productId); ?>"
        data-name="<?php echo esc_attr($fields->productName); ?>"
        data-count="<?php echo esc_attr($fields->productId); ?>"
        data-price="<?php echo esc_attr($fields->productPrice); ?>"
        data-priceHtml="<?php echo esc_attr($fields->productPriceHtml); ?>">
    <span><?php echo esc_html(Coderun\BuyOneClick\Utils\Translation::translate($fields->buttonName)); ?></span>
    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
</button>
