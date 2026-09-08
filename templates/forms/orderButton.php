<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
/**
 * Кнопка для вызова формы заказа
 */
/** @var \Coderun\BuyOneClick\SimpleDataObjects\OrderButton $fields */
/** @var \Coderun\BuyOneClick\Templates\OrderButton $render */
?>
<script><?php echo $fields->inlineScript; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
<style><?php echo $fields->inlineStyle; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
<button
    class="single_add_to_cart_button clickBuyButton button21 button alt ld-ext-left"
    data-variation_id="<?php echo esc_attr($fields->variationId); ?>"
    data-productid="<?php echo esc_attr($fields->productId); ?>">
    <span> <?php echo esc_html(\Coderun\BuyOneClick\Utils\Translation::translate($fields->buttonName)); ?></span>
    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
</button>
