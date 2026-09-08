<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;

/** @var Core $this */

$booc_notification_options = $this->getNotificationOptions();
?>
<h3><?php esc_html_e('Methods and notification settings for the client', 'buy-one-click-woocommerce'); ?>  <?php echo Core::NAME_PLUGIN; ?></h3>

<form method="post" action="options.php">
    <fieldset>
        <legend><?php esc_html_e('Setting E-mail Notifications', 'buy-one-click-woocommerce'); ?></legend>
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields(sprintf('%s_options', Core::OPTIONS_NOTIFICATIONS)); ?>
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php esc_html_e('Name from', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[namemag]" value="<?php
                    echo $booc_notification_options->getOrganizationName();
                    ?>" />
                    <span class="description"><?php esc_html_e('Example', 'buy-one-click-woocommerce'); ?> "<?php bloginfo('name'); ?>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Email From', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[emailfrom]" value="<?php
                    echo esc_attr($booc_notification_options->getEmailFromWhom());
                    ?>" />
                    <span class="description"><?php esc_html_e('Example', 'buy-one-click-woocommerce'); ?> "izm@zixn.ru" </span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Email copy', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[emailbbc]" value="<?php
                    echo esc_attr($booc_notification_options->getEmailBcc());
                    ?>" />
                    <span class="description"><?php esc_html_e('This email will receive a copy of the order messages. Through the sign "," you can specify multiple Email. Example:', 'buy-one-click-woocommerce'); ?>
                        shop@mail.ru, jora@mail.ru, barak-obama@google.com</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e('Optional Options', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <span class="description"><?php esc_html_e('Tick off the boxes to be sent.', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e('Ordering information', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[infozakaz_chek]" <?php
                    checked($booc_notification_options->isEnableOrderInformation());
                    ?>/>
                    <span class="description"><?php esc_html_e('Send order data to customer. A tick is worth sending!', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e('Random information', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[dopiczakaz_chek]" <?php
                    checked($booc_notification_options->isEnableAdditionalField());
                    ?>/>
                    <span class="description"><?php esc_html_e('Send additional data. You can specify any text.', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Links to files', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[links_to_files]" <?php
                    checked($booc_notification_options->isEnableFileLinks());
                    ?>/>
                    <span class="description"><?php esc_html_e('Send links to downloaded files in emails?', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php esc_html_e('Random information', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <textarea cols="50" rows="10" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[dopiczakaz]"><?php
                        echo $booc_notification_options->getAdditionalFieldMessage();
                        ?></textarea>
                    <span class="description"><?php esc_html_e('Arbitrary information, such as contacts or a wish. It is possible to specify the HTML tag', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>

        </table>
    </fieldset>
    <fieldset>
        <legend><?php esc_html_e('Setting SMS notifications', 'buy-one-click-woocommerce'); ?></legend>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Enable SMS Customer Notifications', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_enable_smsc]" <?php
                    checked($booc_notification_options->isEnableSendingSmsToClient());
                    ?>/>
                    <span class="description"><?php esc_html_e('Enable SMS notifications for client via service', 'buy-one-click-woocommerce'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>" <?php esc_html_e('for quick order button. If ticked - SMS notifications will work', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Enable SMS Notifications Seller', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_enable_smsc_saller]" <?php
                    checked($booc_notification_options->isEnableSendingSmsToSeller());
                    ?>/>
                    <span class="description"><?php esc_html_e('Enable SMS notifications for the seller through the service - If checked, SMS notifications will work.', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e('Online Store Owners Phone', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_phone_saller]" value="<?php
                    echo $booc_notification_options->getSellerPhoneNumber();
                    ?>" />
                    <span class="description"><?php esc_html_e('Notifications on new orders will be sent to this phone number. Works when the daw is set higher', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Login', 'buy-one-click-woocommerce'); ?> smsc</th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_login]" value="<?php
                    echo $booc_notification_options->getSmsServiceLogin()
                    ?>" />
                    <span class="description"><?php _e('Your login from the service', 'buy-one-click-woocommerce'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Password', 'buy-one-click-woocommerce'); ?> smsc</th>
                <td>
                    <input type="password" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_password]" value="<?php
                    echo $booc_notification_options->getSmsServicePassword();
                    ?>" />
                    <span class="description"><?php _e('Your service password', 'buy-one-click-woocommerce'); ?> "SMSC"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use POST method', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_methodpost]" <?php
                    checked($booc_notification_options->isEnableSmsServicePostProtocol());
                    ?>/>
                    <span class="description"><?php _e('Use the POST method. By default, do not use', 'buy-one-click-woocommerce'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use HTTPS protocol', 'buy-one-click-woocommerce'); ?></th>
                <td>

                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_https]" <?php
                    checked($booc_notification_options->isEnableSmsServiceHttpsProtocol());
                    ?>/>
                    <span class="description"><?php _e('Use for sms https. Default - do not use', 'buy-one-click-woocommerce'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Encoding', 'buy-one-click-woocommerce'); ?> sms</th>
                <td>
                    <select name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_charset]">
                        <option value="utf-8" <?php selected($booc_notification_options->getSmsCharacterEncoding(), 'utf-8', true); ?>>UTF-8</option>
                        <option value="koi8-r" <?php selected($booc_notification_options->getSmsCharacterEncoding(), 'koi8-r', true); ?>>KOI8-R</option>
                        <option value="windows-1251" <?php selected($booc_notification_options->getSmsCharacterEncoding(), 'windows-1251', true); ?>>WINDOWS-1251</option>
                    </select>
                    <span class="description"><?php _e('SMS encoding of messages', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Customer SMS Template', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <textarea cols="50" rows="5" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_smshablon]"><?php
                        echo esc_attr($booc_notification_options->getSmsClientTemplate());
                        ?></textarea>
                    <!-- translators: %FIO% - customer name, %TPRICE% - order price -->
                    <span class="description"><?php
                        /* translators: %FIO% - customer name, %TPRICE% - order price */
                        _e('The specified template "%Template Name%" will be converted to form information. You can also enter any text.
                          For example: "Hello %FIO%, thanks for the order in the shop Screw and Shpuntik, the amount of your order is %TPRICE%"', 'buy-one-click-woocommerce'); ?></span>
                </td>

                <td>
                    <b>%FIO%</b> - <?php _e('Customer name', 'buy-one-click-woocommerce'); ?><br>
                    <b>%FON%</b> - <?php _e('Customer phone', 'buy-one-click-woocommerce'); ?><br>
                    <b>%EMAIL%</b> - <?php _e('Customer email', 'buy-one-click-woocommerce'); ?><br>
                    <b>%DOPINFO%</b> - <?php _e('Field add. information from the form', 'buy-one-click-woocommerce'); ?><br>
                    <b>%TPRICE%</b> - <?php _e('The price of the product', 'buy-one-click-woocommerce'); ?><br>
                    <b>%TNAME%</b> - <?php _e('Name of product', 'buy-one-click-woocommerce'); ?><br>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Seller SMS Template', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <textarea cols="50" rows="5" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_smshablon_saller]"><?php
                        echo $booc_notification_options->getSmsSellerTemplate();
                        ?></textarea>
                    <!-- translators: %FIO% - customer name, %TPRICE% - order price -->
                    <span class="description"><?php
                        /* translators: %FIO% - customer name, %TPRICE% - order price */
                        _e('The specified template "%Template Name%" will be converted to form information. You can also enter any text.
                          For example: "Hello %FIO%, thanks for the order in the shop Screw and Shpuntik, the amount of your order is %TPRICE%"', 'buy-one-click-woocommerce'); ?></span>
                </td>

                <td>
                    <b>%FIO%</b> - <?php _e('Customer name', 'buy-one-click-woocommerce'); ?><br>
                    <b>%FON%</b> - <?php _e('Customer phone', 'buy-one-click-woocommerce'); ?><br>
                    <b>%EMAIL%</b> - <?php _e('Customer email', 'buy-one-click-woocommerce'); ?><br>
                    <b>%DOPINFO%</b> - <?php _e('Field add. information from the form', 'buy-one-click-woocommerce'); ?><br>
                    <b>%TPRICE%</b> - <?php _e('The price of the product', 'buy-one-click-woocommerce'); ?><br>
                    <b>%TNAME%</b> - <?php _e('Name of product', 'buy-one-click-woocommerce'); ?><br>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Debug <?php _e('mode', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[sms_debug]" <?php
                    checked($booc_notification_options->isEnableSmsDebug());
                    ?>/>
                    <span class="description"><?php _e('Enable debug mode. Debub is off by default.', 'buy-one-click-woocommerce'); ?>.</span>
                </td>
            </tr>

        </table>
    </fieldset>
    <fieldset>
        <legend><?php _e('Other settings in this section', 'buy-one-click-woocommerce'); ?></legend>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Price in the email', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[price_including_tax]" <?php
                    checked($booc_notification_options->isEnablePriceWithTax());
                    ?>/>
                    <span class="description"><?php _e('Specify the price including tax', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Add order information to a WooCommerce email', 'buy-one-click-woocommerce'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS; ?>[modificationOrderTemplate]" <?php
                    checked($booc_notification_options->isEnableOrderInformationToTemplateWoo());
                    ?>/>
                    <span class="description"><?php _e('Enabling this setting will add information from the plugin to the Woocommerce email template.', 'buy-one-click-woocommerce'); ?></span>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="action" value="update" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'buy-one-click-woocommerce') ?>" />
    </p>

</form>
