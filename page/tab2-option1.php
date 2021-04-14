<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\Core;
?>
<h3><?php _e('Methods and notification settings for the client', 'coderun-oneclickwoo'); ?>  <?php echo Core::NAME_PLUGIN; ?></h3>
<?php
$buynotification = get_option('buynotification');
$buysmscoptions = get_option('buysmscoptions'); //настройки смсц
?>

<form method="post" action="options.php">
    <fieldset>
        <legend><?php _e('Setting E-mail Notifications', 'coderun-oneclickwoo'); ?></legend>
        <?php wp_nonce_field('update-options'); ?>
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php _e('Name from', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="buynotification[namemag]" value="<?php
                    if (isset($buynotification['namemag'])) {
                        echo $buynotification['namemag'];
                    }
                    ?>" />
                    <span class="description"><?php _e('Example', 'coderun-oneclickwoo'); ?> "<?php bloginfo('name'); ?>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Email From', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="buynotification[emailfrom]" value="<?php
                    if (isset($buynotification['emailfrom'])) {
                        echo $buynotification['emailfrom'];
                    }
                    ?>" />
                    <span class="description"><?php _e('Example', 'coderun-oneclickwoo'); ?> "izm@zixn.ru" </span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Email copy', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="buynotification[emailbbc]" value="<?php
                    if (isset($buynotification['emailbbc'])) {
                        echo $buynotification['emailbbc'];
                    }
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
                    <input type="checkbox" name="buynotification[infozakaz_chek]" <?php
                    if (isset($buynotification['infozakaz_chek'])) {
                        checked($buynotification['infozakaz_chek'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Send order data to customer. A tick is worth sending!', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Random information', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="buynotification[dopiczakaz_chek]" <?php
                    if (isset($buynotification['dopiczakaz_chek'])) {
                        checked($buynotification['dopiczakaz_chek'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Send additional data. You can specify any text.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Links to files', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="buynotification[links_to_files]" <?php
                    if (isset($buynotification['links_to_files'])) {
                        checked($buynotification['links_to_files'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Send links to downloaded files in emails?', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Random information', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <textarea cols="50" rows="10" name="buynotification[dopiczakaz]"><?php
                        if (isset($buynotification['dopiczakaz'])) {
                            echo $buynotification['dopiczakaz'];
                        }
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
                    <input type="checkbox" name="buysmscoptions[enable_smsc]" <?php
                    if (isset($buysmscoptions['enable_smsc'])) {
                        checked($buysmscoptions['enable_smsc'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Enable SMS notifications for client via service', 'coderun-oneclickwoo'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>" <?php _e('for quick order button. If ticked - SMS notifications will work', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Enable SMS Notifications Seller', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="buysmscoptions[enable_smsc_saller]" <?php
                    if (isset($buysmscoptions['enable_smsc_saller'])) {
                        checked($buysmscoptions['enable_smsc_saller'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Enable SMS notifications for the seller through the service - If checked, SMS notifications will work.', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Online Store Owners Phone', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="buysmscoptions[phone_saller]" value="<?php
                    if (isset($buysmscoptions['phone_saller'])) {
                        echo $buysmscoptions['phone_saller'];
                    }
                    ?>" />
                    <span class="description"><?php _e('Notifications on new orders will be sent to this phone number. Works when the daw is set higher', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Login', 'coderun-oneclickwoo'); ?> smsc</th>
                <td>
                    <input type="text" name="buysmscoptions[login]" value="<?php
                    if (isset($buysmscoptions['login'])) {
                        echo $buysmscoptions['login'];
                    }
                    ?>" />
                    <span class="description"><?php _e('Your login from the service', 'coderun-oneclickwoo'); ?> "<a href="http://smsc.ru/?ppzixn.ru" target="_blank">SMSC</a>"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Password', 'coderun-oneclickwoo'); ?> smsc</th>
                <td>
                    <input type="password" name="buysmscoptions[password]" value="<?php
                    if (isset($buysmscoptions['password'])) {
                        echo $buysmscoptions['password'];
                    }
                    ?>" />
                    <span class="description"><?php _e('Your service password', 'coderun-oneclickwoo'); ?> "SMSC"</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use POST method', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <select name="buysmscoptions[methodpost]">
                        <option value="0" <?php selected($buysmscoptions['methodpost'], '0', true); ?>><?php _e('Do not use', 'coderun-oneclickwoo'); ?></option>
                        <option value="1" <?php selected($buysmscoptions['methodpost'], '1', true); ?>><?php _e('Use', 'coderun-oneclickwoo'); ?></option>

                    </select>
                    <span class="description"><?php _e('Use the POST method. By default, do not use', 'coderun-oneclickwoo'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Use HTTPS protocol', 'coderun-oneclickwoo'); ?></th>
                <td>

                    <select name="buysmscoptions[https]">
                        <option value="0" <?php selected($buysmscoptions['https'], '0', true); ?>><?php _e('Do not use', 'coderun-oneclickwoo'); ?></option>
                        <option value="1" <?php selected($buysmscoptions['https'], '1', true); ?>><?php _e('Use', 'coderun-oneclickwoo'); ?></option>

                    </select>

                    <span class="description"><?php _e('Use for sms https. Default - do not use', 'coderun-oneclickwoo'); ?>.</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Encoding', 'coderun-oneclickwoo'); ?> sms</th>
                <td>
                    <select name="buysmscoptions[charset]">
                        <option value="utf-8" <?php selected($buysmscoptions['charset'], 'utf-8', true); ?>>UTF-8</option>
                        <option value="koi8-r" <?php selected($buysmscoptions['charset'], 'koi8-r', true); ?>>KOI8-R</option>
                        <option value="windows-1251" <?php selected($buysmscoptions['charset'], 'windows-1251', true); ?>>WINDOWS-1251</option>
                    </select>
                    <span class="description"><?php _e('SMS encoding of messages', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Customer SMS Template', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <textarea cols="50" rows="5" name="buysmscoptions[smshablon]"><?php
                        if (isset($buysmscoptions['smshablon'])) {
                            echo $buysmscoptions['smshablon'];
                        }
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
                    <textarea cols="50" rows="5" name="buysmscoptions[smshablon_saller]"><?php
                        if (isset($buysmscoptions['smshablon_saller'])) {
                            echo $buysmscoptions['smshablon_saller'];
                        }
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

                    <select name="buysmscoptions[debug]">
                        <option value="0" <?php selected($buysmscoptions['debug'], '0', true); ?>>Выключить</option>
                        <option value="1" <?php selected($buysmscoptions['debug'], '1', true); ?>>Включить</option>

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
                    <input type="checkbox" name="buynotification[price_including_tax]" <?php
                    if (isset($buynotification['price_including_tax'])) {
                        checked($buynotification['price_including_tax'], 'on', 1);
                    }
                    ?>/>
                    <span class="description"><?php _e('Specify the price including tax', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="buynotification,buysmscoptions" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
