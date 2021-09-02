<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;
?>
<h3><?php _e('Methods and notification settings for the client', 'coderun-oneclickwoo'); ?>  <?php echo Core::NAME_PLUGIN; ?></h3>

<form method="post" action="options.php">
    <fieldset>
        <legend><?php _e('Setting E-mail Notifications', 'coderun-oneclickwoo'); ?></legend>
        <?php wp_nonce_field('update-options'); ?>
        <?php settings_fields(sprintf('%s_options', Core::OPTIONS_NOTIFICATIONS)); ?>
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php _e('Name from', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[namemag]" value="<?php
                    echo Core::getInstance()->getOption('namemag',Core::OPTIONS_NOTIFICATIONS);
                    ?>" />
                    <span class="description"><?php _e('Example', 'coderun-oneclickwoo'); ?> "<?php bloginfo('name'); ?>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Email From', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[emailfrom]" value="<?php
                    echo Core::getInstance()->getOption('emailfrom',Core::OPTIONS_NOTIFICATIONS);
                    ?>" />
                    <span class="description"><?php _e('Example', 'coderun-oneclickwoo'); ?> "izm@zixn.ru" </span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Email copy', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[emailbbc]" value="<?php
                    echo Core::getInstance()->getOption('emailbbc',Core::OPTIONS_NOTIFICATIONS);
                    ?>" />
                    <span class="description"><?php _e('This email will receive a copy of the order messages. Through the sign "," you can specify multiple Email. Example:', 'coderun-oneclickwoo'); ?>
                        shop@mail.ru, jora@mail.ru, barak-obama@google.com</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Optional Options', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <span class="description"><?php _e('Tick off the boxes to be sent.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Ordering information', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[infozakaz_chek]" <?php
                    checked(Core::getInstance()->getOption('infozakaz_chek',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Send order data to customer. A tick is worth sending!', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Random information', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[dopiczakaz_chek]" <?php
                    checked(Core::getInstance()->getOption('dopiczakaz_chek',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Send additional data. You can specify any text.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Links to files', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[links_to_files]" <?php
                    checked(Core::getInstance()->getOption('links_to_files',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Send links to downloaded files in emails?', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Random information', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <textarea cols="50" rows="10" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[dopiczakaz]"><?php
                        echo Core::getInstance()->getOption('dopiczakaz',Core::OPTIONS_NOTIFICATIONS);
                        ?></textarea>
                    <span class="description"><?php _e('Arbitrary information, such as contacts or a wish. It is possible to specify the HTML tag', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

        </table>
    </fieldset>
    <fieldset>
        <legend><?php _e('Setting SMS notifications', 'coderun-oneclickwoo'); ?></legend>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Enable SMS Customer Notifications', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_enable_smsc]" <?php
                    checked(Core::getInstance()->getOption('sms_enable_smsc',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Enable SMS notifications for client via service', 'coderun-oneclickwoo'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>" <?php _e('for quick order button. If ticked - SMS notifications will work', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable SMS Notifications Seller', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_enable_smsc_saller]" <?php
                    checked(Core::getInstance()->getOption('sms_enable_smsc_saller',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Enable SMS notifications for the seller through the service - If checked, SMS notifications will work.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Online Store Owners Phone', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_phone_saller]" value="<?php
                    echo Core::getInstance()->getOption('sms_phone_saller',Core::OPTIONS_NOTIFICATIONS);
                    ?>" />
                    <span class="description"><?php _e('Notifications on new orders will be sent to this phone number. Works when the daw is set higher', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Login', 'coderun-oneclickwoo'); ?> smsc</th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_login]" value="<?php
                    echo Core::getInstance()->getOption('sms_login',Core::OPTIONS_NOTIFICATIONS)
                    ?>" />
                    <span class="description"><?php _e('Your login from the service', 'coderun-oneclickwoo'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Password', 'coderun-oneclickwoo'); ?> smsc</th>
                <td>
                    <input type="password" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_password]" value="<?php
                    echo Core::getInstance()->getOption('sms_password',Core::OPTIONS_NOTIFICATIONS)
                    ?>" />
                    <span class="description"><?php _e('Your service password', 'coderun-oneclickwoo'); ?> "SMSC"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use POST method', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <select name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_methodpost]">
                        <option value="0" <?php selected(Core::getInstance()->getOption('sms_methodpost',Core::OPTIONS_NOTIFICATIONS), '0', true); ?>><?php _e('Do not use', 'coderun-oneclickwoo'); ?></option>
                        <option value="1" <?php selected(Core::getInstance()->getOption('sms_methodpost',Core::OPTIONS_NOTIFICATIONS), '1', true); ?>><?php _e('Use', 'coderun-oneclickwoo'); ?></option>

                    </select>
                    <span class="description"><?php _e('Use the POST method. By default, do not use', 'coderun-oneclickwoo'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use HTTPS protocol', 'coderun-oneclickwoo'); ?></th>
                <td>

                    <select name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_https]">
                        <option value="0" <?php selected(Core::getInstance()->getOption('sms_https',Core::OPTIONS_NOTIFICATIONS), '0', true); ?>><?php _e('Do not use', 'coderun-oneclickwoo'); ?></option>
                        <option value="1" <?php selected(Core::getInstance()->getOption('sms_https',Core::OPTIONS_NOTIFICATIONS), '1', true); ?>><?php _e('Use', 'coderun-oneclickwoo'); ?></option>

                    </select>

                    <span class="description"><?php _e('Use for sms https. Default - do not use', 'coderun-oneclickwoo'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Encoding', 'coderun-oneclickwoo'); ?> sms</th>
                <td>
                    <select name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_charset]">
                        <option value="utf-8" <?php selected(Core::getInstance()->getOption('sms_charset',Core::OPTIONS_NOTIFICATIONS), 'utf-8', true); ?>>UTF-8</option>
                        <option value="koi8-r" <?php selected(Core::getInstance()->getOption('sms_charset',Core::OPTIONS_NOTIFICATIONS), 'koi8-r', true); ?>>KOI8-R</option>
                        <option value="windows-1251" <?php selected(Core::getInstance()->getOption('sms_charset',Core::OPTIONS_NOTIFICATIONS), 'windows-1251', true); ?>>WINDOWS-1251</option>
                    </select>
                    <span class="description"><?php _e('SMS encoding of messages', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Customer SMS Template', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <textarea cols="50" rows="5" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_smshablon]"><?php
                        echo Core::getInstance()->getOption('sms_smshablon',Core::OPTIONS_NOTIFICATIONS);
                        ?></textarea>
                    <span class="description"><?php _e('The specified template "%Template Name%" will be converted to form information. You can also enter any text.
                         For example: "Hello %FIO%, thanks for the order in the shop Screw and Shpuntik, the amount of your order is %TPRICE%"', 'coderun-oneclickwoo'); ?></span>
                </td>

                <td>
                    <b>%FIO%</b> - <?php _e('Customer name', 'coderun-oneclickwoo'); ?><br>
                    <b>%FON%</b> - <?php _e('Customer phone', 'coderun-oneclickwoo'); ?><br>
                    <b>%EMAIL%</b> - <?php _e('Customer email', 'coderun-oneclickwoo'); ?><br>
                    <b>%DOPINFO%</b> - <?php _e('Field add. information from the form', 'coderun-oneclickwoo'); ?><br>
                    <b>%TPRICE%</b> - <?php _e('The price of the product', 'coderun-oneclickwoo'); ?><br>
                    <b>%TNAME%</b> - <?php _e('Name of product', 'coderun-oneclickwoo'); ?><br>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Seller SMS Template', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <textarea cols="50" rows="5" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_smshablon_saller]"><?php
                            echo Core::getInstance()->getOption('sms_smshablon_saller',Core::OPTIONS_NOTIFICATIONS);
                        ?></textarea>
                    <span class="description"><?php _e('The specified template "%Template Name%" will be converted to form information. You can also enter any text.
                         For example: "Hello %FIO%, thanks for the order in the shop Screw and Shpuntik, the amount of your order is %TPRICE%"', 'coderun-oneclickwoo'); ?></span>
                </td>

                <td>
                    <b>%FIO%</b> - <?php _e('Customer name', 'coderun-oneclickwoo'); ?><br>
                    <b>%FON%</b> - <?php _e('Customer phone', 'coderun-oneclickwoo'); ?><br>
                    <b>%EMAIL%</b> - <?php _e('Customer email', 'coderun-oneclickwoo'); ?><br>
                    <b>%DOPINFO%</b> - <?php _e('Field add. information from the form', 'coderun-oneclickwoo'); ?><br>
                    <b>%TPRICE%</b> - <?php _e('The price of the product', 'coderun-oneclickwoo'); ?><br>
                    <b>%TNAME%</b> - <?php _e('Name of product', 'coderun-oneclickwoo'); ?><br>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Debug <?php _e('mode', 'coderun-oneclickwoo'); ?></th>
                <td>

                    <select name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[sms_debug]">
                        <option value="0" <?php selected(Core::getInstance()->getOption('sms_debug',Core::OPTIONS_NOTIFICATIONS), '0', true); ?>>Выключить</option>
                        <option value="1" <?php selected(Core::getInstance()->getOption('sms_debug',Core::OPTIONS_NOTIFICATIONS), '1', true); ?>>Включить</option>

                    </select>

                    <span class="description"><?php _e('Enable debug mode. Debub is off by default.', 'coderun-oneclickwoo'); ?>.</span>
                </td>
            </tr>

        </table>
    </fieldset>
    <fieldset>
        <legend><?php _e('Other settings in this section', 'coderun-oneclickwoo'); ?></legend>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Price in the email', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[price_including_tax]" <?php
                    checked(Core::getInstance()->getOption('price_including_tax',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Specify the price including tax', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Add order information to a WooCommerce email', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_NOTIFICATIONS ?>[modificationOrderTemplate]" <?php
                    checked(Core::getInstance()->getOption('modificationOrderTemplate',Core::OPTIONS_NOTIFICATIONS), 'on', 1);
                    ?>/>
                    <span class="description"><?php _e('Enabling this setting will add information from the plugin to the Woocommerce email template.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="action" value="update" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
