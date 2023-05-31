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
<script><?php echo $fields->inlineScript; ?></script>
<style><?php echo $fields->inlineStyle; ?></style>
<button
    class="single_add_to_cart_button clickBuyButton button21 button alt ld-ext-left"
    data-variation_id="<?php echo $fields->variationId; ?>"
    data-productid="<?php echo $fields->productId; ?>">
    <span> <?php echo \Coderun\BuyOneClick\Utils\Translation::translate($fields->buttonName); ?></span>
    <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
</button>
