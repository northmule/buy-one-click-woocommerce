<?php

use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Utils\Order as UtilsOrder;

if (!defined('ABSPATH')) {
    exit;
}

/** @var Core $this */
?>
    <h3><?php esc_html_e('General add-on settings', 'coderun-oneclickwoo'); ?> <?php echo esc_html(Core::NAME_PLUGIN); ?></h3>
    <p><?php esc_html_e('Do not forget to press the save button after changing the settings in each tab.', 'coderun-oneclickwoo'); ?></p>
<?php
$commonOptions = $this->getCommonOptions();
?>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields(sprintf('%s_options', Core::OPTIONS_GENERAL)); ?>
        <fieldset>
            <div class="admin-link-block top-left">
                <?php require 'admin-link-block.php'; ?>
            </div>
            <legend><?php esc_html_e('Are common', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Plugin operation mode', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[plugin_work_mode]" type="radio" value="0" <?php checked($commonOptions->getPluginWorkMode(), 0); ?>><?php esc_html_e('Basic operation', 'coderun-oneclickwoo'); ?></p>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[plugin_work_mode]" type="radio" value="1" <?php checked($commonOptions->getPluginWorkMode(), 1); ?>><?php esc_html_e('Add to cart mode', 'coderun-oneclickwoo'); ?></p>
                        <span class="description"><?php esc_html_e('If "Basic operation mode" is selected - The classic logic of the plug-in will work and all the settings below will be relevant.', 'coderun-oneclickwoo'); ?><br>
                        <?php esc_html_e('If "Add to cart mode" is selected - When you click on the button, the product enters the cart and is redirected to the checkout page.', 'coderun-oneclickwoo'); ?>
                    </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Enable button display?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[enable_button]"
                            <?php checked($commonOptions->isEnableButton()); ?> />
                        <span class="description"><?php esc_html_e('Enable or disable the display of the quick order button on the site. The tick is - the button is shown', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Enable the display of the shortcode button?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[enable_button_shortcod]"
                            <?php checked($commonOptions->isEnableButtonShortcode()); ?>/>
                        <span class="description"><?php esc_html_e('Enable or disable the display of the quick order button via shortcode. The tick is - the button is shown', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Name of the button on the site', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[namebutton]" value="<?php echo esc_html($commonOptions->getNameButton()); ?>" />
                        <span class="description"><?php esc_html_e('For example, "Buy in one click"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Position of the button in the card', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <select name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[positionbutton]">
                            <option value="woocommerce_simple_add_to_cart" <?php
                            selected($commonOptions->getPositionButton(), 'woocommerce_simple_add_to_cart');
                             ?>><?php esc_html_e('Above the quantity button', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_product_description_heading" <?php
                                selected($commonOptions->getPositionButton(), 'woocommerce_product_description_heading');
                            ?>><?php esc_html_e('In the item description tab', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_before_single_product" <?php
                                selected($commonOptions->getPositionButton(), 'woocommerce_before_single_product');
                            ?>><?php esc_html_e('Above the product image', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_before_single_product_summary" <?php
                                selected($commonOptions->getPositionButton(), 'woocommerce_before_single_product_summary');
                            ?>><?php esc_html_e('Above full product information', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_single_product_summary" <?php
                                selected($commonOptions->getPositionButton(), 'woocommerce_after_single_product_summary');
                            ?>><?php esc_html_e('Under the full information about the product', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_single_product_summary" <?php
                                 selected($commonOptions->getPositionButton(), 'woocommerce_single_product_summary');
                            ?>><?php esc_html_e('Under the price', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_add_to_cart_button" <?php
                                selected($commonOptions->getPositionButton(), 'woocommerce_after_add_to_cart_button');
                            ?>><?php esc_html_e('woocommerce_after_add_to_cart_button', 'coderun-oneclickwoo'); ?></option>
                        </select>
                        <span class="description"><?php esc_html_e('The place where the button will be located in the item card', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Position of the button in the card for products that are not available', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <select name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[positionbutton_out_stock]">
                            <option value="-1" <?php
                                selected($commonOptions->getPositionButtonOutStock(), '-1');
                            ?>><?php esc_html_e('Not show', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_get_stock_html" <?php
                                selected($commonOptions->getPositionButtonOutStock(), 'woocommerce_get_stock_html');
                            ?>><?php esc_html_e('woocommerce_get_stock_html', 'coderun-oneclickwoo'); ?></option>

                        </select>
                        <span class="description"><?php esc_html_e('The place where the button will be located in the item card if the item is not in stock. Only for the main button position in woocommerce_after_add_to_cart_button', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Write orders to the Woocommerce table', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[add_tableorder_woo]" <?php
                            checked($commonOptions->isAddAnOrderToWooCommerce());
                        ?>/>
                        <span class="description"><?php esc_html_e('Setting the checkbox will enable the mechanism when the orders will get not only to the table of the plug-in, but also to the menu "Orders" - Woocommerce', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Enable button display in categories', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[enable_button_category]" <?php
                            checked($commonOptions->isEnableButtonCategory());
                        ?>/>
                        <span class="description"><?php esc_html_e('The option enables or disables the display of the order button in one click in product categories. You can select a display position further.', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Position of the button in the category', 'coderun-oneclickwoo'); ?></th>
                    <td>

                        <select name="buyoptions[positionbutton_category]">
                            <option value="woocommerce_before_shop_loop_item_title" <?php
                                selected($commonOptions->getButtonPositionInCategory(), 'woocommerce_before_shop_loop_item_title');
                            ?>><?php esc_html_e('Over the goods', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_shop_loop_item_title" <?php
                                selected($commonOptions->getButtonPositionInCategory(), 'woocommerce_after_shop_loop_item_title');
                            ?>><?php esc_html_e('Under the name of the product to the price', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_shop_loop_item" <?php
                                selected($commonOptions->getButtonPositionInCategory(), 'woocommerce_after_shop_loop_item');
                            ?>><?php esc_html_e('Under the goods', 'coderun-oneclickwoo'); ?></option>

                        </select>
                        <span class="description"><?php esc_html_e('The place where the button will be located in the category of goods', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('WooCommerce Order Status', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <select name="buyoptions[woo_commerce_order_status]">
                            <?php
                            foreach (UtilsOrder::getListOfAvailableStatuses(__('Specify the status if necessary', 'coderun-oneclickwoo')) as $statusKey => $statusTitle) {
                                echo esc_html(sprintf(
                                    '<option %s value="%s">%s</option>',
                                    selected($commonOptions->getWooCommerceOrderStatus(), $statusKey),
                                    $statusKey,
                                    $statusTitle
                                ));
                            }
                            ?>
                        </select>
                        <span class="description"><?php esc_html_e('The created order will be transferred to this status after registration via the quick order form. It only works when integrated with WooCommerce.', 'coderun-oneclickwoo'); ?></span>

                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Information on the order form', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Show product information?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[infotovar_chek]" <?php
                            checked($commonOptions->isEnableProductInformation());
                        ?>/>
                        <span class="description"><?php esc_html_e('Will the product information be displayed in a modal window or not? The tick is worth it - will be displayed', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Ask for full name', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fio_chek]" <?php
                            checked($commonOptions->isEnableFieldWithName());
                        ?>/>
                        <span class="description"><?php esc_html_e('Does the buyer have to enter his name? A check mark is to offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Ask phone', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fon_chek]" <?php
                            checked($commonOptions->isEnableFieldWithPhone());
                        ?>/>
                        <span class="description"><?php esc_html_e('Does the buyer have to enter his phone? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Ask for Email', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[email_chek]" <?php
                            checked($commonOptions->isEnableFieldWithEmail());
                        ?>/>
                        <span class="description"><?php esc_html_e('Does the buyer have to enter their email? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Additional Information Field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[dopik_chek]" <?php
                            checked($commonOptions->isEnableFieldWithComment());
                        ?>/>
                        <span class="description"><?php esc_html_e('Does the buyer have to enter additional information? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Quantity', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[add_quantity_form]" <?php
                            checked($commonOptions->isEnableFieldWithQuantity());
                        ?>/>
                        <span class="description"><?php esc_html_e('Add a quantity input form', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('File upload field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[upload_input_file_chek]" <?php
                            checked($commonOptions->isEnableFieldWithFiles());
                        ?>/>
                        <span class="description"><?php esc_html_e('Add the ability to attach a file to the quick order form. Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Description of form fields', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Name field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fio_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldName());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Your name?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php esc_html_e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fio_verifi]" <?php
                            checked($commonOptions->isFieldNameIsRequired());
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Telephone box', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fon_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldPhone());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Your phone?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php esc_html_e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fon_verifi]" <?php
                            checked($commonOptions->isFieldPhoneIsRequired());
                        ?>/>

                    </td>
                    <th scope="row"><?php esc_html_e('Format hint', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="input" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fon_format]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldFormatPhone());
                        ?>" />
                        <span class="description"><?php esc_html_e('for example', 'coderun-oneclickwoo'); ?> "+7 XXX XXX XX XX"</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Phone number entry format', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[fon_format_input]" value="<?php
                            echo esc_html($commonOptions->getPhoneNumberInputMask());
                        ?>" />
                        <span class="description"><?php esc_html_e('For input formatting used JS plugin jquery.maskedinput. Examples:', 'coderun-oneclickwoo'); ?> 99-9999999, 999-99-9999,(999) 999-9999, +7(999)-999-99-99 ...</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Email field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[email_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldEmail());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Your email?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php esc_html_e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[email_verifi]" <?php
                            checked($commonOptions->isFieldEmailIsRequired());
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Field "Advanced"', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[dopik_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldComment());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Delivery Address"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php esc_html_e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[dopik_verifi]" <?php
                            checked($commonOptions->isFieldCommentIsRequired());
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Field "Upload file"', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[upload_input_file_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldFiles());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Upload file"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php esc_html_e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[upload_input_file_verifi]" <?php
                            checked($commonOptions->isFieldFilesIsRequired());
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Name of the button in the form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[butform_descript]" value="<?php
                            echo esc_html($commonOptions->getDescriptionForFieldOrderButton());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Order"', 'coderun-oneclickwoo'); ?></span>
                    </td>

                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Behavior and dependencies on some WooCommerce options', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <td><?php esc_html_e('Actions related to setting "Balance Status" in WooCommerce', 'coderun-oneclickwoo'); ?></td>
                    <td>
                        <?php esc_html_e('Enable this option?', 'coderun-oneclickwoo'); ?>
                        <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[woo_stock_status_enable]" <?php
                            checked($commonOptions->isEnableWorkWithRemainingItems());
                        ?>>
                    </td>
                    <th scope="row"><?php esc_html_e('Text on the button if the product is not in stock', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[woo_stock_status_button_text]" value="<?php
                            echo esc_html($commonOptions->getDescriptionOfPreOrderButton());
                        ?>" />
                        <span class="description"><?php esc_html_e('For example, "Make a pre-order"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Actions and notifications', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Message in the form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success]" value="<?php
                            echo esc_html($commonOptions->getSubmittingFormMessageSuccess());
                        ?>" />
                        <span class="description"><?php esc_html_e('Message about the successful registration of the order. For example: "Thanks for the order!". A message appears in the "One Click" order form after the user has clicked the order confirmation button. The message should be short.', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('What to do after pressing the button', 'coderun-oneclickwoo'); ?> <?php
                            echo esc_html($commonOptions->getDescriptionForFieldOrderButton());
                        ?></th>
                    <td>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="1" <?php checked($commonOptions->getActionAfterSubmittingForm(), 1); ?>> <?php esc_html_e('Nothing to do', 'coderun-oneclickwoo'); ?></p><span class="description"><?php esc_html_e('The window will remain open, the user will see the message above.', 'coderun-oneclickwoo'); ?></span>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="2" <?php checked($commonOptions->getActionAfterSubmittingForm(), 2); ?>> <?php esc_html_e('Close through', 'coderun-oneclickwoo'); ?> <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action_close]" maxlength="6" pattern="[0-9]*" size="5"  value="<?php
                                echo esc_html($commonOptions->getSecondsBeforeClosingForm());
                            ?>" /><?php esc_html_e('ms', 'coderun-oneclickwoo'); ?>.</p><span class="description"><?php esc_html_e('For example: "2000". The user will see the message above and the form will be closed after the specified time.', 'coderun-oneclickwoo'); ?></span>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="3" <?php checked($commonOptions->getActionAfterSubmittingForm(), 3); ?>> <?php esc_html_e('Show message (html available)', 'coderun-oneclickwoo'); ?></p>  <textarea cols="20" rows="4" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action_message]"><?php
                                echo esc_html($commonOptions->getMessageAfterSubmittingForm());
                            ?></textarea>
                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="4" <?php checked($commonOptions->getActionAfterSubmittingForm(), 4); ?>> <?php esc_html_e('Redirect to page', 'coderun-oneclickwoo'); ?> <input type="text" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action_redirect]" value="<?php
                                echo esc_html($commonOptions->getUrlRedirectAddress());
                            ?>" />

                        </p><span class="description"><?php esc_html_e('Example', 'coderun-oneclickwoo'); ?>: <a href="https://coderun.ru">"https://coderun.ru"</a>. <?php esc_html_e('The user will see the message and go to the specified page.', 'coderun-oneclickwoo'); ?></span>

                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="5" <?php checked($commonOptions->getActionAfterSubmittingForm(), 5); ?>>
                            <?php esc_html_e('Send to the order page WooCommerce', 'coderun-oneclickwoo'); ?></p>
                        <span class="description"><?php esc_html_e('The buyer will be redirected to the WooCommerce completed order page. It only works if orders are placed in the WooCommerce table', 'coderun-oneclickwoo'); ?></span>


                        <p><input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[success_action]" type="radio" value="6" <?php checked($commonOptions->getActionAfterSubmittingForm(), 6); ?>>
                            <?php esc_html_e('Send to the order payment page', 'coderun-oneclickwoo'); ?></p>
                        <span class="description">
                            <?php esc_html_e('The buyer will be redirected to the order payment page. This option only works if orders are placed in the WooCommerce table.', 'coderun-oneclickwoo'); ?>
                            <?php esc_html_e('Please note that in this case, the order will automatically switch to the "Waiting for payment" status."', 'coderun-oneclickwoo') ?>
                        </span>

                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Quick order forms - styles', 'coderun-oneclickwoo'); ?></legend>
            <p>№1. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="1" <?php checked($commonOptions->getFormStyle(), 1); ?>> <?php esc_html_e('Basic', 'coderun-oneclickwoo'); ?></p>
            <p>№2. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="2" <?php checked($commonOptions->getFormStyle(), 2); ?>> <?php esc_html_e('Blue', 'coderun-oneclickwoo'); ?></p>
            <p>№3. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="3" <?php checked($commonOptions->getFormStyle(), 3); ?>> <?php esc_html_e('Red', 'coderun-oneclickwoo'); ?></p>
            <p>№4. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="4" <?php checked($commonOptions->getFormStyle(), 4); ?>> <?php esc_html_e('Green', 'coderun-oneclickwoo'); ?></p>
            <p>№5. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="5" <?php checked($commonOptions->getFormStyle(), 5); ?>> <?php esc_html_e('Orange', 'coderun-oneclickwoo'); ?></p>
            <p>№6. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[form_style_color]" type="radio" value="6" <?php checked($commonOptions->getFormStyle(), 6); ?>> <?php esc_html_e('Your WordPress Theme Styles', 'coderun-oneclickwoo'); ?></p>
            <span><?php esc_html_e('If you want to change the CSS design of the form, create it in your template folder or in', 'coderun-oneclickwoo'); ?>
            wp-content/uploads/ 
            <?php esc_html_e('folder', 'coderun-oneclickwoo'); ?>
            "buy-one-click-woocommerce" 
            <?php esc_html_e('and copy files from there', 'coderun-oneclickwoo'); ?>
            "<?php esc_html_e('plugin_folder', 'coderun-oneclickwoo'); ?>/templates/"</span>
        </fieldset>
        <fieldset>
            <legend><?php esc_html_e('Other options', 'coderun-oneclickwoo'); ?></legend>
            <p><?php esc_html_e('Do not send the order form more often than: (seconds)', 'coderun-oneclickwoo'); ?>. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[time_limit_send_form]" type="number" value="<?php echo esc_html($commonOptions->getFormSubmissionLimit()); ?>"></p>
            <p><?php esc_html_e('Message in the form when re-sending.', 'coderun-oneclickwoo'); ?>. <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[time_limit_message]" type="txt" value="<?php echo esc_html($commonOptions->getFormSubmissionLimitMessage()); ?>"></p>
            <span><?php esc_html_e('The default is 10 seconds. This means if the client will click the button to send the order from the form more often, the order will not be duplicated.', 'coderun-oneclickwoo'); ?>.</span>
            <p><?php esc_html_e('Consent to the processing of personal data', 'coderun-oneclickwoo'); ?>: <?php esc_html_e('Enable?', 'coderun-oneclickwoo') ?>
                <input type="checkbox" name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[conset_personal_data_enabled]" <?php
                    checked($commonOptions->isConsentToProcessing());
                ?>> <?php esc_html_e('Text (can HTML)', 'coderun-oneclickwoo') ?> <input name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[conset_personal_data_text]" size="80" type="txt" value="<?php esc_html_e($commonOptions->getDescriptionConsentToProcessing()); ?>">

            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Enable recaptcha in the order form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p>
                            <?php esc_html_e('Do not use in the order form', 'coderun-oneclickwoo'); ?>
                            <input
                                    name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[recaptcha_order_form]"
                                    type="radio"
                                    value="0"
                                <?php checked($commonOptions->isRecaptchaEnabled(), false); ?>
                            >
                        </p>
                        <?php foreach (\Coderun\BuyOneClick\ReCaptcha::getInstance()->isSupported() as $key_recapcha=>$item) { ?>
                            <p>
                                <?php esc_html_e('I use a plugin: ', 'coderun-oneclickwoo'); ?><a target="_blank" href="<?php echo esc_html($item['url']); ?>"><?php echo esc_html($item['name']); ?></a>
                                <input
                                        name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[recaptcha_order_form]"
                                        type="radio"
                                        value="<?php echo esc_html($key_recapcha); ?>"
                                    <?php checked($commonOptions->getCaptchaProvider(), $key_recapcha); ?>
                                >  -  <?php esc_html_e('Tested with the "I am not a robot" captcha in V2, but it may also work with other options', 'coderun-oneclickwoo'); ?>
                            </p>


                            <p>
                                <span class="description"><?php esc_html_e('Includes support for anti-spam forms based on third-party plugins. Be careful, as the functionality of third-party plugins may not be predictable.', 'coderun-oneclickwoo'); ?></span>

                            </p>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Embed form styles in html', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p>
                            <input
                                    name="<?php echo esc_html(Core::OPTIONS_GENERAL); ?>[style_insert_html]"
                                    type="checkbox"
                                    value="on"
                                <?php checked($commonOptions->isStyleInsertHtml()); ?>
                            >  -  <?php esc_html_e('The styles will be added to the html page', 'coderun-oneclickwoo'); ?>
                        </p>
                        <p>
                            <span class="description"><?php esc_html_e('This option is not needed in 99% of cases. When enabled, all plugin styles are embedded in the html page content. You don\'t need to turn it on just like that.', 'coderun-oneclickwoo'); ?></span>
                        </p>

                    </td>
                </tr>
            </table>
            <div class="admin-link-block bottom-right">
                <?php require 'admin-link-block.php'; ?>
            </div>
        </fieldset>
        <input type="hidden" name="action" value="update" />
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php esc_html_e('Save Changes') ?>" />
             <input name="export_options" type="button" class="button-secondary" value="<?php esc_html_e('Exporting Settings', 'coderun-oneclickwoo') ?>" />
            <input name="import_options" type="button" class="button-secondary" value="<?php esc_html_e('Importing Settings', 'coderun-oneclickwoo') ?>" />
        </p>
        <p class="setting_message_result"></p>
    </form>
    <form method="post" id="form_settings_file_select" style="display:none">
        <input id="settings_file_select" type="file"/>
        <input type="hidden" name="action" value="buy_one_click_import_options">
    </form>
