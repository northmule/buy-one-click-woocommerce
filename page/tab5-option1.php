<?php
if (!defined('ABSPATH')) {
    exit;
}

use Coderun\BuyOneClick\Core;

/** @var Core $this */

$marketingOptions = $this->getMarketingOptions();
?>
<form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields(sprintf('%s_options', Core::OPTIONS_MARKETING)); ?>

    <h3><?php _e('Additional settings', 'coderun-oneclickwoo'); ?></h3>
    <p><?php _e('Example', 'coderun-oneclickwoo') ?>: <a target="_blank" href="https://coderun.ru/blog/kak-v-plagin-buy-one-click-woocommerce-dobavit-celi-po-sobytiya-javascript/"><?php _e('Switching to another site', 'coderun-oneclickwoo') ?></a> </p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Event after clicking the button', 'coderun-oneclickwoo'); ?></th>
            <td>
                <?php
                $codeMirrorSetting = wp_enqueue_code_editor([ 'type' => 'application/javascript' ]);
                if ($codeMirrorSetting !== false) {
                    wp_add_inline_script(
                        'code-editor',
                        sprintf('jQuery( function() { wp.codeEditor.initialize( "after_clicking_on_button", %s ); } );', wp_json_encode($codeMirrorSetting))
                    );
                }
                \wp_editor($marketingOptions->getAfterClickingOnButton(), 'after_clicking_on_button', [
                    'wpautop' => false,
                    'media_buttons' => 0,
                    'textarea_name' => \sprintf('%s[after_clicking_on_button]', Core::OPTIONS_MARKETING),
                    'textarea_rows' => 5,
                    'tabindex' => null,
                    'editor_css' => '',
                    'editor_class' => '',
                    'teeny' => 0,
                    'dfw' => 0,
                    'tinymce' => 0,
                    'quicktags' => 0,
                    'drag_drop_upload' => 0
                ]);
                ?>
                <p><span class="description">
                    <?php _e('The JavaScript code that will be called when you click on the button to open the order form.', 'coderun-oneclickwoo'); ?>
                        <br>
                     <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.', 'coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Event after successful form submission', 'coderun-oneclickwoo'); ?></th>
            <td>
                <?php
                $codeMirrorSetting = wp_enqueue_code_editor([ 'type' => 'application/javascript' ]);
                if ($codeMirrorSetting !== false) {
                    wp_add_inline_script(
                        'code-editor',
                        sprintf('jQuery( function() { wp.codeEditor.initialize( "successful_form_submission", %s ); } );', wp_json_encode($codeMirrorSetting))
                    );
                }
                \wp_editor($marketingOptions->getSuccessfulFormSubmission(), 'successful_form_submission', [
                    'wpautop' => false,
                    'media_buttons' => 0,
                    'textarea_name' => \sprintf('%s[successful_form_submission]', Core::OPTIONS_MARKETING),
                    'textarea_rows' => 5,
                    'tabindex' => null,
                    'editor_css' => '',
                    'editor_class' => '',
                    'teeny' => 0,
                    'dfw' => 0,
                    'tinymce' => 0,
                    'quicktags' => 0,
                    'drag_drop_upload' => 0
                ]);
                ?>
                <p><span class="description">
                        <?php _e('The JavaScript code that will be called after the successful submission of the order form.', 'coderun-oneclickwoo'); ?>
                        <br>
                    <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.', 'coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
    </table>
    <fieldset>
        <legend><?php _e('Data transfer to Yandex e-commerce event of product purchase', 'coderun-oneclickwoo'); ?></legend>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"></th>
                <td>
                    <p>
                        <span class="description">
                        <?php _e('When you specify these settings, data will be transmitted to the Yandex Metrica - e-commerce service. This event is a product purchase event.', 'coderun-oneclickwoo'); ?>
                        <br>
                    <p><?php _e('Example', 'coderun-oneclickwoo') ?>: <a target="_blank" href="https://yandex.ru/support/metrica/ecommerce/data.html"><?php _e('Switching to another site', 'coderun-oneclickwoo') ?></a> </p>
                        
                        </span>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Transmit data', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="checkbox" name="<?php echo Core::OPTIONS_MARKETING ?>[transfer_data_to_yandex_commerce]" <?php
                    checked($marketingOptions->isTransferDataToYandexCommerce());
                    ?>/>
                    <span class="description"><?php _e('Enable data transfer to the Yandex e-commerce service', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Name of the data container', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_MARKETING ?>[name_of_yandex_metrica_data_container]" value="<?php
                    echo $marketingOptions->getNameOfYandexMetricaDataContainer();
                    ?>" />
                    <span class="description"><?php _e('Name of the yandex Metrica data container. The default value is "dataLayer"', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Goal ID', 'coderun-oneclickwoo'); ?></th>
                <td>
                    <input type="text" name="<?php echo Core::OPTIONS_MARKETING ?>[goal_id_in_yandex_e_commerce]" value="<?php
                    echo $marketingOptions->getGoalIdInYandexECommerce();
                    ?>" />
                    <span class="description"><?php _e('Goal ID. If used, specify it here', 'coderun-oneclickwoo'); ?></span>
                </td>
            </tr>

        </table>
    </fieldset>
    <input type="hidden" name="action" value="update" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>