<?php
use Coderun\BuyOneClick\Utils\Translation;

if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
/**
 * Шаблон формы быстрого заказа
 */
/** @var \Coderun\BuyOneClick\SimpleDataObjects\FieldsOfOrderForm $fields */
/** @var \Coderun\BuyOneClick\Templates\QuickOrderForm $render */
$booc_commonOptions = $render->getCommonOptions();
?>
<div id="formOrderOneClick">
    <div class="overlay" title="окно"></div>
    <div class="popup">
        <div class="close_order <?php echo $fields->templateStyle ? 'button' : '' ?>">x</div>
        <form id="buyoneclick_form_order" class="b1c-form" method="post" action="#">
            <h2><?php echo Translation::translate($booc_commonOptions->getNameButton()); ?></h2>
            <?php if ($booc_commonOptions->isEnableProductInformation()) { ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr valign="top">
                            <th scope="row">
                                <span class="description"><?php esc_html_e('Name', 'buy-one-click-woocommerce'); ?></span>
                            </th>
                            <th>
                                <span class="description"><?php esc_html_e('Price', 'buy-one-click-woocommerce'); ?></span>
                            </th>
                            <?php if (!empty($fields->productImg)) { ?>
                                <th>
                                    <span class="description"><?php esc_html_e('Picture', 'buy-one-click-woocommerce'); ?></span>
                                </th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <tr valign="top">
                            <td data-label="<?php esc_html_e('Name', 'buy-one-click-woocommerce'); ?>" scope="row">
                                <span class="description"> <?php echo $fields->productName; ?></span>
                            </td>
                            <td data-label="<?php esc_html_e('Price', 'buy-one-click-woocommerce'); ?>">
                                <span class="description"><?php echo $fields->productPriceHtml; ?></span>
                            </td>
                            <?php if (!empty($fields->productImg)) { ?>
                                <td data-label="<?php esc_html_e('Picture', 'buy-one-click-woocommerce'); ?>">
                                    <span class="description"><?php echo $fields->productSrcImg; ?></span>
                                </td>
                            <?php } ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            
            <?php if ($booc_commonOptions->isEnableFieldWithName()) { ?>
                <input class="buyvalide <?php echo $fields->templateStyle ? 'input-text' : '' ?>" type="text" <?php ?> placeholder="<?php echo Translation::translate($booc_commonOptions->getDescriptionForFieldName()); ?>" name="txtname">
            <?php } ?>
            <?php if ($booc_commonOptions->isEnableFieldWithPhone()) { ?>
                <input class="buyvalide <?php echo $fields->templateStyle ? 'input-text' : '' ?> " type="tel" <?php ?> placeholder="<?php echo Translation::translate($booc_commonOptions->getDescriptionForFieldPhone()); ?>" name="txtphone">
                <p class="phoneFormat"><?php
                    if (!empty($booc_commonOptions->getDescriptionForFieldFormatPhone())) {
                        echo __('Format', 'buy-one-click-woocommerce') . ' ' . $booc_commonOptions->getDescriptionForFieldFormatPhone();
                    }
                    ?></p>
            <?php } ?>
            <?php if ($booc_commonOptions->isEnableFieldWithEmail()) { ?>
                <input class="buyvalide <?php echo $fields->templateStyle ? 'input-text' : '' ?> " type="email" <?php ?> placeholder="<?php echo Translation::translate($booc_commonOptions->getDescriptionForFieldEmail()); ?>" name="txtemail">
            <?php } ?>
            <?php if ($booc_commonOptions->isEnableFieldWithComment()) { ?>
                <textarea class="buymessage buyvalide" <?php ?> name="message" placeholder="<?php echo Translation::translate($booc_commonOptions->getDescriptionForFieldComment()); ?>" rows="2" value=""></textarea>
            <?php } ?>
            
            <?php if ($booc_commonOptions->isConsentToProcessing()) { ?>
                <p>
                    <input type="checkbox" name="conset_personal_data">
                    <?php echo Translation::translate($booc_commonOptions->getDescriptionConsentToProcessing()); ?>
                </p>
            <?php } ?>
            
            <?php echo $fields->formWithQuantity; ?>
            
            <?php wp_nonce_field('one_click_send', '_coderun_nonce'); ?>
            <input type="hidden" name="nametovar" value="<?php echo $fields->productName; ?>" />
            <input type="hidden" name="pricetovar" value="<?php echo $fields->productPrice; ?>" />
            <input type="hidden" name="idtovar" value="<?php echo $fields->productId; ?>" />
            <input type="hidden" name="action" value="coderun_send_form_buy_one_click_buybuttonform" />
            <input type="hidden" name="custom" value="<?php echo $fields->shortCode; ?>"/>
            
            <?php
            //Форма файлов
            echo $fields->formWithFiles;
            
            if ($booc_commonOptions->isRecaptchaEnabled()) {
                Coderun\BuyOneClick\ReCaptcha::getInstance()->view($booc_commonOptions->getCaptchaProvider());
            }

            ?>
            
            <p class="form-message-result"></p>
            
            <button
                type="submit"
                class="button alt buyButtonOkForm ld-ext-left"
                name="btnsend">
                <span> <?php echo Translation::translate($booc_commonOptions->getDescriptionForFieldOrderButton()); ?></span>
                <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
            </button>
        
        </form>
    
    </div>
    <?php
    if ($booc_commonOptions->getActionAfterSubmittingForm() > 0) {
        ?>
        <div class = "overlay_message" title = "<?php esc_html_e('Notification', 'buy-one-click-woocommerce'); ?>"></div>
        <div class = "popummessage">
            <div class="close_message">x</div>
            <?php echo Translation::translate($booc_commonOptions->getMessageAfterSubmittingForm());  ?>
        </div>
        <?php
    }
    ?>
</div>