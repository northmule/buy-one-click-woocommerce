<?php
use Coderun\BuyOneClick\Core;

if (!defined('ABSPATH')) {
    exit;
}

?>
<form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
    <?php settings_fields(sprintf('%s_options', Core::OPTIONS_MARKETING)); ?>

    <h3><?php _e('Additional settings','coderun-oneclickwoo'); ?></h3>
    <p><?php _e('Example','coderun-oneclickwoo') ?>: <a target="_blank" href="https://coderun.ru/blog/kak-v-plagin-buy-one-click-woocommerce-dobavit-celi-po-sobytiya-javascript/"><?php _e('Switching to another site','coderun-oneclickwoo') ?></a> </p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Event after clicking the button','coderun-oneclickwoo'); ?></th>
            <td>
                <?php
                $codeMirrorSetting = wp_enqueue_code_editor([ 'type' => 'application/javascript' ]);
                if ($codeMirrorSetting !== false) {
                    wp_add_inline_script(
                        'code-editor',
                        sprintf('jQuery( function() { wp.codeEditor.initialize( "after_clicking_on_button", %s ); } );', wp_json_encode($codeMirrorSetting))
                    );
                }
                \wp_editor(Core::getInstance()->getOption('after_clicking_on_button', Core::OPTIONS_MARKETING), 'after_clicking_on_button', [
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
                    <?php _e('The JavaScript code that will be called when you click on the button to open the order form.','coderun-oneclickwoo'); ?>
                        <br>
                     <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.','coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Event after successful form submission','coderun-oneclickwoo'); ?></th>
            <td>
                <?php
                $codeMirrorSetting = wp_enqueue_code_editor([ 'type' => 'application/javascript' ]);
                if ($codeMirrorSetting !== false) {
                    wp_add_inline_script(
                        'code-editor',
                        sprintf('jQuery( function() { wp.codeEditor.initialize( "successful_form_submission", %s ); } );', wp_json_encode($codeMirrorSetting))
                    );
                }
                \wp_editor(Core::getInstance()->getOption('successful_form_submission', Core::OPTIONS_MARKETING), 'successful_form_submission', [
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
                        <?php _e('The JavaScript code that will be called after the successful submission of the order form.','coderun-oneclickwoo'); ?>
                        <br>
                    <?php _e('Please note that the code may disrupt the normal operation of the plugin. Always check the changes after saving the settings.','coderun-oneclickwoo'); ?>
                    </span>
                </p>
            </td>
        </tr>
    </table>
    <input type="hidden" name="action" value="update" />
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>