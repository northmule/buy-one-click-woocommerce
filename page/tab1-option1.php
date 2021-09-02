<?php
if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Core;
use Coderun\BuyOneClick\Help;
?>
    <h3><?php _e('General add-on settings', 'coderun-oneclickwoo'); ?> <?php echo Core::NAME_PLUGIN; ?></h3>
    <p><?php _e('Do not forget to press the save button after changing the settings in each tab.', 'coderun-oneclickwoo'); ?></p>
<?php
$buyoptions = Help::getInstance()->get_options(Core::OPTIONS_GENERAL); //Массив настроек
?>
    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields(sprintf('%s_options', Core::OPTIONS_GENERAL)); ?>
        <fieldset>
            <legend><?php _e('Are common', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Plugin operation mode', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[plugin_work_mode]" type="radio" value="0" <?php checked(Core::getInstance()->getOption('plugin_work_mode', Core::OPTIONS_GENERAL), '0', 1); ?>><?php _e('Basic operation', 'coderun-oneclickwoo'); ?></p>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[plugin_work_mode]" type="radio" value="1" <?php checked(Core::getInstance()->getOption('plugin_work_mode', Core::OPTIONS_GENERAL), '1', 1); ?>><?php _e('Add to cart mode', 'coderun-oneclickwoo'); ?></p>
                        <span class="description"><?php _e('If "Basic operation mode" is selected - The classic logic of the plug-in will work and all the settings below will be relevant.', 'coderun-oneclickwoo'); ?><br>
                        <?php _e('If "Add to cart mode" is selected - When you click on the button, the product enters the cart and is redirected to the checkout page.', 'coderun-oneclickwoo'); ?>
                    </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Enable button display?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[enable_button]" <?php
                        if (isset($buyoptions['enable_button'])) {
                            checked($buyoptions['enable_button'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Enable or disable the display of the quick order button on the site. The tick is - the button is shown', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Enable the display of the shortcode button?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[enable_button_shortcod]" <?php
                        if (isset($buyoptions['enable_button_shortcod'])) {
                            checked($buyoptions['enable_button_shortcod'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Enable or disable the display of the quick order button via shortcode. The tick is - the button is shown', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Name of the button on the site', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[namebutton]" value="<?php
                        if (isset($buyoptions['namebutton'])) {
                            echo $buyoptions['namebutton'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Buy in one click"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Position of the button in the card', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <select name="<?php echo Core::OPTIONS_GENERAL; ?>[positionbutton]">
                            <option value="woocommerce_simple_add_to_cart" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_simple_add_to_cart', true);
                            }
                            ?>><?php _e('Above the quantity button', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_product_description_heading" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_product_description_heading', true);
                            }
                            ?>><?php _e('In the item description tab', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_before_single_product" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_before_single_product', true);
                            }
                            ?>><?php _e('Above the product image', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_before_single_product_summary" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_before_single_product_summary', true);
                            }
                            ?>><?php _e('Above full product information', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_single_product_summary" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_after_single_product_summary', true);
                            }
                            ?>><?php _e('Under the full information about the product', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_single_product_summary" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_single_product_summary', true);
                            }
                            ?>><?php _e('Under the price', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_add_to_cart_button" <?php
                            if (isset($buyoptions['positionbutton'])) {
                                selected($buyoptions['positionbutton'], 'woocommerce_after_add_to_cart_button', true);
                            }
                            ?>><?php _e('woocommerce_after_add_to_cart_button', 'coderun-oneclickwoo'); ?></option>
                        </select>
                        <span class="description"><?php _e('The place where the button will be located in the item card', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Position of the button in the card for products that are not available', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <select name="<?php echo Core::OPTIONS_GENERAL; ?>[positionbutton_out_stock]">
                            <option value="-1" <?php
                            if (isset($buyoptions['positionbutton_out_stock'])) {
                                selected($buyoptions['positionbutton_out_stock'], '-1', true);
                            }
                            ?>><?php _e('Not show', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_get_stock_html" <?php
                            if (isset($buyoptions['positionbutton_out_stock'])) {
                                selected($buyoptions['positionbutton_out_stock'], 'woocommerce_get_stock_html', true);
                            }
                            ?>><?php _e('woocommerce_get_stock_html', 'coderun-oneclickwoo'); ?></option>

                        </select>
                        <span class="description"><?php _e('The place where the button will be located in the item card if the item is not in stock. Only for the main button position in woocommerce_after_add_to_cart_button', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Write orders to the Woocommerce table', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[add_tableorder_woo]" <?php
                        if (isset($buyoptions['add_tableorder_woo'])) {
                            checked($buyoptions['add_tableorder_woo'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Setting the checkbox will enable the mechanism when the orders will get not only to the table of the plug-in, but also to the menu "Orders" - Woocommerce', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Enable button display in categories', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[enable_button_category]" <?php
                        if (isset($buyoptions['enable_button_category'])) {
                            checked($buyoptions['enable_button_category'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('The option enables or disables the display of the order button in one click in product categories. You can select a display position further.', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Position of the button in the category', 'coderun-oneclickwoo'); ?></th>
                    <td>

                        <select name="buyoptions[positionbutton_category]">
                            <option value="woocommerce_before_shop_loop_item_title" <?php
                            if (isset($buyoptions['positionbutton_category'])) {
                                selected($buyoptions['positionbutton_category'], 'woocommerce_before_shop_loop_item_title', true);
                            }
                            ?>><?php _e('Over the goods', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_shop_loop_item_title" <?php
                            if (isset($buyoptions['positionbutton_category'])) {
                                selected($buyoptions['positionbutton_category'], 'woocommerce_after_shop_loop_item_title', true);
                            }
                            ?>><?php _e('Under the name of the product to the price', 'coderun-oneclickwoo'); ?></option>
                            <option value="woocommerce_after_shop_loop_item" <?php
                            if (isset($buyoptions['positionbutton_category'])) {
                                selected($buyoptions['positionbutton_category'], 'woocommerce_after_shop_loop_item', true);
                            }
                            ?>><?php _e('Under the goods', 'coderun-oneclickwoo'); ?></option>

                        </select>
                        <span class="description"><?php _e('The place where the button will be located in the category of goods', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php _e('Information on the order form', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Show product information?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[infotovar_chek]" <?php
                        if (isset($buyoptions['infotovar_chek'])) {
                            checked($buyoptions['infotovar_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Will the product information be displayed in a modal window or not? The tick is worth it - will be displayed', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Ask for full name', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[fio_chek]" <?php
                        if (isset($buyoptions['fio_chek'])) {
                            checked($buyoptions['fio_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Does the buyer have to enter his name? A check mark is to offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Ask phone', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[fon_chek]" <?php
                        if (isset($buyoptions['fon_chek'])) {
                            checked($buyoptions['fon_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Does the buyer have to enter his phone? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Ask for Email', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[email_chek]" <?php
                        if (isset($buyoptions['email_chek'])) {
                            checked($buyoptions['email_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Does the buyer have to enter their email? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Additional Information Field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[dopik_chek]" <?php
                        if (isset($buyoptions['dopik_chek'])) {
                            checked($buyoptions['dopik_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Does the buyer have to enter additional information? Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Quantity', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[add_quantity_form]" <?php
                        if (isset($buyoptions['add_quantity_form'])) {
                            checked($buyoptions['add_quantity_form'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Add a quantity input form', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('File upload field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[upload_input_file_chek]" <?php
                        if (isset($buyoptions['upload_input_file_chek'])) {
                            checked($buyoptions['upload_input_file_chek'], 'on', 1);
                        }
                        ?>/>
                        <span class="description"><?php _e('Add the ability to attach a file to the quick order form. Tick is worth - offer', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php _e('Description of form fields', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?php _e('Name field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[fio_descript]" value="<?php
                        if (isset($buyoptions['fio_descript'])) {
                            echo $buyoptions['fio_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Your name?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php _e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[fio_verifi]" <?php
                        if (isset($buyoptions['fio_verifi'])) {
                            checked($buyoptions['fio_verifi'], 'on', 1);
                        }
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Telephone box', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[fon_descript]" value="<?php
                        if (isset($buyoptions['fon_descript'])) {
                            echo $buyoptions['fon_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Your phone?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php _e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[fon_verifi]" <?php
                        if (isset($buyoptions['fon_verifi'])) {
                            checked($buyoptions['fon_verifi'], 'on', 1);
                        }
                        ?>/>

                    </td>
                    <th scope="row"><?php _e('Format hint', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="input" name="<?php echo Core::OPTIONS_GENERAL; ?>[fon_format]" value="<?php
                        if (isset($buyoptions['fon_format'])) {
                            echo $buyoptions['fon_format'];
                        }
                        ?>" />
                        <span class="description"><?php _e('for example', 'coderun-oneclickwoo'); ?> "+7 XXX XXX XX XX"</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Phone number entry format', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[fon_format_input]" value="<?php
                        if (isset($buyoptions['fon_format_input'])) {
                            echo $buyoptions['fon_format_input'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For input formatting used JS plugin jquery.maskedinput. Examples:', 'coderun-oneclickwoo'); ?> 99-9999999, 999-99-9999,(999) 999-9999, +7(999)-999-99-99 ...</span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Email field', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[email_descript]" value="<?php
                        if (isset($buyoptions['email_descript'])) {
                            echo $buyoptions['email_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Your email?"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php _e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[email_verifi]" <?php
                        if (isset($buyoptions['email_verifi'])) {
                            checked($buyoptions['email_verifi'], 'on', 1);
                        }
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Field "Advanced"', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[dopik_descript]" value="<?php
                        if (isset($buyoptions['dopik_descript'])) {
                            echo $buyoptions['dopik_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Delivery Address"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php _e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[dopik_verifi]" <?php
                        if (isset($buyoptions['dopik_verifi'])) {
                            checked($buyoptions['dopik_verifi'], 'on', 1);
                        }
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Field "Upload file"', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[upload_input_file_descript]" value="<?php
                        if (isset($buyoptions['upload_input_file_descript'])) {
                            echo $buyoptions['upload_input_file_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Upload file"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                    <th scope="row"><?php _e('Obligatory field?', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[upload_input_file_verifi]" <?php
                        if (isset($buyoptions['upload_input_file_verifi'])) {
                            checked($buyoptions['upload_input_file_verifi'], 'on', 1);
                        }
                        ?>/>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Name of the button in the form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[butform_descript]" value="<?php
                        if (isset($buyoptions['butform_descript'])) {
                            echo $buyoptions['butform_descript'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Order"', 'coderun-oneclickwoo'); ?></span>
                    </td>

                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php _e('Behavior and dependencies on some WooCommerce options', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <td><?php _e('Actions related to setting "Balance Status" in WooCommerce', 'coderun-oneclickwoo'); ?></td>
                    <td>
                        <?php _e('Enable this option?', 'coderun-oneclickwoo'); ?>
                        <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[woo_stock_status_enable]" <?php
                        if (isset($buyoptions['woo_stock_status_enable'])) {
                            checked($buyoptions['woo_stock_status_enable'], 'on', 1);
                        }
                        ?>>
                    </td>
                    <th scope="row"><?php _e('Text on the button if the product is not in stock', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[woo_stock_status_button_text]" value="<?php
                        if (isset($buyoptions['woo_stock_status_button_text'])) {
                            echo $buyoptions['woo_stock_status_button_text'];
                        }
                        ?>" />
                        <span class="description"><?php _e('For example, "Make a pre-order"', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php _e('Actions and notifications', 'coderun-oneclickwoo'); ?></legend>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Message in the form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[success]" value="<?php
                        if (isset($buyoptions['success'])) {
                            echo $buyoptions['success'];
                        }
                        ?>" />
                        <span class="description"><?php _e('Message about the successful registration of the order. For example: "Thanks for the order!". A message appears in the "One Click" order form after the user has clicked the order confirmation button. The message should be short.', 'coderun-oneclickwoo'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('What to do after pressing the button', 'coderun-oneclickwoo'); ?> <?php
                        if (isset($buyoptions['butform_descript'])) {
                            echo $buyoptions['butform_descript'];
                        }
                        ?></th>
                    <td>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="1" <?php checked($buyoptions['success_action'], '1', 1); ?>> <?php _e('Nothing to do', 'coderun-oneclickwoo'); ?></p><span class="description"><?php _e('The window will remain open, the user will see the message above.', 'coderun-oneclickwoo'); ?></span>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="2" <?php checked($buyoptions['success_action'], '2', 1); ?>> <?php _e('Close through', 'coderun-oneclickwoo'); ?> <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action_close]" maxlength="6" pattern="[0-9]*" size="5"  value="<?php
                            if (isset($buyoptions['success_action_close'])) {
                                echo $buyoptions['success_action_close'];
                            }
                            ?>" /><?php _e('ms', 'coderun-oneclickwoo'); ?>.</p><span class="description"><?php _e('For example: "2000". The user will see the message above and the form will be closed after the specified time.', 'coderun-oneclickwoo'); ?></span>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="3" <?php checked($buyoptions['success_action'], '3', 1); ?>> <?php _e('Show message (html available)', 'coderun-oneclickwoo'); ?></p>  <textarea cols="20" rows="4" name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action_message]"><?php
                            if (isset($buyoptions['success_action_message'])) {
                                echo $buyoptions['success_action_message'];
                            }
                            ?></textarea>
                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="4" <?php checked($buyoptions['success_action'], '4', 1); ?>> <?php _e('Redirect to page', 'coderun-oneclickwoo'); ?> <input type="text" name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action_redirect]" value="<?php
                            if (isset($buyoptions['success_action_redirect'])) {
                                echo $buyoptions['success_action_redirect'];
                            }
                            ?>" />


                        </p><span class="description"><?php _e('Example', 'coderun-oneclickwoo'); ?>: <a href="https://coderun.ru">"https://coderun.ru"</a>. <?php _e('The user will see the message and go to the specified page.', 'coderun-oneclickwoo'); ?></span>

                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="5" <?php checked($buyoptions['success_action'], '5', 1); ?>>
                            <?php _e('Send to the order page WooCommerce', 'coderun-oneclickwoo'); ?></p>
                        <span class="description"><?php _e('The buyer will be redirected to the WooCommerce completed order page. It only works if orders are placed in the WooCommerce table', 'coderun-oneclickwoo'); ?></span>


                        <p><input name="<?php echo Core::OPTIONS_GENERAL; ?>[success_action]" type="radio" value="6" <?php checked($buyoptions['success_action'], '6', 1); ?>>
                            <?php _e('Send to the order payment page', 'coderun-oneclickwoo'); ?></p>
                        <span class="description">
                            <?php _e('The buyer will be redirected to the order payment page. This option only works if orders are placed in the WooCommerce table.', 'coderun-oneclickwoo'); ?>
                            <?php _e('Please note that in this case, the order will automatically switch to the "Waiting for payment" status."', 'coderun-oneclickwoo') ?>
                        </span>

                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php _e('Quick order forms - styles', 'coderun-oneclickwoo'); ?></legend>
            <p>№1. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="1" <?php checked($buyoptions['form_style_color'], '1', 1); ?>> <?php _e('Basic', 'coderun-oneclickwoo'); ?></p>
            <p>№2. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="2" <?php checked($buyoptions['form_style_color'], '2', 1); ?>> <?php _e('Blue', 'coderun-oneclickwoo'); ?></p>
            <p>№3. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="3" <?php checked($buyoptions['form_style_color'], '3', 1); ?>> <?php _e('Red', 'coderun-oneclickwoo'); ?></p>
            <p>№4. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="4" <?php checked($buyoptions['form_style_color'], '4', 1); ?>> <?php _e('Green', 'coderun-oneclickwoo'); ?></p>
            <p>№5. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="5" <?php checked($buyoptions['form_style_color'], '5', 1); ?>> <?php _e('Orange', 'coderun-oneclickwoo'); ?></p>
            <p>№6. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[form_style_color]" type="radio" value="6" <?php checked($buyoptions['form_style_color'], '6', 1); ?>> <?php _e('Your WordPress Theme Styles', 'coderun-oneclickwoo'); ?></p>
            <span><?php _e('If you want to change the CSS design of the form, create it in your template folder or in', 'coderun-oneclickwoo'); ?>
            wp-content/uploads/ 
            <?php _e('folder', 'coderun-oneclickwoo'); ?>
            "buy-one-click-woocommerce" 
            <?php _e('and copy files from there', 'coderun-oneclickwoo'); ?>
            "<?php _e('plugin_folder', 'coderun-oneclickwoo'); ?>/templates/"</span>
        </fieldset>
        <fieldset>
            <legend><?php _e('Other options', 'coderun-oneclickwoo'); ?></legend>
            <p><?php _e('Do not send the order form more often than: (seconds)', 'coderun-oneclickwoo'); ?>. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[time_limit_send_form]" type="number" value="<?php echo $buyoptions['time_limit_send_form']; ?>"></p>
            <p><?php _e('Message in the form when re-sending.', 'coderun-oneclickwoo'); ?>. <input name="<?php echo Core::OPTIONS_GENERAL; ?>[time_limit_message]" type="txt" value="<?php echo $buyoptions['time_limit_message'] ? $buyoptions['time_limit_message'] : ''; ?>"></p>
            <span><?php _e('The default is 10 seconds. This means if the client will click the button to send the order from the form more often, the order will not be duplicated.', 'coderun-oneclickwoo'); ?>.</span>
            <p><?php _e('Consent to the processing of personal data', 'coderun-oneclickwoo'); ?>: <?php _e('Enable?', 'coderun-oneclickwoo') ?>
                <input type="checkbox" name="<?php echo Core::OPTIONS_GENERAL; ?>[conset_personal_data_enabled]" <?php
                if (isset($buyoptions['conset_personal_data_enabled'])) {
                    checked($buyoptions['conset_personal_data_enabled'], 'on', 1);
                }
                ?>> <?php _e('Text (can HTML)', 'coderun-oneclickwoo') ?> <input name="<?php echo Core::OPTIONS_GENERAL; ?>[conset_personal_data_text]" size="80" type="txt" value="<?php esc_html_e(Core::getInstance()->getOption('conset_personal_data_text', Core::OPTIONS_GENERAL)); ?>">

            </p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Enable recaptcha in the order form', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p>
                            <?php _e('Do not use in the order form','coderun-oneclickwoo'); ?>
                            <input
                                    name="<?php echo Core::OPTIONS_GENERAL; ?>[recaptcha_order_form]"
                                    type="radio"
                                    value="0"
                                <?php checked(Core::getInstance()->getOption('recaptcha_order_form', Core::OPTIONS_GENERAL), 0, 1); ?>
                            >
                        </p>
                        <?php foreach (\Coderun\BuyOneClick\ReCaptcha::getInstance()->isSupported() as $key_recapcha=>$item) { ?>
                            <p>
                                <?php _e('I use a plugin: ', 'coderun-oneclickwoo'); ?><a target="_blank" href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a>
                                <input
                                        name="<?php echo Core::OPTIONS_GENERAL; ?>[recaptcha_order_form]"
                                        type="radio"
                                        value="<?php echo $key_recapcha; ?>"
                                    <?php checked(Core::getInstance()->getOption('recaptcha_order_form', Core::OPTIONS_GENERAL), $key_recapcha, 1); ?>
                                >  -  <?php _e('Tested with the "I am not a robot" captcha in V2, but it may also work with other options','coderun-oneclickwoo'); ?>
                            </p>


                            <p>
                                <span class="description"><?php _e('Includes support for anti-spam forms based on third-party plugins. Be careful, as the functionality of third-party plugins may not be predictable.', 'coderun-oneclickwoo'); ?></span>

                            </p>
                        <?php } ?>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Embed form styles in html', 'coderun-oneclickwoo'); ?></th>
                    <td>
                        <p>
                            <input
                                    name="<?php echo Core::OPTIONS_GENERAL; ?>[style_insert_html]"
                                    type="checkbox"
                                    value="on"
                                <?php checked(Core::getInstance()->getOption('style_insert_html', 'buyoptions', ''), 'on', 1); ?>
                            >  -  <?php _e('The styles will be added to the html page','coderun-oneclickwoo'); ?>
                        </p>
                        <p>
                            <span class="description"><?php _e('This option is not needed in 99% of cases. When enabled, all plugin styles are embedded in the html page content. You don\'t need to turn it on just like that.', 'coderun-oneclickwoo'); ?></span>
                        </p>

                    </td>
                </tr>
            </table>
            <?php /*
          <p>Держать HTML форму в DOM всегда?  <input type="checkbox" name="buyoptions[form_no_load_ajax]" <?php
          if (isset($buyoptions['form_no_load_ajax'])) {
          checked($buyoptions['form_no_load_ajax'], 'on', 1);
          }
          ?>> <span class="description">(По умолчанию форма подргужается по ajax)</span></p>
         */ ?>
        </fieldset>
        <input type="hidden" name="action" value="update" />
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>

    <fieldset>
        <legend><?php _e('Shortcodes and plugin hooks', 'coderun-oneclickwoo'); ?></legend>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>

                    <th class="active"><?php _e('Element', 'coderun-oneclickwoo'); ?></th>
                    <th class="success"><?php _e('Code to use', 'coderun-oneclickwoo'); ?></th>
                    <th class="warning"><?php _e('Description', 'coderun-oneclickwoo'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="active"><?php _e('Element', 'coderun-oneclickwoo'); ?></td>
                    <td class="success">[viewBuyButton id="<?php _e('Optional parameter of the real product ID','coderun-oneclickwoo'); ?>"]</td>
                    <td class="warning">
                        1. <?php _e('Button shortcode must be inserted in product output cycles, where it is possible to get the product ID. Wherein
                         The shortcode loads styles and scripts for itself, and the "buy" button will be shown if there is a tick "Enable the display of the shortcode button"', 'coderun-oneclickwoo'); ?>
                        <br>
                        2. <?php _e('If the ID of the actual WooCommerce product is specified, then you can insert the button anywhere on your site.', 'coderun-oneclickwoo') ?>
                    </td>

                </tr>
                <tr>
                    <td class="active"><?php _e('Buy button in any version', 'coderun-oneclickwoo'); ?></td>
                    <td class="success">[viewBuyButtonCustom id="<?php _e('your item code (string)', 'coderun-oneclickwoo'); ?>" name="<?php _e('your name (string)', 'coderun-oneclickwoo'); ?>" count="<?php _e('purchase quantity (number)', 'coderun-oneclickwoo'); ?>" price="<?php _e('price (number)', 'coderun-oneclickwoo'); ?>"]</td>
                    <td class="warning"><?php _e('Short code buttons with arbitrary parameters, you can set the "gag" is not tied to real goods.
                         With this shortcode, orders will be recorded only in the plug-in table, in the Woocommerce table - no entries will be made.
                         In this case, the Shortcode loads styles and scripts for itself, and the "buy" button will be shown if there is a checkmark "Enable display of the shortcode button"', 'coderun-oneclickwoo'); ?>
                    </td>

                </tr>
                <tr>
                    <td class="active"><?php _e('New order event', 'coderun-oneclickwoo'); ?></td>
                    <td class="success"><?php _e('Call', 'coderun-oneclickwoo'); ?> add_action('buy_click_new_order', '<?php _e('Your function', 'coderun-oneclickwoo'); ?>', 100, 2);</td>
                    <td class="warning"><?php _e('An event (hook) occurs when a new order is formed via a plug-in form. As of arguments takes two arrays 1st function result, 2 - data sent to the plugins order book', 'coderun-oneclickwoo'); ?>   </td>

                </tr>
                </tbody>

            </table>
        </div>
    </fieldset>
