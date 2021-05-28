<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
/**
 * Шаблон формы быстрого заказа
 */
/** @var array $options */
/** @var array $field */
?>
<div id="formOrderOneClick">
    <div class="overlay" title="окно"></div>
    <div class="popup">
        <div class="close_order <?php echo ($field['is_template_style'] ? 'button' : '') ?>">x</div>
        <form id="buyoneclick_form_order" class="b1c-form" method="post" action="#">
            <h2><?php echo $options['buyoptions']['namebutton']; ?></h2>
            <?php if (!empty($options['buyoptions']['infotovar_chek'])) { ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr valign="top">
                            <th scope="row">
                                <span class="description"><?php _e('Name', 'coderun-oneclickwoo'); ?></span>
                            </th>
                            <th>
                                <span class="description"><?php _e('Price', 'coderun-oneclickwoo'); ?></span>
                            </th>
                            <?php if (!empty($field['product_img'])) { ?>
                                <th>
                                    <span class="description"><?php _e('Picture', 'coderun-oneclickwoo'); ?></span>
                                </th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <tr valign="top">
                            <td data-label="<?php _e('Name', 'coderun-oneclickwoo'); ?>" scope="row">
                                <span class="description"> <?php echo $field['product_name']; ?></span>
                            </td>
                            <td data-label="<?php _e('Price', 'coderun-oneclickwoo'); ?>">
                                <span class="description"><?php echo $field['product_price']; ?></span>
                            </td>
                            <?php if (!empty($field['product_img'])) { ?>
                                <td data-label="<?php _e('Picture', 'coderun-oneclickwoo'); ?>">
                                    <span class="description"><?php echo $field['product_src_img']; ?></span>
                                </td>
                            <?php } ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            
            <?php if (!empty($options['buyoptions']['fio_chek'])) { ?>
                <input class="buyvalide <?php echo ($field['is_template_style'] ? 'input-text' : '') ?>" type="text" <?php ?> placeholder="<?php echo $options['buyoptions']['fio_descript']; ?>" name="txtname">
            <?php } ?>
            <?php if (!empty($options['buyoptions']['fon_chek'])) { ?>
                <input class="buyvalide <?php echo ($field['is_template_style'] ? 'input-text' : '') ?> " type="tel" <?php ?> placeholder="<?php echo $options['buyoptions']['fon_descript']; ?>" name="txtphone">
                <p class="phoneFormat"><?php
                    if (!empty($options['buyoptions']['fon_format'])) {
                        echo __('Format', 'coderun-oneclickwoo') . ' ' . $options['buyoptions']['fon_format'];
                    }
                    ?></p>
            <?php } ?>
            <?php if (!empty($options['buyoptions']['email_chek'])) { ?>
                <input class="buyvalide <?php echo ($field['is_template_style'] ? 'input-text' : '') ?> " type="email" <?php ?> placeholder="<?php echo $options['buyoptions']['email_descript']; ?>" name="txtemail">
            <?php } ?>
            <?php if (!empty($options['buyoptions']['dopik_chek'])) { ?>
                <textarea class="buymessage buyvalide" <?php ?> name="message" placeholder="<?php echo $options['buyoptions']['dopik_descript']; ?>" rows="2" value=""></textarea>
            <?php } ?>
            
            <?php if (!empty($options['buyoptions']['conset_personal_data_enabled'])) { ?>
                <p>
                    <input type="checkbox" name="conset_personal_data">
                    <?php echo $options['buyoptions']['conset_personal_data_text']; ?>
                </p>
            <?php } ?>
            
            <?php echo $field['html_form_quantity']; ?>
            
            <?php wp_nonce_field('one_click_send','_coderun_nonce'); ?>
            <input type="hidden" name="nametovar" value="<?php echo $field['product_name']; ?>" />
            <input type="hidden" name="pricetovar" value="<?php echo $field['product_price']; ?>" />
            <input type="hidden" name="idtovar" value="<?php echo $field['product_id']; ?>" />
            <input type="hidden" name="action" value="coderun_send_form_buy_one_click_buybuttonform" />
            <input type="hidden" name="custom" value="<?php echo $field['form_custom']; ?>"/>
            
            <?php
            //Форма файлов
            echo $field['html_form_file_upload'];
            
            if(!empty($options['buyoptions']['recaptcha_order_form'])) {
                Coderun\BuyOneClick\ReCaptcha::getInstance()->view($options['buyoptions']['recaptcha_order_form']);
            }
            
            ?>
            
            <p class="form-message-result"></p>
            
            <button
                type="submit"
                class="button alt buyButtonOkForm ld-ext-left"
                name="btnsend">
                <span> <?php echo $options['buyoptions']['butform_descript']; ?></span>
                <div style="font-size:14px" class="ld ld-ring ld-cycle"></div>
            </button>
        
        </form>
    
    </div>
    <?php
    if (!empty($options['buyoptions']['success_action'])) {
        ?>
        <div class = "overlay_message" title = "<?php _e('Notification', 'coderun-oneclickwoo'); ?>"></div>
        <div class = "popummessage">
            <div class="close_message">x</div>
            <?php echo $options['buyoptions']['success_action_message']; ?>
        </div>
        <?php
    }
    ?>
</div>